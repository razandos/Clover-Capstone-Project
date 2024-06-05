<?php

use App\Http\Livewire\AdminDusun;
use App\Http\Livewire\DusunPosyandu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

use App\Http\Livewire\PendaftaranAnggota;
use App\Http\Livewire\DokumentasimasterShow;
use App\Http\Livewire\DokumentasiShow;
use App\Http\Livewire\JadwalShow;
use App\Http\Livewire\PosyanduShow;

use App\Models\Dokumentasimaster;
use App\Models\Dokumentasi;
use App\Models\Jadwal;
use App\Models\T_Posyandu;
use App\Models\T_Dusun;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// User Page
Route::get('/', function () {
    $jadwals = Jadwal::all();
    $dokumentasimasters = Dokumentasimaster::all();
    return view('beranda', compact('jadwals','dokumentasimasters')) ;
})->name('beranda');


Route::get('/jadwal-kegiatan', function () {
    $jadwals = Jadwal::all();
    $posyandu = T_Posyandu::all();
    $dusun = T_Dusun::all();
    return view('jadwal-kegiatan', compact('jadwals', 'posyandu', 'dusun'));
})->name('jadwal_kegiatan');

Route::get('/jadwal-kegiatan/detail/{judul}', function ($judul = null) {
    return view('detail-jadwal-kegiatan', compact('judul'));
})->name('jadwal_kegiatan.detail');

Route::get('/dokumentasi', function () {
    $dokumentasimasters = Dokumentasimaster::all();
    return view('dokumentasi', ['dokumentasimasters' => $dokumentasimasters]);
})->name('dokumentasi');

Route::get('/dokumentasi/detail/{judul}', function ($judul = null) {
    $dokumentasis = Dokumentasi::all();
    return view('detail-dokumentasi',compact('dokumentasis', 'judul'));
})->name('dokumentasi.detail');

Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');


//login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticated']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// Admin Page

Route::get('/admin/dashboard', [DashboardController::class, 'index'])
    ->name('admin.index')
    ->middleware(['auth', 'checkrole:utama,dusun']);

Route::group(['prefix' => 'admin'], function(){
    // Utama middleware
    Route::group(['middleware:auth' => 'utama'], function(){
        Route::get('jadwal-kegiatan', JadwalShow::class)->name('admin.jadwal');
        Route::get('kategori/posyandu', PosyanduShow::class)->name('admin.master.posyandu');
        Route::get('kategori/dokumentasi', DokumentasimasterShow::class)->name('admin.master.dokumentasi');
        Route::get('dokumentasi', DokumentasiShow::class)->name('admin.dokumentasi');
    });
    
    // Dusun middleware
    Route::group(['middleware:auth' => 'dusun', 'utama'], function(){
        Route::get('dusun-posyandu', DusunPosyandu::class)->name('admin.dusun-posyandu');
        Route::get('dusun', AdminDusun::class)->name('admin.dusun');
        Route::get('pendaftaran-anggota-posyandu', PendaftaranAnggota::class)->name('admin.pendaftaran');  
    });
    
    // Route::get('/admin/dashboard', function () {
    //     return view('admin.index');
    // })->name('admin.index');    
    
});

// Route::get('/admin/jadwal-kegiatan', function () {
//     return view('admin.jadwal-kegiatan');
// })->name('admin.jadwal');

// Route::get('/admin/dokumentasi', function () {
//     return view('admin.dokumentasi');
// })->name('admin.dokumentasi');      

// Admin Master - Page
// Route::get('/admin/kategori/posyandu', function () {
//     return view('admin.master.posyandu');
// })->name('admin.master.posyandu');

// Route::get('/admin/kategori/dokumentasi', function () {
//     return view('admin.master.dokumentasi');
// })->name('admin.master.dokumentasi');

// Auth::routes();