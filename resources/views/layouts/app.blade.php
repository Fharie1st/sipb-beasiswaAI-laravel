<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Sistem Informasi Penerimaan Beasiswa')</title>

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css','resources/js/app.js'])

    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins',sans-serif;
        }

        html{
            scroll-behavior:smooth;
        }

        body{
            background:#ffffff;
            color:#111827;
            overflow-x:hidden;
            padding-top: 95px;
        }

        a{
            text-decoration:none;
        }

        .navbar-custom{
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: rgba(255,255,255,.95);
            backdrop-filter: blur(18px);
            border-bottom: 1px solid #ececec;
            z-index: 9999;
        }

        .nav-wrapper{
            min-height: 82px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .brand{
            display: flex;
            align-items: center;
            gap: 12px;
            color: #111827;
            font-size: 28px;
            font-weight: 700;
        }

        .logo-box{
            width:48px;
            height:48px;
            border-radius:14px;
            background:#EEF4FF;
            display:flex;
            justify-content:center;
            align-items:center;
            color:#2563EB;
            font-weight:700;
            flex-shrink:0;
            overflow:hidden;
        }

        .logo-box img{
            width:100%;
            height:100%;
            object-fit:cover;
        }

        /* Toggler hamburger untuk mobile */
        .navbar-toggler{
            border:none;
            background:transparent;
            font-size:26px;
            color:#111827;
            padding:4px 8px;
        }

        .navbar-toggler:focus{
            box-shadow:none;
        }

        .menu{
            display:flex;
            gap:28px;
            list-style:none;
            margin:0;
            padding:0;
        }

        .menu a{
            color:#6B7280;
            font-weight:500;
            transition:.3s;
        }

        .menu a:hover{
            color:#2563EB;
        }

        .action{
            display:flex;
            align-items:center;
            gap:18px;
        }

        .login-btn{
            color:#111827;
            font-weight:600;
        }

        .start-btn{
            background:#111827;
            color:white;
            padding:12px 24px;
            border-radius:50px;
            font-weight:600;
            transition:.3s;
            white-space:nowrap;
        }

        .start-btn:hover{
            background:#2563EB;
            color:white;
        }

        footer{
            background:#111827;
            color:white;
            padding:35px 0;
        }

        #chatButton{
            position:fixed;
            right:30px;
            bottom:30px;
            z-index:99999;
        }

        #openChat{
            width:68px;
            height:68px;
            border:none;
            border-radius:50%;
            background:#2563EB;
            color:white;
            font-size:28px;
            cursor:pointer;
            box-shadow:0 12px 35px rgba(37,99,235,.35);
            transition:.35s;
            animation:floatButton 2.5s ease-in-out infinite;
        }

        #openChat:hover{
            transform:scale(1.08);
            background:#1D4ED8;
        }

        @keyframes floatButton{
            0%{transform:translateY(0);}
            50%{transform:translateY(-8px);}
            100%{transform:translateY(0);}
        }

        /* ================= RESPONSIVE (TABLET & MOBILE) ================= */
        @media (max-width: 991.98px){
            body{
                padding-top: 78px;
            }

            .nav-wrapper{
                min-height: 68px;
            }

            .brand{
                font-size: 22px;
            }

            .logo-box{
                width:40px;
                height:40px;
                border-radius:12px;
            }

            /* Menu jadi dropdown penuh di bawah navbar saat dibuka */
            .navbar-collapse{
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: #ffffff;
                border-bottom: 1px solid #ececec;
                box-shadow: 0 12px 25px rgba(0,0,0,.06);
                padding: 16px 20px 20px;
            }

            .menu{
                flex-direction: column;
                gap: 14px;
                width: 100%;
            }

            .menu a{
                display:block;
                padding:8px 0;
                border-bottom:1px solid #f3f4f6;
            }

            .action{
                flex-direction: column;
                align-items: stretch;
                width: 100%;
                gap: 10px;
                margin-top: 14px;
            }

            .action .login-btn{
                text-align:center;
                padding:10px 0;
            }

            .action .start-btn{
                text-align:center;
            }
        }

        @media (max-width: 575.98px){
            .brand span{
                font-size: 18px;
            }

            #chatButton{
                right: 16px;
                bottom: 16px;
            }

            #openChat{
                width: 56px;
                height: 56px;
                font-size: 22px;
            }

            footer{
                padding: 24px 0;
                font-size: 14px;
            }
        }
    </style>
</head>

<body>

<!-- ================= NAVBAR ================= -->
<nav class="navbar-custom">
    <div class="container nav-wrapper">
        <a href="/" class="brand">
            <div class="logo-box">
                <img src="{{ asset('images/logo beasiswa.png') }}" alt="Logo SIPB">
            </div>
            <span>SIPB</span>
        </a>

        {{-- Tombol hamburger, hanya muncul di layar kecil (lg ke bawah) --}}
        <button class="navbar-toggler d-lg-none" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarMain"
                aria-controls="navbarMain" aria-expanded="false" aria-label="Toggle navigation">
            <i class="bi bi-list"></i>
        </button>

        {{-- Wrapper collapse: di layar besar selalu tampil (flex), di layar kecil di-toggle --}}
        <div class="collapse navbar-collapse d-lg-flex flex-lg-row justify-content-lg-between align-items-lg-center flex-grow-1"
             id="navbarMain">

            <ul class="menu ms-lg-4">
                <li>
                    <a href="{{ route('dashboard') }}">Beranda</a>
                </li>
                <li>
                    <a href="{{ route('dashboard') }}#prediksi">Mulai Prediksi</a>
                </li>
                <li>
                    <a href="{{ route('berita') }}">Berita</a>
                </li>
                <li>
                    <a href="{{ route('informasi') }}">Informasi</a>
                </li>
                <li>
                    <a href="{{ route('tentang') }}">Tentang</a>
                </li>
            </ul>

            <div class="action">
                @guest
                    <a href="{{ route('login') }}" class="login-btn">Login</a>
                    <a href="{{ route('register') }}" class="start-btn">Mulai</a>
                @endguest

                @auth
                    <div class="dropdown">
                        <a class="d-flex align-items-center gap-2 text-dark fw-semibold text-decoration-none dropdown-toggle"
                           href="#"
                           data-bs-toggle="dropdown">
                             @if(Auth::user()->avatar)
                                 <img src="{{ Auth::user()->avatar }}" alt="Avatar" style="width:42px; height:42px; border-radius:50%; object-fit:cover;">
                             @else
                                 <div style="width:42px; height:42px; border-radius:50%; background:#2563EB; color:white; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:18px;">
                                     {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                                 </div>
                             @endif
                             {{ Auth::user()->name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow">
                            <li>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="bi bi-house me-2"></i> Dashboard
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('prediction.index') }}">
                                    <i class="bi bi-cpu me-2"></i> Prediksi
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-person-gear me-2"></i> Pengaturan Profil
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- Isi halaman --}}
<div class="container" style="margin-top: 20px;">
    @yield('content')
</div>

<footer>
    <div class="container text-center">
        © {{ date('Y') }} Sistem Informasi Penerimaan Beasiswa Menggunakan Metode Decision Tree
    </div>
</footer>

{{-- Bootstrap JS Bundle --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
