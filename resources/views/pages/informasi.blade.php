@extends('layouts.app')

@section('title', 'Informasi & Panduan - Sistem Informasi Penerimaan Beasiswa')

@section('content')
<div style="padding-top: 120px; padding-bottom: 60px; background: #f8fafc; min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="fw-bold mb-2">Informasi & Panduan Sistem</h2>
                <p class="text-muted">Panduan lengkap mengenai tata cara penggunaan sistem prediktor beasiswa dan pertanyaan yang sering diajukan.</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <!-- Panduan Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h4 class="fw-bold mb-3 text-primary"><i class="bi bi-journal-check me-2"></i> Alur Penggunaan Prediksi Beasiswa</h4>
                    <ol class="text-muted ps-3 mb-0" style="line-height: 2;">
                        <li><strong>Login/Register:</strong> Masuk ke akun mahasiswa Anda menggunakan email kampus yang terdaftar.</li>
                        <li><strong>Input Data Akademik:</strong> Buka menu <a href="{{ route('prediction.index') }}">Prediksi</a> dan masukkan data seperti IPK, jumlah SKS, penghasilan orang tua, serta keaktifan organisasi.</li>
                        <li><strong>Analisis Sistem:</strong> Sistem akan memproses data menggunakan metode <em>Decision Tree</em> secara instan.</li>
                        <li><strong>Hasil Rekomendasi:</strong> Anda akan langsung mendapatkan status kelayakan beserta tingkat kepastian (confidence score) dari sistem.</li>
                    </ol>
                </div>

                <!-- FAQ Section (Menggunakan Card biasa agar teks pasti tampil dan aman dari konflik JS) -->
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h4 class="fw-bold mb-3 text-primary"><i class="bi bi-question-circle me-2"></i> Pertanyaan Umum (FAQ)</h4>
                    
                    <div class="mb-4 pb-3 border-bottom">
                        <h6 class="fw-bold text-dark mb-2">
                            <i class="bi bi-question-circle-fill text-primary me-2"></i> Apakah hasil prediksi ini bersifat mutlak?
                        </h6>
                        <p class="text-secondary small mb-0 ps-4" style="line-height: 1.7;">
                            Hasil prediksi adalah rekomendasi awal berbasis kecerdasan buatan dan data akademik Anda untuk membantu tim seleksi dalam mengambil keputusan yang objektif.
                        </p>
                    </div>

                    <div>
                        <h6 class="fw-bold text-dark mb-2">
                            <i class="bi bi-question-circle-fill text-primary me-2"></i> Bagaimana jika data IPK atau SKS saya salah input?
                        </h6>
                        <p class="text-secondary small mb-0 ps-4" style="line-height: 1.7;">
                            Anda dapat mengulangi proses pengisian form prediksi kapan saja dengan data akademik yang sudah diperbarui.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection