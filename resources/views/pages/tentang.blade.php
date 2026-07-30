@extends('layouts.app')

@section('title', 'Tentang - Sistem Informasi Penerimaan Beasiswa')

@section('content')
<div style="padding-top: 120px; padding-bottom: 60px; background: #f8fafc; min-height: 80vh;">
    <div class="container">
        <!-- Header Halaman -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 text-center">
                <h2 class="fw-bold mb-2">Tentang Sistem</h2>
                <p class="text-muted">Mengenal lebih dekat mengenai Sistem Informasi Penerimaan Beasiswa dan teknologi yang digunakan.</p>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-9">
                <!-- Card 1: Latar Belakang / Deskripsi Aplikasi -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h4 class="fw-bold mb-3 text-primary"><i class="bi bi-info-circle me-2"></i> Apa itu SIPB?</h4>
                    <p class="text-secondary" style="line-height: 1.8;">
                        <strong>Sistem Informasi Penerimaan Beasiswa (SIPB)</strong> adalah platform berbasis web yang dirancang untuk membantu pihak institusi atau kampus dalam menyeleksi calon penerima beasiswa secara cepat, transparan, dan objektif. Sistem ini mempermudah proses rekapitulasi data akademik mahasiswa hingga penentuan kelayakan akhir.
                    </p>
                </div>

                <!-- Card 2: Penjelasan Metode Decision Tree -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h4 class="fw-bold mb-3 text-primary"><i class="bi bi-cpu me-2"></i> Teknologi & Metode Decision Tree</h4>
                    <p class="text-secondary mb-3" style="line-height: 1.8;">
                        Dalam melakukan analisis prediksi kelayakan, sistem ini mengimplementasikan metode <em>Decision Tree</em> (Pohon Keputusan). Metode ini bekerja dengan cara memecah data akademik yang kompleks (seperti IPK, jumlah SKS, penghasilan orang tua, dan keaktifan organisasi) ke dalam bentuk aturan-aturan keputusan bercabang.
                    </p>
                    <ul class="text-secondary ps-3 mb-0" style="line-height: 1.8;">
                        <li><strong>Objektif:</strong> Mengurangi unsur subjektivitas dalam penilaian seleksi.</li>
                        <li><strong>Transparan:</strong> Alur keputusan dari variabel input hingga hasil rekomendasi dapat dipertanggungjawabkan.</li>
                        <li><strong>Cepat:</strong> Proses komputasi dilakukan secara instan begitu mahasiswa memasukkan data.</li>
                    </ul>
                </div>

                <!-- Card 3: Tujuan Pembuatan Sistem -->
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h4 class="fw-bold mb-3 text-primary"><i class="bi bi-bullseye me-2"></i> Tujuan & Manfaat</h4>
                    <p class="text-secondary mb-0" style="line-height: 1.8;">
                        Sistem ini dibangun untuk memberikan kemudahan bagi mahasiswa dalam mendapatkan estimasi status kelayakan beasiswa mereka secara mandiri, sekaligus menjadi alat bantu (*decision support system*) yang efektif bagi tim seleksi kampus dalam menyaring kandidat terbaik.
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection