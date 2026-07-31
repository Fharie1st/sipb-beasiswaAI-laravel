<?php

namespace App\Http\Controllers;

use App\Models\Prediction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PredictionController extends Controller
{
    /**
     * Base URL Flask API. Pindahkan ke .env (FLASK_API_URL) kalau perlu
     * beda antara local/production.
     */
    protected string $flaskUrl;

    public function __construct()
    {
        $this->flaskUrl = env('FLASK_URL');
    }

    /**
     * Halaman pilihan fitur
     */
    public function index()
    {
        return view('prediction.index');
    }

    /**
     * Halaman chatbot prediksi
     */
    public function chat()
    {
        return view('prediction.chat');
    }

    /**
     * Halaman AI Assistant Beasiswa
     */
    public function assistant()
    {
        return view('assistant.chat');
    }

    /**
     * Menampilkan halaman pengaturan profil
     */
    public function editProfile()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Mengupdate data profil user (Nama, Email, Avatar, & Password)
     */
    public function updateProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 1. Validasi Input Profil, Avatar, dan Password
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'avatar' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Maksimal 2MB
            'current_password' => ['nullable', 'required_with:new_password', 'string'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        // 2. Update Nama & Email
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
        
            $filename = time() . '_' . $file->getClientOriginalName();
        
            $file->move(public_path('avatars'), $filename);
        
            $user->avatar = 'avatars/' . $filename;
        }

        // 4. Proses Ubah Password (Jika diisi)
        if ($request->filled('current_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai.'])->withInput();
            }

            $user->password = Hash::make($request->new_password);
        }

        // 5. Simpan Perubahan ke Database
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Resize gambar avatar ke ukuran maksimal (menjaga rasio) dan kompres
     * jadi JPEG kualitas rendah, lalu kembalikan sebagai data URI base64.
     * Tujuannya supaya ukuran data yang disimpan ke kolom `avatar` selalu
     * kecil, jadi aman dari limit max_allowed_packet MySQL.
     */

    /**
     * Proses prediksi ke API Python, lalu simpan hasilnya ke database
     * supaya bisa muncul di tab Riwayat. Dipanggil via fetch dari chat.js,
     * jadi HARUS selalu balikin JSON, bukan view.
     */
    public function predict(Request $request)
    {
        // 1. Validasi input dari form
        $validated = $request->validate([
            'prodi'       => 'required|string',
            'ipk'         => 'required|numeric|min:0|max:4',
            'sks'         => 'required|numeric|min:0',
            'penghasilan' => 'required|string|in:Rendah,Sedang,Tinggi',
            'tanggungan'  => 'required|numeric|min:0',
            'organisasi'  => 'required|string|in:Ya,Tidak',
        ]);

        // 2. Konversi penghasilan (string -> angka) sesuai kolom di database:
        //    Rendah = 1, Sedang = 2, Tinggi = 3
        $penghasilanValue = match ($validated['penghasilan']) {
            'Rendah' => 1,
            'Sedang' => 2,
            'Tinggi' => 3,
            default  => 1,
        };

        // 3. Payload untuk API Flask. Khusus untuk Flask, organisasi diubah
        //    dari Ya/Tidak menjadi Ikut/Tidak (tidak memengaruhi kolom di DB).
        $flaskPayload = [
            'Prodi' => $validated['prodi'],
            'IPK' => $validated['ipk'],
            'SKS' => $validated['sks'],
            'Penghasilan' => $validated['penghasilan'],
            'Tanggungan' => $validated['tanggungan'],
            'Ikut Organisasi' => $validated['organisasi'] === 'Ya'
                ? 'Ikut'
                : 'Tidak',
        ];

        try {
            $response = Http::timeout(15)->post(
                "{$this->flaskUrl}/predict",
                $flaskPayload
            );

            if (! $response->successful()) {
                $flaskError = $response->json();

                Log::error(
                    'PREDICT - Flask returned error: ' .
                    json_encode($flaskError)
                );

                return response()->json([
                    'success' => true,
                    'data' => $predictions,
                ]);
            }

            $result = $response->json();

            // 4. Simpan hasil ke database (HANYA SATU KALI, setelah hasil dari
            //    Flask didapat, agar prediction/confidence/accuracy terisi benar)
            Prediction::create([
                'user_id'     => Auth::id(),
                'nama'        => Auth::user()->name,
                'prodi'       => $validated['prodi'],
                'ipk'         => $validated['ipk'],
                'sks'         => $validated['sks'],
                'tanggungan'  => $validated['tanggungan'],
                'kehadiran'   => 0,
                'prestasi'    => 'Tidak',
                'organisasi'  => $validated['organisasi'],
                'penghasilan' => $penghasilanValue,
                'semester'    => 1,
                'hasil'       => $result['prediction'] ?? 0,
                'confidence'  => $result['confidence'] ?? 0,
            ]);

            return response()->json($result);
        } catch (\Exception $e) {
            Log::error(
                'PREDICT - Flask connection failed: ' .
                $e->getMessage()
            );

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Proses tanya-jawab bebas ke ScholarAI Assistant (Flask /ask).
     */
    public function ask(Request $request)
    {
        $request->validate([
            'question' => 'required|string',
        ]);

        try {
            $response = Http::timeout(15)->post("{$this->flaskUrl}/ask", [
                'question' => $request->input('question'),
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return response()->json([
                    'answer' => $data['answer'] ?? 'Maaf, saya belum bisa menjawab pertanyaan itu.',
                ]);
            }

            return response()->json([
                'answer' => 'Maaf, terjadi kendala saat menghubungi ScholarAI Assistant.',
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'answer' => 'Server AI Assistant belum dijalankan.',
            ], 500);
        }
    }

    /**
     * Riwayat hasil analisis milik user yang sedang login.
     */
    public function history()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
    
        $predictions = $user->predictions()
            ->latest()
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nama' => $p->nama,
                    'prodi' => $p->prodi,
                    'ipk' => $p->ipk,
                    'penghasilan' => $p->penghasilan,
                    'organisasi' => $p->organisasi,
                    'prediction' => $p->hasil,
                    'confidence' => $p->confidence,
                    'created_at' => $p->created_at->format('d M Y, H:i'),
                ];
            });
    
        return response()->json([
            'success' => true,
            'data' => $predictions,
        ]);
    }

    /**
     * Update satu data riwayat prediksi milik user yang sedang login.
     */
    public function update(Request $request, $id)
{
    /** @var \App\Models\User $user */
    $user = Auth::user();

    $predictionItem = $user->predictions()->find($id);

    if (! $predictionItem) {
        return response()->json([
            'success' => false,
            'message' => 'Data tidak ditemukan.',
        ], 404);
    }

    $validated = $request->validate([
        'prodi'       => 'required|string',
        'ipk'         => 'required|numeric|min:0|max:4',
        'sks'         => 'required|numeric|min:0',
        'penghasilan' => 'required|string|in:Rendah,Sedang,Tinggi',
        'tanggungan'  => 'required|numeric|min:0',
        'organisasi'  => 'required|string|in:Ya,Tidak',
    ]);

    // Konversi penghasilan (string -> angka), sama seperti di method predict()
    $penghasilanValue = match ($validated['penghasilan']) {
        'Rendah' => 1,
        'Sedang' => 2,
        'Tinggi' => 3,
        default  => 1,
    };

    $predictionItem->update([
        'prodi'       => $validated['prodi'],
        'ipk'         => $validated['ipk'],
        'sks'         => $validated['sks'],
        'penghasilan' => $penghasilanValue,
        'tanggungan'  => $validated['tanggungan'],
        'organisasi'  => $validated['organisasi'],
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Data berhasil diperbarui.',
        'data'    => [
            'id'          => $predictionItem->id,
            'nama'        => $predictionItem->nama,
            'prodi'       => $predictionItem->prodi,
            'ipk'         => $predictionItem->ipk,
            'sks'         => $predictionItem->sks,
            'penghasilan' => $predictionItem->penghasilan,
            'tanggungan'  => $predictionItem->tanggungan,
            'organisasi'  => $predictionItem->organisasi,
            'prediction'  => $predictionItem->hasil,
            'confidence'  => $predictionItem->confidence,
            'created_at'  => $predictionItem->created_at->format('d M Y, H:i'),
        ],
    ]);
}
    /**
     * Hapus satu data riwayat prediksi milik user yang sedang login.
     */
    public function destroy($id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $predictionItem = $user->predictions()->find($id);

        if (! $predictionItem) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan.',
            ], 404);
        }

        $predictionItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil dihapus.',
        ]);
    }
}
