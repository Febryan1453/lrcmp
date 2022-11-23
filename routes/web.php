<?php

use App\Http\Controllers\Admin\BootcampController;
use App\Http\Controllers\Admin\CrudMentorController;
use App\Http\Controllers\Admin\KategoriController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Mentor\MentorController;
use App\Http\Controllers\Peserta\PesertaController;
use App\Http\Controllers\Template\DashboardController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;




// Akses Tamu : Login atau tidak login itu bisa lihat halaman depan
Route::controller(FrontController::class)->group(function(){
    Route::get('/', 'index')->name('front.index');
    Route::get('/bootcamps', 'bootcamps')->name('front.bootcamps');
    Route::get('/bootcamps/detail/{slug}', 'detail_bootcamp')->name('front.detail.bootcamp');
    Route::get('/bootcamps/kategori/{slug}', 'kategori_bootcamp')->name('front.kategori.bootcamp');
    Route::post('/bootcamps/register', 'daftar_bootcamp')->name('front.daftar.bootcamp');
    Route::get('/bootcamps/mentor', 'mentor_bootcamp')->name('front.mentor.bootcamp');
    Route::get('/bootcamps/mentor/kelas/{username}', 'kelas_mentor_bootcamp')->name('front.kelas.mentor.bootcamp');
    Route::get('/bootcamps/my_dashboard', 'my_dashboard')->middleware('auth')->name('front.my.dashboard');
});
// End Akses Tamu

Auth::routes();

// Akses Admin = 1
Route::prefix('a')->middleware(['auth','isAdmin'])->group(function(){
    Route::controller(DashboardController::class)->group(function(){
        Route::get('welcome', 'index')->name('welcome.index');
        Route::get('admin', 'list_admin')->name('welcome.list.admin');
        Route::get('peserta', 'list_peserta')->name('welcome.list.peserta');
    });
    Route::controller(KategoriController::class)->group(function(){
        Route::get('kategori', 'index')->name('kategori.index');
        Route::post('kategori/store', 'store')->name('kategori.store');
        Route::put('kategori/update', 'update')->name('kategori.update');
        Route::delete('kategori/destroy', 'destroy')->name('kategori.destroy');
    });
    Route::controller(BootcampController::class)->group(function(){
        Route::get('bootcamp', 'index')->name('bootcamp.index');
        Route::get('bootcamp/baru', 'create')->name('bootcamp.create');
        Route::post('bootcamp/store', 'store')->name('bootcamp.store');
        Route::delete('bootcamp/destroy', 'destroy')->name('bootcamp.destroy');
    });
    Route::controller(CrudMentorController::class)->group(function(){
        Route::get('mentor', 'index')->name('crud.mentor.index');
        Route::get('mentor/baru', 'create')->name('crud.mentor.create');
        Route::post('mentor/store', 'store')->name('crud.mentor.store');
    });
    Route::controller(TransaksiController::class)->group(function(){
        Route::get('transaksi', 'index')->name('transaksi.index');
        Route::post('transaksi/status/update', 'status_update')->name('transaksi.status.update');
    });
});
// End Akses Admin

// Akses Mentor = 2
Route::prefix('m')->middleware(['auth','isMentor','blokir'])->group(function(){
    Route::controller(MentorController::class)->group(function(){
        Route::get('welcome', 'index')->name('mentor.index');
        Route::get('my_bootcamp', 'my_bootcamp')->name('mentor.my.bootcamp');
        Route::get('peserta_bootcamp/{id}', 'peserta_bootcamp')->name('mentor.my.peserta');
    });
});
// End Akses Mentor

// Akses Peserta = 3
Route::prefix('p')->middleware(['auth','isPeserta','blokir'])->group(function(){
    Route::controller(PesertaController::class)->group(function(){
        Route::get('welcome', 'index')->name('peserta.index');
        Route::get('success_checkout', 'success_checkout')->name('peserta.success');
        Route::get('transaksi', 'transaksi')->name('peserta.transaksi');
        Route::get('my_bootcamp', 'my_bootcamp')->name('peserta.my.bootcamp');
    });
});
// End Akses Peserta

// Route Belajar Get Data Api yang method nya Get dari api luar
Route::get('/doa_harian', [App\Http\Controllers\Api\AdditionalController::class, 'getDoa']);
Route::get('/registrasi', [App\Http\Controllers\Api\AdditionalController::class, 'regis']);
Route::get('/login', [App\Http\Controllers\Api\AdditionalController::class, 'login']);
Route::post('/proses-registrasi', [App\Http\Controllers\Api\AdditionalController::class, 'prosesRegis'])->name('regis.aja');


// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

