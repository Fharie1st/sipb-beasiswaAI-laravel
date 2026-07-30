<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PredictionController;

/*
|--------------------------------------------------------------------------
| Halaman Utama
|--------------------------------------------------------------------------
*/

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::redirect('/home', '/');

/*
|--------------------------------------------------------------------------
| Halaman Informasi Publik (Berita, Informasi, Tentang)
|--------------------------------------------------------------------------
*/

Route::get('/berita', function () {
    return view('pages.berita');
})->name('berita');

// Rute Detail Berita dengan Parameter ID 👇
Route::get('/berita/detail/{id}', function ($id) {
    return view('pages.detail-berita', compact('id'));
})->name('berita.detail');

Route::get('/informasi', function () {
    return view('pages.informasi');
})->name('informasi');

Route::get('/tentang', function () {
    return view('pages.tentang');
})->name('tentang');

/*
|--------------------------------------------------------------------------
| Guest (Belum Login)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'loginStore'])->name('login.store');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerStore'])->name('register.store');

});

/*
|--------------------------------------------------------------------------
| Auth (Harus Login)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard setelah login
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard.home');

    /*
    |--------------------------------------------------------------------------
    | Prediksi (Decision Tree)
    |--------------------------------------------------------------------------
    */

    Route::get('/prediksi', [PredictionController::class, 'chat'])
        ->name('prediction.index');

    Route::get('/prediksi/chat', [PredictionController::class, 'chat'])
        ->name('prediction.chat');

    Route::post('/prediksi', [PredictionController::class, 'predict'])
        ->name('prediction.predict');

    /*
    |--------------------------------------------------------------------------
    | ScholarAI Assistant (Tanya Jawab Bebas)
    |--------------------------------------------------------------------------
    */

    Route::get('/assistant/chat', [PredictionController::class, 'assistant'])
        ->name('assistant.chat');

    Route::post('/assistant/ask', [PredictionController::class, 'ask'])
        ->name('assistant.ask');

    /*
    |--------------------------------------------------------------------------
    | Riwayat Analisis
    |--------------------------------------------------------------------------
    */

    Route::get('/riwayat', [PredictionController::class, 'history'])
        ->name('predictions.history');

    Route::put('/riwayat/{id}', [PredictionController::class, 'update'])
        ->name('predictions.update');

    Route::delete('/riwayat/{id}', [PredictionController::class, 'destroy'])
        ->name('predictions.destroy');

    /*
    |--------------------------------------------------------------------------
    | Pengaturan Profil
    |--------------------------------------------------------------------------
    */
    Route::get('/profile', [PredictionController::class, 'editProfile'])->name('profile.edit');
    Route::patch('/profile', [PredictionController::class, 'updateProfile'])->name('profile.update');

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

});