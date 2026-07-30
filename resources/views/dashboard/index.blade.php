@extends('layouts.app')

@section('title', 'Sistem Informasi Penerimaan Beasiswa')

@section('content')

@if(session('success'))
<div class="container">
    <div class="alert alert-success alert-dismissible fade show shadow-sm">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
@endif

<section class="hero">
    <div class="container">
        <div class="text-center">

            <div class="badge-ai">
                🎓 Sistem Rekomendasi Penerimaan Beasiswa
            </div>

            <h1>
                Temukan Peluang
                <br>
                <span>Beasiswa Sesuai Potensimu.</span>
            </h1>

            <p>
                Sistem membantu mahasiswa menemukan rekomendasi beasiswa berdasarkan data akademik
                menggunakan <strong>algoritma Decision Tree</strong>, sehingga proses seleksi menjadi
                lebih cepat, objektif, dan transparan.
            </p>

            <div class="d-flex justify-content-center gap-3 flex-wrap">
                <a href="{{ auth()->check() ? route('prediction.index') : route('login') }}" class="btn btn-primary btn-lg">
                    Mulai Konsultasi
                </a>
            </div>

        </div>
    </div>
</section>

<style>

    /* ===== Hero ===== */
    .hero{
        padding: 40px 0 30px;
    }

    .badge-ai{
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 999px;
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
        box-shadow: 0 6px 16px rgba(0,0,0,.04);
        margin-bottom: 24px;
    }

    .hero h1{
        font-size: 60px;
        font-weight: 800;
        line-height: 1.15;
        color: #111827;
        letter-spacing: -1px;
        margin: 0 0 22px;
    }

    .hero h1 span{
        color: #9CA3AF;
        font-weight: 800;
    }

    .hero > .container > .text-center > p{
        max-width: 680px;
        margin: 0 auto 32px;
        color: #6B7280;
        font-size: 16px;
        line-height: 1.75;
    }

    .hero .btn-primary{
        background: #2563EB;
        border: none;
        padding: 14px 32px;
        border-radius: 999px;
        font-weight: 600;
    }

    .hero .btn-primary:hover{
        background: #1D4ED8;
    }

    @media (max-width: 768px){
        .hero h1{
            font-size: 36px;
        }
    }

    /* ===== CTA section ===== */
    .cta-section{
        padding: 90px 0;
        border-top: 1px solid #ececec;
    }

    .section-title{
        font-size: 34px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 16px;
    }

    .section-subtitle{
        color: #6B7280;
        font-size: 16px;
        max-width: 640px;
        margin: 0 auto 32px;
        line-height: 1.75;
    }

    .btn-main{
        background: #111827;
        color: #fff;
        border: none;
        padding: 14px 34px;
        border-radius: 999px;
        font-weight: 600;
        display: inline-block;
        transition: .3s;
    }

    .btn-main:hover{
        background: #2563EB;
        color: #fff;
    }

    /* ===== Shared tokens ===== */
    .eyebrow {
        font-size: 13px;
        font-weight: 600;
        color: #6B7280;
        margin-bottom: 14px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .eyebrow .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #2563EB;
        display: inline-block;
    }

    /* ===== Section 1: showcase (teks + mock chat, statis) ===== */
    .showcase-section {
        padding: 90px 0;
        border-top: 1px solid #ececec;
        border-bottom: 1px solid #ececec;
    }
    .showcase-heading {
        font-size: 40px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 20px;
        line-height: 1.2;
    }
    .showcase-text {
        color: #6B7280;
        font-size: 16px;
        line-height: 1.75;
        margin-bottom: 28px;
    }
    .showcase-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .showcase-list li {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: #374151;
        font-size: 15px;
        margin-bottom: 14px;
    }
    .showcase-list li i {
        color: #2563EB;
        font-size: 16px;
        margin-top: 2px;
    }

    /* --- Mock chat card (dekorasi saja, tidak interaktif) --- */
    .mock-chat-card {
        background: #fff;
        border: 1px solid #ececec;
        border-radius: 20px;
        box-shadow: 0 20px 50px rgba(0,0,0,.06);
        padding: 26px;
        pointer-events: none;   /* memastikan tidak bisa diklik / diketik */
        user-select: none;
    }
    .mock-chat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
    }
    .mock-chat-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        color: #111827;
        font-size: 15px;
    }
    .mock-chat-title .icon-circle {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        background: #EEF4FF;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #2563EB;
        font-size: 14px;
    }
    .mock-badge {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        background: #F3F4F6;
        border-radius: 999px;
        padding: 5px 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mock-badge .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #22c55e;
        display: inline-block;
    }
    .mock-bot-msg {
        color: #374151;
        font-size: 14.5px;
        margin-bottom: 14px;
        line-height: 1.6;
    }
    .mock-user-msg {
        background: #111827;
        color: #fff;
        font-size: 14px;
        padding: 10px 16px;
        border-radius: 14px;
        display: inline-block;
        margin-bottom: 16px;
        float: right;
        clear: both;
    }
    .mock-chip-row {
        clear: both;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin: 6px 0 20px;
    }
    .mock-chip {
        font-size: 12.5px;
        color: #6B7280;
        background: #F9FAFB;
        border: 1px solid #ececec;
        border-radius: 999px;
        padding: 6px 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .mock-chip i {
        font-size: 12px;
        color: #2563EB;
    }
    .mock-input-bar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #F9FAFB;
        border: 1px solid #ececec;
        border-radius: 999px;
        padding: 8px 8px 8px 18px;
    }
    .mock-input-bar span {
        color: #9ca3af;
        font-size: 14px;
    }
    .mock-input-actions {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .mock-input-actions i {
        color: #9ca3af;
        font-size: 15px;
    }
    .mock-send-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #2563EB;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
    }

    /* ===== Section 2: how-it-works style numbered cards ===== */
    .howworks-section {
        padding: 90px 0;
        text-align: center;
    }
    .howworks-title {
        font-size: 38px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 16px;
    }
    .howworks-subtitle {
        color: #6B7280;
        font-size: 16px;
        max-width: 620px;
        margin: 0 auto 50px;
        line-height: 1.7;
    }
    .howworks-card {
        background: #fff;
        border: 1px solid #0f0e0e;
        border-radius: 18px;
        padding: 28px;
        text-align: left;
        height: 100%;
        transition: box-shadow .2s ease, transform .2s ease;
    }
    .howworks-card:hover {
        box-shadow: 0 16px 40px rgba(0,0,0,.06);
        transform: translateY(-2px);
    }
    .howworks-top {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 18px;
    }
    .howworks-icon {
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #EEF4FF;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        color: #2563EB;
    }
    .howworks-number {
        font-size: 26px;
        font-weight: 700;
        color: #E5E7EB;
    }
    .howworks-card h4 {
        font-size: 17px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 8px;
    }
    .howworks-card p {
        font-size: 14.5px;
        color: #6B7280;
        line-height: 1.7;
        margin: 0;
    }

