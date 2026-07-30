<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Prediction;

class PrediksiController extends Controller
{
    public function index()
    {
        return view('prediksi.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'prodi'       => 'required|string|max:255',
            'ipk'         => 'required|numeric|min:0|max:4',
            'sks'         => 'required|integer|min:0',
            'penghasilan' => 'required|numeric|min:0',
            'tanggungan'  => 'required|integer|min:0',
            'organisasi'  => 'required|string|in:Ya,Tidak',
        ]);

        $prediction = Prediction::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil disimpan.',
            'id'      => $prediction->id,
        ]);
    }
}