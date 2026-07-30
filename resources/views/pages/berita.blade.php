@extends('layouts.app')

@section('title', 'Berita & Pengumuman - Sistem Informasi Penerimaan Beasiswa')

@section('content')
<div style="padding-top: 120px; padding-bottom: 60px; background: #f8fafc; min-height: 80vh;">
    <div class="container">
        <div class="row mb-5">
            <div class="col-lg-8">
                <h2 class="fw-bold mb-2">Berita & Pengumuman Beasiswa</h2>
                <p class="text-muted">Temukan informasi terbaru seputar jadwal pendaftaran, tips lolos seleksi, dan pengumuman penting lainnya.</p>
            </div>
        </div>

        <div class="row g-4">
            <!-- Item Berita 1 -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div style="height: 200px; background: #e2e8f0; overflow: hidden;">
                        <img src="{{ asset('images/berita1.png') }}" alt="Berita 1" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <span class="text-primary fw-semibold small mb-2"><i class="bi bi-calendar-event me-1"></i> 29 Juli 2026</span>
                        <h5 class="fw-bold text-dark mb-2">Pendaftaran Beasiswa Internal Kampus Semester Genap Resmi Dibuka</h5>
                        <p class="text-muted small mb-4">Simak alur persyaratan administrasi dan ketentuan IPK minimum untuk mengikuti seleksi beasiswa tahun ini.</p>
                        <a href="{{ route('berita.detail', 1) }}" class="btn btn-outline-primary mt-auto rounded-pill fw-semibold btn-sm">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>

            <!-- Item Berita 2 -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div style="height: 200px; background: #e2e8f0; overflow: hidden;">
                        <img src="{{ asset('images/berita2.png') }}" alt="Berita 2" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <span class="text-primary fw-semibold small mb-2"><i class="bi bi-calendar-event me-1"></i> 15 Juni 2026</span>
                        <h5 class="fw-bold text-dark mb-2">Tips & Trik Meningkatkan Peluang Lolos Menggunakan Sistem Prediksi</h5>
                        <p class="text-muted small mb-4">Pelajari bagaimana cara melengkapi data akademik dan aktivitas organisasi agar sistem memberikan rekomendasi terbaik.</p>
                        <a href="{{ route('berita.detail', 2) }}" class="btn btn-outline-primary mt-auto rounded-pill fw-semibold btn-sm">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>

            <!-- Item Berita 3 -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div style="height: 200px; background: #e2e8f0; overflow: hidden;">
                        <img src="{{ asset('images/berita3.png') }}" alt="Berita 3" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <div class="card-body p-4 d-flex flex-column">
                        <span class="text-primary fw-semibold small mb-2"><i class="bi bi-calendar-event me-1"></i> 01 Mei 2026</span>
                        <h5 class="fw-bold text-dark mb-2">Sosialisasi Algoritma Decision Tree dalam Seleksi Beasiswa Objektif</h5>
                        <p class="text-muted small mb-4">Kampus meluncurkan sistem berbasis web baru untuk mempercepat proses verifikasi data penerima beasiswa secara transparan.</p>
                        <a href="{{ route('berita.detail', 3) }}" class="btn btn-outline-primary mt-auto rounded-pill fw-semibold btn-sm">Baca Selengkapnya</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection