<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login | SIPB</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icon -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
          rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Poppins',sans-serif;
}

body{

background:#eef3ff;

height:100vh;

display:flex;

justify-content:center;

align-items:center;

overflow:hidden;

}

/* Card */

.login-wrapper{

width:1200px;

height:720px;

background:white;

border-radius:30px;

overflow:hidden;

display:flex;

box-shadow:0 20px 60px rgba(0,0,0,.12);

}

/* Left */

.left-side{

width:55%;

background:linear-gradient(135deg,#0f172a,#1e3a8a);

color:white;

padding:70px;

display:flex;

flex-direction:column;

justify-content:center;

position:relative;

}

.logo{

position:absolute;

top:40px;

left:50px;

display:flex;

align-items:center;

gap:12px;

font-size:24px;

font-weight:700;

}

.logo img{

width:55px;

height:55px;

object-fit:contain;

}

.left-side h1{

font-size:55px;

font-weight:800;

margin-bottom:20px;

line-height:1.1;

}

.left-side p{

font-size:18px;

line-height:32px;

color:#dbeafe;

margin-bottom:50px;

}

.feature{

display:flex;

align-items:center;

gap:15px;

margin-bottom:20px;

font-size:18px;

}

.feature i{

font-size:22px;

color:#4ade80;

}

.circle{

position:absolute;

width:450px;

height:450px;

background:rgba(255,255,255,.08);

border-radius:50%;

bottom:-170px;

right:-130px;

}

.circle2{

position:absolute;

width:250px;

height:250px;

background:rgba(255,255,255,.05);

border-radius:50%;

top:-70px;

right:-80px;

}

/* Right */

.right-side{

width:45%;

display:flex;

justify-content:center;

align-items:center;

padding:60px;

}

.form-box{

width:100%;

max-width:380px;

}

.form-box h2{

font-size:38px;

font-weight:700;

margin-bottom:10px;

}

.form-box p{

color:#6b7280;

margin-bottom:35px;

}

.form-control{

height:58px;

border-radius:15px;

border:1px solid #d1d5db;

padding-left:18px;

margin-bottom:20px;

}

.form-control:focus{

box-shadow:none;

border-color:#2563eb;

}

.password{

position:relative;

}

.password i{

position:absolute;

right:20px;

top:18px;

cursor:pointer;

color:#6b7280;

}

.login-btn{

width:100%;

height:58px;

border:none;

border-radius:15px;

background:#2563eb;

color:white;

font-weight:600;

font-size:17px;

transition:.3s;

}

.login-btn:hover{

background:#1d4ed8;

}

.bottom{

margin-top:30px;

text-align:center;

}

.bottom a{

text-decoration:none;

font-weight:600;

color:#2563eb;

}

.back-home{

position:absolute;

top:35px;

right:35px;

text-decoration:none;

font-weight:600;

color:#2563eb;

}

/* Responsive */

@media(max-width:992px){

.login-wrapper{

width:95%;

height:auto;

flex-direction:column;

}

.left-side{

width:100%;

padding:50px;

}

.right-side{

width:100%;

padding:40px;

}

.left-side h1{

font-size:42px;

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

<img src="{{ asset('images/logo beasiswa.png') }}">

<span>SIPB</span>

</div>

<h1>

Selamat Datang
Kembali

</h1>

<p>

Masuk ke Sistem Informasi Penerimaan Beasiswa
untuk memperoleh rekomendasi beasiswa sesuai
profil akademik Anda menggunakan metode
Decision Tree.

</p>

<div class="feature">

<i class="bi bi-check-circle-fill"></i>

Prediksi Cepat

</div>

<div class="feature">

<i class="bi bi-check-circle-fill"></i>

Rekomendasi Beasiswa

</div>

<div class="feature">

<i class="bi bi-check-circle-fill"></i>

Analisis Decision Tree

</div>

</div>

<!-- RIGHT -->

<div class="right-side">

<div class="form-box">

<a href="/" class="back-home">

<i class="bi bi-arrow-left"></i>

Home

</a>

<h2>

Login

</h2>

<p>

Masuk ke akun SIPB Anda.

</p>

<form action="{{ route('login.store') }}" method="POST">

    @csrf

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

@csrf

<input
type="email"
name="email"
class="form-control"
placeholder="Email"
value="{{ old('email') }}"
required>
<div class="password">

<div class="password">

<input
type="password"
name="password"
class="form-control"
placeholder="Password"
required>

<i class="bi bi-eye"></i>

</div>

<i class="bi bi-eye"></i>

<input
class="form-check-input"
type="checkbox"
name="remember"
id="remember">

<label
class="form-check-label"
for="remember">

Remember me

</label>

<a href="#" style="text-decoration:none">

Lupa Password?

</a>

</div>

<button
type="submit"
class="login-btn">

Masuk

</button>

</form>

<div class="bottom">

Belum memiliki akun?

<a href="{{ route('register') }}">

Create Account

</a>

</div>

</div>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>