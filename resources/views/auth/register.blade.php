<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | SIPB</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #eef3ff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-wrapper {
            width: 1200px;
            max-width: 100%;
            background: white;
            border-radius: 30px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .12);
        }

        /* LEFT */
        .left-side {
            width: 55%;
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: white;
            padding: 70px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo {
            position: absolute;
            top: 40px;
            left: 50px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 24px;
            font-weight: 700;
        }

        .logo img {
            width: 55px;
            height: 55px;
            object-fit: contain;
        }

        .left-side h1 {
            font-size: 54px;
            font-weight: 800;
            line-height: 1.15;
            margin-bottom: 20px;
        }

        .left-side p {
            color: #dbeafe;
            line-height: 30px;
            font-size: 18px;
            margin-bottom: 40px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 18px;
            font-size: 18px;
        }

        .feature i {
            color: #4ade80;
            font-size: 22px;
        }

        .circle {
            position: absolute;
            width: 420px;
            height: 420px;
            background: rgba(255, 255, 255, .07);
            border-radius: 50%;
            bottom: -150px;
            right: -130px;
        }

        .circle2 {
            position: absolute;
            width: 220px;
            height: 220px;
            background: rgba(255, 255, 255, .05);
            top: -70px;
            right: -70px;
            border-radius: 50%;
        }

        /* RIGHT */
        .right-side {
            width: 45%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 50px;
        }

        .form-box {
            width: 100%;
            max-width: 390px;
        }

        .back-home {
            display: inline-block;
            margin-bottom: 20px;
            text-decoration: none;
            font-weight: 600;
            color: #2563eb;
            font-size: 14px;
        }

        .form-box h2 {
            font-size: 36px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .form-box p {
            color: #6b7280;
            margin-bottom: 25px;
        }

        .form-control,
        .form-select {
            height: 55px;
            border-radius: 14px;
            margin-bottom: 15px;
        }

        .form-control:focus,
        .form-select:focus {
            box-shadow: none;
            border-color: #2563eb;
        }

        .register-btn {
            width: 100%;
            height: 58px;
            border: none;
            border-radius: 15px;
            background: #2563eb;
            color: white;
            font-weight: 600;
            font-size: 17px;
            transition: .3s;
        }

        .register-btn:hover {
            background: #1d4ed8;
        }

        .bottom {
            margin-top: 25px;
            text-align: center;
        }

        .bottom a {
            text-decoration: none;
            font-weight: 600;
            color: #2563eb;
        }

        /* Responsive: tablet */
        @media (max-width: 992px) {
            .register-wrapper {
                width: 100%;
                flex-direction: column;
            }

            .left-side,
            .right-side {
                width: 100%;
            }

            .left-side {
                padding: 50px 40px 40px;
            }

            .right-side {
                padding: 40px;
            }

            .left-side h1 {
                font-size: 40px;
            }

            .left-side p {
                margin-bottom: 30px;
            }

            .circle {
                width: 280px;
                height: 280px;
                bottom: -130px;
                right: -100px;
            }

            .circle2 {
                width: 170px;
                height: 170px;
                top: -50px;
                right: -60px;
            }
        }

        /* Responsive: mobile */
        @media (max-width: 576px) {
            body {
                padding: 0;
                align-items: flex-start;
            }

            .register-wrapper {
                border-radius: 0;
                min-height: 100vh;
                box-shadow: none;
            }

            .left-side {
                padding: 90px 25px 30px;
            }

            .logo {
                top: 20px;
                left: 20px;
                font-size: 20px;
            }

            .logo img {
                width: 42px;
                height: 42px;
            }

            .left-side h1 {
                font-size: 28px;
                line-height: 1.2;
            }

            .left-side p {
                font-size: 15px;
                line-height: 26px;
                margin-bottom: 20px;
            }

            .feature {
                font-size: 15px;
                gap: 10px;
                margin-bottom: 12px;
            }

            .feature i {
                font-size: 18px;
            }

            .circle {
                width: 190px;
                height: 190px;
                bottom: -90px;
                right: -70px;
            }

            .circle2 {
                display: none;
            }

            .right-side {
                padding: 30px 22px 40px;
            }

            .back-home {
                margin-bottom: 16px;
            }

            .form-box h2 {
                font-size: 28px;
            }

            .form-box p {
                margin-bottom: 20px;
            }

            .form-control,
            .form-select,
            .register-btn {
                height: 50px;
            }
        }
    </style>
</head>
<body>

    <div class="register-wrapper">

        <!-- LEFT -->
        <div class="left-side">
            <div class="circle"></div>
            <div class="circle2"></div>

            <div class="logo">
                <img src="{{ asset('images/logo beasiswa.png') }}" alt="Logo SIPB">
                <span>SIPB</span>
            </div>

            <h1>Mulai Perjalanan Beasiswamu</h1>

            <p>
                Daftarkan akun untuk mendapatkan rekomendasi
                beasiswa yang sesuai dengan kondisi akademik
                dan profil mahasiswa Anda.
            </p>

            <div class="feature">
                <i class="bi bi-check-circle-fill"></i> Rekomendasi Beasiswa
            </div>
            <div class="feature">
                <i class="bi bi-check-circle-fill"></i> Decision Tree
            </div>
            <div class="feature">
                <i class="bi bi-check-circle-fill"></i> Analisis Cepat
            </div>
        </div>

        <!-- RIGHT -->
        <div class="right-side">
            <div class="form-box">
                <a href="/" class="back-home">
                    <i class="bi bi-arrow-left"></i> Home
                </a>

                <h2>Create Account</h2>
                <p>Lengkapi data berikut untuk membuat akun.</p>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST">
                    @csrf

                    <input
                        type="text"
                        name="name"
                        class="form-control @error('name') is-invalid @enderror"
                        placeholder="Nama Lengkap"
                        value="{{ old('name') }}">
                    @error('name')
                        <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror

                    <input
                        type="email"
                        name="email"
                        class="form-control @error('email') is-invalid @enderror"
                        placeholder="Email"
                        value="{{ old('email') }}">
                    @error('email')
                        <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror

                    <input
                        type="text"
                        name="nim"
                        class="form-control @error('nim') is-invalid @enderror"
                        placeholder="NIM"
                        value="{{ old('nim') }}">
                    @error('nim')
                        <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror

                    <select
                        name="prodi"
                        class="form-select @error('prodi') is-invalid @enderror">
                        <option value="">Pilih Program Studi</option>
                        <option value="Teknik Informatika" {{ old('prodi') == 'Teknik Informatika' ? 'selected' : '' }}>
                            Teknik Informatika
                        </option>
                        <option value="Sistem Informasi" {{ old('prodi') == 'Sistem Informasi' ? 'selected' : '' }}>
                            Sistem Informasi
                        </option>
                        <option value="Teknologi Informasi" {{ old('prodi') == 'Teknologi Informasi' ? 'selected' : '' }}>
                            Teknologi Informasi
                        </option>
                    </select>
                    @error('prodi')
                        <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror

                    <input
                        type="password"
                        name="password"
                        class="form-control @error('password') is-invalid @enderror"
                        placeholder="Password">
                    @error('password')
                        <div class="text-danger mb-2">{{ $message }}</div>
                    @enderror

                    <input
                        type="password"
                        name="password_confirmation"
                        class="form-control"
                        placeholder="Konfirmasi Password">

                    <button type="submit" class="register-btn">Create Account</button>
                </form>

                <div class="bottom">
                    Sudah memiliki akun? <a href="{{ route('login') }}">Login</a>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
