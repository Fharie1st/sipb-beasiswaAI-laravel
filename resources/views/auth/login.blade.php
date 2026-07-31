<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | SIPB</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Google Font -->
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

        /* Card */
        .login-wrapper {
            width: 1200px;
            max-width: 100%;
            height: 720px;
            background: white;
            border-radius: 30px;
            overflow: hidden;
            display: flex;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .12);
        }

        /* Left */
        .left-side {
            width: 55%;
            background: linear-gradient(135deg, #0f172a, #1e3a8a);
            color: white;
            padding: 70px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
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
            font-size: 55px;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.1;
        }

        .left-side p {
            font-size: 18px;
            line-height: 32px;
            color: #dbeafe;
            margin-bottom: 50px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
            font-size: 18px;
        }

        .feature i {
            font-size: 22px;
            color: #4ade80;
        }

        .circle {
            position: absolute;
            width: 450px;
            height: 450px;
            background: rgba(255, 255, 255, .08);
            border-radius: 50%;
            bottom: -170px;
            right: -130px;
        }

        .circle2 {
            position: absolute;
            width: 250px;
            height: 250px;
            background: rgba(255, 255, 255, .05);
            border-radius: 50%;
            top: -70px;
            right: -80px;
        }

        /* Right */
        .right-side {
            width: 45%;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 60px;
        }

        .form-box {
            width: 100%;
            max-width: 380px;
            position: relative;
        }

        .form-box h2 {
            font-size: 38px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .form-box p {
            color: #6b7280;
            margin-bottom: 35px;
        }

        .form-control {
            height: 58px;
            border-radius: 15px;
            border: 1px solid #d1d5db;
            padding-left: 18px;
            margin-bottom: 20px;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #2563eb;
        }

        .password {
            position: relative;
        }

        .password .form-control {
            padding-right: 45px;
        }

        .password i.toggle-eye {
            position: absolute;
            right: 20px;
            top: 18px;
            cursor: pointer;
            color: #6b7280;
        }

        .form-options {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
        }

        .form-options .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-options a {
            text-decoration: none;
            color: #2563eb;
            font-weight: 500;
            font-size: 14px;
        }

        .login-btn {
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

        .login-btn:hover {
            background: #1d4ed8;
        }

        .bottom {
            margin-top: 30px;
            text-align: center;
        }

        .bottom a {
            text-decoration: none;
            font-weight: 600;
            color: #2563eb;
        }

        .back-home {
            display: inline-block;
            margin-bottom: 25px;
            text-decoration: none;
            font-weight: 600;
            color: #2563eb;
            font-size: 14px;
        }

        /* Responsive: tablet */
        @media (max-width: 992px) {
            .login-wrapper {
                width: 100%;
                height: auto;
                flex-direction: column;
            }

            .left-side {
                width: 100%;
                padding: 50px 40px 40px;
            }

            .right-side {
                width: 100%;
                padding: 40px;
            }

            .left-side h1 {
                font-size: 38px;
            }

            .left-side p {
                margin-bottom: 30px;
            }

            .circle {
                width: 300px;
                height: 300px;
                bottom: -140px;
                right: -100px;
            }

            .circle2 {
                width: 180px;
                height: 180px;
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

            .login-wrapper {
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

            .back-home {
                margin-bottom: 18px;
            }

            .left-side h1 {
                font-size: 28px;
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
                width: 200px;
                height: 200px;
                bottom: -100px;
                right: -70px;
            }

            .circle2 {
                display: none;
            }

            .right-side {
                padding: 30px 22px 40px;
            }

            .form-box h2 {
                font-size: 30px;
            }

            .form-box p {
                margin-bottom: 25px;
            }

            .form-control,
            .login-btn {
                height: 52px;
            }

            .form-options {
                flex-wrap: wrap;
                gap: 10px;
            }
        }
    </style>
</head>
<body>

    <div class="login-wrapper">

        <!-- LEFT -->
        <div class="left-side">
            <div class="circle"></div>
            <div class="circle2"></div>

            <div class="logo">
                <img src="{{ asset('images/logo beasiswa.png') }}" alt="Logo SIPB">
                <span>SIPB</span>
            </div>

            <h1>Selamat Datang Kembali</h1>
            <p>
                Masuk ke Sistem Informasi Penerimaan Beasiswa untuk memperoleh rekomendasi
                beasiswa sesuai profil akademik Anda menggunakan metode Decision Tree.
            </p>

            <div class="feature">
                <i class="bi bi-check-circle-fill"></i> Prediksi Cepat
            </div>
            <div class="feature">
                <i class="bi bi-check-circle-fill"></i> Rekomendasi Beasiswa
            </div>
            <div class="feature">
                <i class="bi bi-check-circle-fill"></i> Analisis Decision Tree
            </div>
        </div>

        <!-- RIGHT -->
        <div class="right-side">
            <div class="form-box">
                <a href="/" class="back-home">
                    <i class="bi bi-arrow-left"></i> Home
                </a>

                <h2>Login</h2>
                <p>Masuk ke akun SIPB Anda.</p>

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form action="{{ route('login.store') }}" method="POST">
                    @csrf

                    <input
                        type="email"
                        name="email"
                        class="form-control"
                        placeholder="Email"
                        value="{{ old('email') }}"
                        required
                    >

                    <div class="password">
                        <input
                            type="password"
                            name="password"
                            id="password"
                            class="form-control"
                            placeholder="Password"
                            required
                        >
                        <i class="bi bi-eye toggle-eye" id="togglePassword"></i>
                    </div>

                    <div class="form-options">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <a href="#">Lupa Password?</a>
                    </div>

                    <button type="submit" class="login-btn">Masuk</button>
                </form>

                <div class="bottom">
                    Belum memiliki akun? <a href="{{ route('register') }}">Create Account</a>
                </div>
            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });
    </script>
</body>
</html>
