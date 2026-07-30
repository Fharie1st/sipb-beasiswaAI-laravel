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

        // 3. Proses Upload Avatar (Foto Profil) Baru
        // Foto di-resize & dikompres dulu (maks 400px, kualitas 75) sebelum
        // disimpan sebagai base64 di kolom 'avatar'. Ini membuat ukurannya
        // selalu kecil (biasanya puluhan KB saja), jadi tidak akan pernah
        // menabrak limit max_allowed_packet MySQL, dan tidak ada file yang
        // ditulis ke folder storage sama sekali.
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $user->avatar = $this->resizeAndEncodeAvatar($file->getRealPath());
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
    private function resizeAndEncodeAvatar(string $path, int $maxSize = 400, int $quality = 75): string
    {
        $info = getimagesize($path);
        $type = $info[2] ?? null;

        $source = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($path),
            IMAGETYPE_PNG  => imagecreatefrompng($path),
            IMAGETYPE_GIF  => imagecreatefromgif($path),
            default        => imagecreatefromstring(file_get_contents($path)),
        };

        $width  = imagesx($source);
        $height = imagesy($source);

        // Jangan diperbesar kalau fotonya memang sudah kecil, cukup di-cap maksimal
        $ratio     = min($maxSize / $width, $maxSize / $height, 1);
        $newWidth  = max(1, (int) round($width * $ratio));
        $newHeight = max(1, (int) round($height * $ratio));

        $resized = imagecreatetruecolor($newWidth, $newHeight);

        // Kasih background putih dulu (biar PNG transparan gak jadi hitam pas dijadiin JPEG)
        $white = imagecolorallocate($resized, 255, 255, 255);
        imagefill($resized, 0, 0, $white);

        imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        ob_start();
        imagejpeg($resized, null, $quality);
        $data = ob_get_clean();

        imagedestroy($source);
        imagedestroy($resized);

        return 'data:image/jpeg;base64,' . base64_encode($data);
    }

    /**
     * Proses prediksi ke API Python, lalu simpan hasilnya ke database
     * supaya bisa muncul di tab Riwayat. Dipanggil via fetch dari chat.js,
     * jadi HARUS selalu balikin JSON, bukan view.
     */
    public function predict(Request $request)
    {
        $validated = $request->validate([
            'prodi'       => 'required|string',
            'ipk'         => 'required|numeric|min:0|max:4',
            'sks'         => 'required|numeric|min:0',
            'penghasilan' => 'required|string|in:Rendah,Sedang,Tinggi',
            'tanggungan'  => 'required|numeric|min:0',
            'Ikut Organisasi' => $validated['organisasi'] === 'Ya'
                ? 'Ikut'
                : 'Tidak',
        ]);

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

            $response = Http::timeout(15)->post("{$this->flaskUrl}/predict", $flaskPayload);

            if (! $response->successful()) {

                $flaskError = $response->json();

                Log::error('PREDICT - Flask returned error: ' . json_encode($flaskError));

                return response()->json([
                    'success' => false,
                    'message' => $flaskError['message'] ?? 'Gagal menghubungi sistem prediksi.',
                ], $response->status());
            }

            $result = $response->json();

            Prediction::create([
            'user_id'     => Auth::id(),
            'prodi'       => $validated['prodi'],
            'ipk'         => $validated['ipk'],
            'sks'         => $validated['sks'],
            'penghasilan' => $validated['penghasilan'],
            'tanggungan'  => $validated['tanggungan'],
            'organisasi'  => $validated['organisasi'],
            'prediction'  => $result['prediction'] ?? 0,
            'confidence'  => $result['confidence'] ?? 0,
            'accuracy'    => $result['accuracy'] ?? null,
        ]);

            return response()->json($result);

        } catch (\Exception $e) {

            Log::error('PREDICT - Flask connection failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server Machine Learning belum dijalankan.',
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
                    'id'          => $p->id,
                    'prodi'       => $p->prodi,
                    'ipk'         => $p->ipk,
                    'sks'         => $p->sks,
                    'penghasilan' => $p->penghasilan,
                    'tanggungan'  => $p->tanggungan,
                    'organisasi'  => $p->organisasi,
                    'prediction'  => $p->prediction,
                    'confidence'  => $p->confidence,
                    'accuracy'    => $p->accuracy,
                    'created_at'  => $p->created_at->format('d M Y, H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'data'    => $predictions,
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

        $predictionItem->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diperbarui.',
            'data'    => [
                'id'          => $predictionItem->id,
                'prodi'       => $predictionItem->prodi,
                'ipk'         => $predictionItem->ipk,
                'sks'         => $predictionItem->sks,
                'penghasilan' => $predictionItem->penghasilan,
                'tanggungan'  => $predictionItem->tanggungan,
                'organisasi'  => $predictionItem->organisasi,
                'prediction'  => $predictionItem->prediction,
                'confidence'  => $predictionItem->confidence,
                'accuracy'    => $predictionItem->accuracy,
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
