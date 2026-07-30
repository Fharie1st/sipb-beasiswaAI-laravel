@extends('layouts.app')

@section('content')
<div class="container mt-5 pt-4 pb-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            {{-- Notifikasi Sukses --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show shadow-sm mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Notifikasi Error Validasi Umum --}}
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show shadow-sm mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Terdapat kesalahan pada inputan Anda. Silakan periksa kembali.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="row g-4">

                    {{-- Kolom Kiri: Foto Profil & Info Singkat --}}
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm text-center p-4 h-100">
                            <div class="card-body d-flex flex-column align-items-center justify-content-center">

                                {{-- Wrapper foto: klik di sini untuk ganti foto --}}
                                <div class="position-relative mb-3" style="cursor: pointer;" onclick="document.getElementById('avatar').click();">

                                    @if($user->avatar)
                                        <img id="avatarPreview" src="{{ $user->avatar }}" alt="Avatar"
                                             class="rounded-circle shadow"
                                             style="width: 110px; height: 110px; object-fit: cover;">
                                    @else
                                        <div id="avatarPreview"
                                             class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center shadow"
                                             style="width: 110px; height: 110px; font-size: 38px; font-weight: bold;">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    {{-- Ikon kamera kecil di pojok foto --}}
                                    <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow"
                                         style="width: 32px; height: 32px;">
                                        <i class="bi bi-camera-fill" style="font-size: 14px;"></i>
                                    </div>
                                </div>

                                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                                <p class="text-muted small mb-3">{{ $user->email }}</p>

                                {{-- Input file asli disembunyikan --}}
                                <input type="file" class="d-none @error('avatar') is-invalid @enderror"
                                       id="avatar" name="avatar" accept=".jpg,.jpeg,.png" onchange="previewAvatar(event)">

                                {{-- Tombol custom pengganti "Choose File" --}}
                                <button type="button" class="btn btn-outline-primary btn-sm w-100"
                                        onclick="document.getElementById('avatar').click();">
                                    <i class="bi bi-upload me-1"></i> Ganti Foto Profil
                                </button>

                                <div class="form-text text-muted mt-2" style="font-size: 11px;">
                                    Format: JPG, JPEG, PNG (Maks. 2MB)
                                </div>

                                @error('avatar')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Form Data Utama & Password --}}
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm p-4 h-100">
                            <div class="card-body">
                                <h4 class="fw-bold text-dark mb-4 pb-2 border-bottom">Pengaturan Profil</h4>

                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="email" class="form-label fw-semibold">Alamat Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <h5 class="fw-bold text-dark mb-3 pt-3 border-top">Ubah Kata Sandi</h5>

                                <div class="mb-3">
                                    <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror"
                                           id="current_password" name="current_password"
                                           placeholder="Kosongkan jika tidak ingin mengubah">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="new_password" class="form-label fw-semibold">Password Baru</label>
                                        <input type="password" class="form-control @error('new_password') is-invalid @enderror"
                                               id="new_password" name="new_password" placeholder="Minimal 8 karakter">
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
                                        <input type="password" class="form-control"
                                               id="new_password_confirmation" name="new_password_confirmation"
                                               placeholder="Ulangi password baru">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary px-4">Kembali</a>
                                    <button type="submit" class="btn btn-primary px-4 shadow-sm">Simpan Perubahan</button>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewAvatar(event) {
        const file = event.target.files[0];
        if (!file) return;

        const preview = document.getElementById('avatarPreview');
        const reader = new FileReader();

        reader.onload = function (e) {
            // Ganti isi wrapper preview jadi <img> (menutupi div inisial kalau sebelumnya belum ada foto)
            const newImg = document.createElement('img');
            newImg.id = 'avatarPreview';
            newImg.src = e.target.result;
            newImg.alt = 'Avatar';
            newImg.className = 'rounded-circle shadow';
            newImg.style.width = '110px';
            newImg.style.height = '110px';
            newImg.style.objectFit = 'cover';

            preview.replaceWith(newImg);
        };

        reader.readAsDataURL(file);
    }
</script>
@endsection