</style>

<!-- ================= SHOWCASE: Apa itu SIPB sekarang (statis, dekorasi) ================= -->
<section class="showcase-section">
    <div class="container">
        <div class="row align-items-center g-5">

            <div class="col-lg-6">
                <div class="eyebrow"><span class="dot"></span> Apa itu SIPB sekarang</div>

                <h2 class="showcase-heading">Analisis instan, bukan formulir panjang.</h2>

                <p class="showcase-text">
                    SIPB menggantikan proses seleksi manual dengan analisis instan berbasis
                    data akademik. Cukup masukkan data kamu, dan sistem langsung membaca
                    IPK, SKS, kondisi keluarga, hingga keaktifan organisasi untuk
                    memberikan rekomendasi kelayakan beasiswa yang objektif.
                </p>

                <ul class="showcase-list">
                    <li><i class="bi bi-patch-check-fill"></i> Berdasarkan data akademik asli, bukan asumsi</li>
                    <li><i class="bi bi-patch-check-fill"></i> Hasil dan alasan penilaian disampaikan transparan</li>
                    <li><i class="bi bi-patch-check-fill"></i> Dari isi data sampai rekomendasi, dalam satu alur</li>
                </ul>
            </div>

            <div class="col-lg-6">
                <!-- Kotak ini hanya tampilan / dekorasi, tidak fungsional -->
                <div class="mock-chat-card">

                    <div class="mock-chat-header">
                        <div class="mock-chat-title">
                            <span class="icon-circle"><i class="bi bi-stars"></i></span>
                            SIPB Advisor
                        </div>
                        <div class="mock-badge"><span class="dot"></span> Analisis 87%</div>
                    </div>

                    <div class="mock-bot-msg">
                        Halo! Program studi kamu apa, dan berapa IPK saat ini?
                    </div>

                    <div class="mock-user-msg">S1 Sistem Komputer, IPK 3.55</div>

                    <div class="mock-bot-msg" style="clear:both;">
                        Oke, sudah berapa SKS yang lulus dan aktif organisasi kampus?
                    </div>

                    <div class="mock-chip-row">
                        <span class="mock-chip"><i class="bi bi-check-circle"></i> Prodi tersimpan</span>
                        <span class="mock-chip"><i class="bi bi-check-circle"></i> IPK tersimpan</span>
                        <span class="mock-chip"><i class="bi bi-check-circle"></i> SKS tersimpan</span>
                    </div>

                    <div class="mock-input-bar">
                        <span>Tulis jawaban Anda...</span>
                        <div class="mock-input-actions">
                            <i class="bi bi-mic"></i>
                            <span class="mock-send-btn"><i class="bi bi-arrow-up"></i></span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================= HOW IT WORKS style: Mengapa Memilih SIPB ================= -->
