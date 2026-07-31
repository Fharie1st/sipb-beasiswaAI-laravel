@extends('layouts.app')

@section('title', 'Pengaturan Profil - SIPB')

@section('content')
<div style="max-width: 720px; margin: 0 auto 60px;">

    <h2 style="font-weight:700; margin-bottom: 6px;">Pengaturan Profil</h2>
    <p style="color:#6B7280; margin-bottom: 28px;">Kelola informasi akun dan foto profil kamu di sini.</p>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="alert alert-success" style="border-radius:12px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi error validasi --}}
    @if ($errors->any())
        <div class="alert alert-danger" style="border-radius:12px;">
            <ul style="margin-bottom:0; padding-left:18px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
          style="background:#fff; border:1px solid #ececec; border-radius:20px; padding:28px;">
        @csrf
        @method('PATCH')

        {{-- Avatar --}}
        <div style="display:flex; align-items:center; gap:20px; margin-bottom:28px;">
            @if($user->avatar)
                <img src="{{ asset($user->avatar) }}" alt="Avatar"
                     style="width:84px; height:84px; border-radius:50%; object-fit:cover;">
            @else
                <div style="width:84px; height:84px; border-radius:50%; background:#2563EB; color:white;
                            display:flex; align-items:center; justify-content:center; font-weight:700; font-size:32px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif

            <div>
                <label for="avatar" class="form-label fw-semibold" style="margin-bottom:6px; display:block;">
                    Foto Profil
                </label>
                <input type="file" name="avatar" id="avatar" class="form-control" accept="image/jpeg,image/jpg,image/png">
                <small style="color:#9CA3AF;">Format JPG/PNG, maksimal 2MB.</small>
            </div>
        </div>

        {{-- Nama --}}
        <div class="mb-3">
            <label for="name" class="form-label fw-semibold">Nama Lengkap</label>
            <input type="text" name="name" id="name" class="form-control"
                   value="{{ old('name', $user->name) }}" required>
        </div>

        {{-- Email --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">Email</label>
            <input type="email" name="email" id="email" class="form-control"
                   value="{{ old('email', $user->email) }}" required>
        </div>

        <hr style="margin: 28px 0;">

        <h5 style="font-weight:600; margin-bottom: 16px;">Ubah Password</h5>
        <p style="color:#6B7280; font-size: 14px; margin-bottom: 18px;">
            Kosongkan bagian ini jika kamu tidak ingin mengubah password.
        </p>

        {{-- Password saat ini --}}
        <div class="mb-3">
            <label for="current_password" class="form-label fw-semibold">Password Saat Ini</label>
            <input type="password" name="current_password" id="current_password" class="form-control">
        </div>

        {{-- Password baru --}}
        <div class="mb-3">
            <label for="new_password" class="form-label fw-semibold">Password Baru</label>
            <input type="password" name="new_password" id="new_password" class="form-control">
        </div>

        {{-- Konfirmasi password baru --}}
        <div class="mb-4">
            <label for="new_password_confirmation" class="form-label fw-semibold">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control">
        </div>

        <button type="submit" class="start-btn" style="border:none; cursor:pointer;">
            Simpan Perubahan
        </button>
    </form>
</div>
@endsection
