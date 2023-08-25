<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PinjamController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/list-peminjam', [PinjamController::class, 'index']);
Route::get('/total-peminjam', [PinjamController::class, 'getTotal']);
Route::get('/list-pengembalian', [PinjamController::class, 'indexPengembalian']);
Route::get('/list-anggota', [AnggotaController::class, 'index']);
Route::get('/list-buku', [BukuController::class, 'index']);

Route::prefix('/data')->group(
    function() {
        // Route untuk mengatur CRUD Anggota
        Route::post('/store-anggota', [AnggotaController::class, 'store']);
        Route::post('/update-anggota/{id}', [AnggotaController::class, 'update']);
        Route::delete('/destroy-anggota/{id}', [AnggotaController::class, 'destroy']);
        Route::delete('/show-anggota/{id}', [AnggotaController::class, 'show']);
        Route::get('/search-anggota/{name}', [AnggotaController::class, 'search']);
        
        // Route untuk menyimpan peminjam dan mencari peminjam
        Route::post('/store-peminjam', [PinjamController::class, 'store']);
        Route::get('/search-peminjam/{name}', [PinjamController::class, 'search']);

        // Route mengganti status pinjam ke pengembalian dan mencari pengembalian
        Route::get('/pengembalian/{id}', [PinjamController::class, 'update']);
        Route::get('/search-pengembalian/{name}', [PinjamController::class, 'searchPengembalian']);
        
        // Route untuk mengatur CRUD Buku
        Route::post('/store-buku', [BukuController::class, 'store']);
        Route::get('/search-buku/{judul}', [BukuController::class, 'search']);
        Route::post('/update-buku/{id}', [BukuController::class, 'update']);
        Route::delete('/destroy-buku/{id}', [BukuController::class, 'destroy']);
        
        // Route untuk mengatur AUTH (login-register)
        Route::post('/register', [LoginController::class, 'register']);
        Route::post('/login', [LoginController::class, 'login']);
        Route::get('/logout', [LoginController::class, 'logout']);

        // Route untuk mencari jumlah peminjam dalam setahun dan rentang tahun tertentu
        Route::get('/jml-peminjam/{tahun}', [DashboardController::class, 'jmlPeminjamPerTahun']);
        Route::get('/tahun-awal/{tahunAwal}/tahun-akhir/{tahunAkhir}', [DashboardController::class, 'jmlPeminjamRentang']);
        
        Route::get('/get-peminjam', [DashboardController::class, 'getPeminjam']);
        
        // Route::get('/jml-anggota/{tahun}', [DashboardController::class, 'jmlAnggotaPerTahun']);
        // Route::get('/anggota-tahun-awal/{tahunAwal}/anggota-tahun-akhir/{tahunAkhir}', [DashboardController::class, 'jmlAnggotaRentang']);
        
        // Route::get('/jml-buku/{tahun}', [DashboardController::class, 'jmlBukuPerTahun']);
        // Route::get('/buku-tahun-awal/{tahunAwal}/buku-tahun-akhir/{tahunAkhir}', [DashboardController::class, 'jmlBukuRentang']);

    }
);