<section class="howworks-section">
    <div class="container">

        <div class="eyebrow justify-content-center"><span class="dot"></span> Mengapa SIPB</div>
        <h2 class="howworks-title">Dari data ke rekomendasi</h2>
        <p class="howworks-subtitle">
            Satu sistem yang membaca data akademikmu dan langsung memberi rekomendasi
            beasiswa yang cepat, otomatis, dan objektif.
        </p>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="howworks-card">
                    <div class="howworks-top">
                        <div class="howworks-icon"><i class="bi bi-cpu"></i></div>
                        <div class="howworks-number">01</div>
                    </div>
                    <h4>Decision Tree</h4>
                    <p>Menggunakan algoritma Decision Tree untuk memberikan rekomendasi beasiswa berdasarkan data mahasiswa.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="howworks-card">
                    <div class="howworks-top">
                        <div class="howworks-icon"><i class="bi bi-lightning-charge"></i></div>
                        <div class="howworks-number">02</div>
                    </div>
                    <h4>Rekomendasi Otomatis</h4>
                    <p>Hasil prediksi dapat diperoleh hanya dalam hitungan detik.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="howworks-card">
                    <div class="howworks-top">
                        <div class="howworks-icon"><i class="bi bi-shield-check"></i></div>
                        <div class="howworks-number">03</div>
                    </div>
                    <h4>Transparan</h4>
                    <p>Penilaian dilakukan berdasarkan data akademik mahasiswa sehingga lebih objektif.</p>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- ================= STATISTIK ================= -->
<section class="bg-light">
    <div class="container">
        <div class="row text-center">

            <div class="col-md-3">
                <h2 class="fw-bold text-primary">833+</h2>
                <p>Data Mahasiswa</p>
            </div>

            <div class="col-md-3">
                <h2 class="fw-bold text-primary">6</h2>
                <p>Faktor Penilaian</p>
            </div>

            <div class="col-md-3">
                <h2 class="fw-bold text-primary">Decision Tree</h2>
                <p>Algoritma Klasifikasi</p>
            </div>

            <div class="col-md-3">
                <h2 class="fw-bold text-primary">24/7</h2>
                <p>Akses Sistem</p>
            </div>

        </div>
    </div>
</section>

<!-- ================= CTA ================= -->
<section id="prediksi" class="cta-section">
    <div class="container text-center">

        <h2 class="section-title">Siap Menemukan Beasiswa yang Tepat?</h2>

        <p class="section-subtitle">
            Masukkan data akademikmu, lalu biarkan sistem menganalisis menggunakan
            <strong>algoritma Decision Tree</strong> untuk menentukan rekomendasi beasiswa yang paling sesuai.
        </p>

        <a href="{{ auth()->check() ? route('prediction.index') : route('login') }}" class="btn btn-main btn-lg">
            Mulai Prediksi Sekarang
        </a>

    </div>
</section>

@endsection