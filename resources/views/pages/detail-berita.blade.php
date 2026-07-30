@extends('layouts.app')

@section('title', 'Detail Berita - Sistem Informasi Penerimaan Beasiswa')

@section('content')
@php
    // Daftar data berita berdasarkan ID
    $beritaList = [
        1 => [
            'tanggal' => '29 Juli 2026',
            'judul' => 'Pendaftaran Beasiswa Internal Kampus Semester Genap Resmi Dibuka',
            'gambar' => 'berita1.png',
            'ringkasan' => 'Simak alur persyaratan administrasi dan ketentuan IPK minimum untuk mengikuti seleksi beasiswa tahun ini.',
            'konten' => '
                <p class="mb-3">
                    Pihak kampus secara resmi mengumumkan pembukaan pendaftaran beasiswa internal untuk semester genap. Program ini ditujukan bagi mahasiswa aktif yang memenuhi kriteria akademik maupun non-akademik yang telah ditentukan oleh pihak rektorat guna mendukung kelancaran studi mahasiswa.
                </p>

                <h5 class="fw-bold text-dark mt-4 mb-3">Syarat & Ketentuan Pendaftaran:</h5>
                <ul class="mb-4 ps-3">
                    <li class="mb-2">Mahasiswa aktif minimal berada di semester 3 dan maksimal semester 7.</li>
                    <li class="mb-2">Memiliki IPK minimum 3.00 yang dibuktikan dengan Transkrip Nilai terbaru.</li>
                    <li class="mb-2">Aktif mengikuti kegiatan organisasi atau ekstrakurikuler di lingkungan kampus.</li>
                    <li>Tidak sedang menerima beasiswa dari sumber atau instansi lain.</li>
                </ul>

                <h5 class="fw-bold text-dark mt-4 mb-3">Alur Seleksi Menggunakan Sistem SIPB:</h5>
                <p class="mb-3">
                    Tahun ini, proses seleksi menggunakan pendekatan sistem berbasis web dengan algoritma <em>Decision Tree</em> untuk memastikan penilaian yang objektif dan transparan.
                </p>
                <p class="mb-4">
                    Bagi mahasiswa yang ingin mendaftar, silakan manfaatkan fitur <a href="' . route('prediction.index') . '" class="text-decoration-none fw-semibold text-primary">Mulai Prediksi</a> di sistem SIPB ini terlebih dahulu untuk mengecek tingkat kelayakan beasiswa sebelum mengumpulkan berkas fisik ke bagian kemahasiswaan.
                </p>

                <div class="alert alert-info border-0 rounded-4 p-4 mt-4 bg-light text-secondary">
                    <i class="bi bi-info-circle-fill text-primary me-2"></i> 
                    <strong>Catatan Penting:</strong> Batas akhir pengisian formulir dan unggah berkas secara online adalah tanggal <strong>15 Agustus 2026</strong>. Pastikan seluruh dokumen discan dengan jelas.
                </div>
            '
        ],
        2 => [
            'tanggal' => '15 Juni 2026',
            'judul' => 'Tips & Trik Meningkatkan Peluang Lolos Menggunakan Sistem Prediksi',
            'gambar' => 'berita2.png', // Sesuaikan nama file gambar jika berbeda (misal berita2.jpg)
            'ringkasan' => 'Pelajari bagaimana cara melengkapi data akademik dan aktivitas organisasi agar sistem memberikan rekomendasi terbaik.',
            'konten' => '
                <p class="mb-3">
                    Dalam proses seleksi beasiswa menggunakan sistem berbasis web ini, kelengkapan data akademik seperti IPK, nilai transkrip, hingga keaktifan organisasi sangat memengaruhi hasil rekomendasi dari sistem.
                </p>
                <h5 class="fw-bold text-dark mt-4 mb-3">Langkah Optimalisasi Data:</h5>
                <ul class="mb-4 ps-3">
                    <li class="mb-2">Pastikan data transkrip nilai yang diinput sudah valid dan sesuai dengan portal akademik.</li>
                    <li class="mb-2">Cantumkan riwayat keaktifan organisasi atau sertifikat pendukung yang relevan.</li>
                    <li>Gunakan fitur prediksi secara berkala untuk memantau estimasi kelayakan status beasiswa kamu.</li>
                </ul>
                <p class="mb-4">
                    Ingin tahu seberapa besar peluangmu? Coba langsung analisis datamu melalui menu <a href="' . route('prediction.index') . '" class="text-decoration-none fw-semibold text-primary">Mulai Prediksi</a>.
                </p>
            '
        ],
        3 => [
            'tanggal' => '01 Mei 2026',
            'judul' => 'Sosialisasi Algoritma Decision Tree dalam Seleksi Beasiswa Objektif',
            'gambar' => 'berita3.png', // Sesuaikan nama file gambar jika berbeda (misal berita3.jpg)
            'ringkasan' => 'Kampus meluncurkan sistem berbasis web baru untuk mempercepat proses verifikasi data penerima beasiswa secara transparan.',
            'konten' => '
                <p class="mb-3">
                    Penerapan metode <em>Decision Tree</em> pada sistem penerimaan beasiswa ini dirancang khusus agar proses seleksi berjalan secara objektif, transparan, dan adil bagi seluruh pendaftar mahasiswa di lingkungan kampus.
                </p>
                <h5 class="fw-bold text-dark mt-4 mb-3">Keunggulan Sistem Baru:</h5>
                <p class="mb-3">
                    Sistem mampu memproses berbagai kriteria penilaian secara otomatis dan cepat, meminimalkan potensi kesalahan manual, serta memberikan hasil rekomendasi yang akurat berdasarkan bobot nilai yang telah ditentukan.
                </p>
                <div class="alert alert-success border-0 rounded-4 p-4 mt-4 bg-light text-secondary">
                    <i class="bi bi-check-circle-fill text-success me-2"></i> 
                    <strong>Informasi:</strong> Sosialisasi lanjutan mengenai penggunaan sistem ini akan disiarkan secara daring melalui kanal resmi kampus.
                </div>
            '
        ]
    ];

    // Ambil data berdasarkan $id yang dikirim dari route, jika tidak ada fallback ke ID 1
    $current = $beritaList[$id ?? 1];
@endphp

<div style="padding-top: 120px; padding-bottom: 60px; background: #f8fafc; min-height: 80vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Tombol Kembali ke Halaman Berita -->
                <a href="{{ route('berita') }}" class="btn btn-outline-secondary mb-4 rounded-pill px-3 btn-sm">
                    <i class="bi bi-arrow-left me-1"></i> Kembali ke Berita
                </a>

                <!-- Kategori & Tanggal -->
                <div class="mb-3">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-semibold">
                        <i class="bi bi-calendar-event me-1"></i> {{ $current['tanggal'] }}
                    </span>
                </div>

                <!-- Judul Berita -->
                <h1 class="fw-bold text-dark mb-4" style="line-height: 1.3;">
                    {{ $current['judul'] }}
                </h1>

                <!-- Foto Utama Berita -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <img src="{{ asset('images/' . $current['gambar']) }}" alt="Foto Berita" style="width: 100%; max-height: 400px; object-fit: cover;">
                </div>

                <!-- Konten / Isi Berita -->
                <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 bg-white">
                    <div class="content-text" style="line-height: 1.8; color: #374151;">
                        <p class="lead fw-semibold mb-4 text-dark">
                            {{ $current['ringkasan'] }}
                        </p>
                        
                        {!! $current['konten'] !!}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection