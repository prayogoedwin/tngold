<?php

use App\Http\Controllers\Api\AppVersionApi;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HargaEmasApi;
use App\Http\Controllers\Api\PembelianApi;
use App\Http\Controllers\Api\TokoApi;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TransaksiApi;
use App\Http\Controllers\Api\TransaksiKategoriApi;

Route::prefix('pembelian')->group(function () {

    Route::post('/login', [AuthController::class, 'loginPembelian']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [UserController::class, 'me']);
        Route::get('/users', [UserController::class, 'index'])->middleware('role:admin');
        Route::post('/update-password', [UserController::class, 'updatePassword']);

        // - cek harga
        //resource hargaemas
        Route::get('harga-emas', [HargaEmasApi::class, 'index'])->middleware('permission:view-harga-emas');
        Route::post('harga-emas', [HargaEmasApi::class, 'store'])->middleware('permission:create-harga-emas');

        // - harga terkini kasih masing2 dari produk
        Route::get('harga-emas/{hargaEmas}', [HargaEmasApi::class, 'show'])->middleware('permission:show-harga-emas');
        Route::put('harga-emas/{hargaEmas}', [HargaEmasApi::class, 'update'])->middleware('permission:edit-harga-emas');
        Route::delete('harga-emas/{hargaEmas}', [HargaEmasApi::class, 'destroy'])->middleware('permission:delete-harga-emas');



        // - saldo terkini
        //resource transaksi
        Route::get('transaksis', [TransaksiApi::class, 'index'])->middleware('permission:view-transaksis');
        Route::post('transaksis', [TransaksiApi::class, 'store'])->middleware('permission:create-transaksis');
        Route::get('transaksis/current-saldo', [TransaksiApi::class, 'getCurrentSaldo'])->middleware('permission:view-transaksis');
        Route::get('transaksis/total-nominal-transaksi-masuk', [TransaksiApi::class, 'getTotalNominalTransaksiMasuk'])->middleware('permission:view-transaksis');
        Route::get('transaksis/total-nominal-transaksi-keluar', [TransaksiApi::class, 'getTotalNominalTransaksiKeluar'])->middleware('permission:view-transaksis');
        Route::get('transaksis/{transaksi}', [TransaksiApi::class, 'show'])->middleware('permission:show-transaksis');
        Route::put('transaksis/{transaksi}', [TransaksiApi::class, 'update'])->middleware('permission:edit-transaksis');
        Route::delete('transaksis/{transaksi}', [TransaksiApi::class, 'destroy'])->middleware('permission:delete-transaksis');

        //resource transaksi-kategori
        Route::get('transaksi-kategoris', [TransaksiKategoriApi::class, 'index'])->name('transaksi-kategoris.index')->middleware('permission:view-transaksi-kategoris');
        Route::post('transaksi-kategoris', [TransaksiKategoriApi::class, 'store'])->name('transaksi-kategoris.store')->middleware('permission:create-transaksi-kategoris');
        Route::get('transaksi-kategoris/{transaksiKategori}', [TransaksiKategoriApi::class, 'show'])->name('transaksi-kategoris.show')->middleware('permission:show-transaksi-kategoris');
        Route::put('transaksi-kategoris/{transaksiKategori}', [TransaksiKategoriApi::class, 'update'])->name('transaksi-kategoris.update')->middleware('permission:edit-transaksi-kategoris');
        Route::delete('transaksi-kategoris/{transaksiKategori}', [TransaksiKategoriApi::class, 'destroy'])->name('transaksi-kategoris.destroy')->middleware('permission:delete-transaksi-kategoris');

        // - pembelian
        Route::get('pembelians', [PembelianApi::class, 'indexPembelian'])->middleware('permission:view-pembelians');
        Route::post('pembelians', [PembelianApi::class, 'storePembelian'])->middleware('permission:create-pembelians');
        Route::get('pembelians/{pembelian}', [PembelianApi::class, 'show'])->middleware('permission:show-pembelians');
        Route::put('pembelians/{pembelian}', [PembelianApi::class, 'updatePembelian'])->middleware('permission:edit-pembelians');
        Route::delete('pembelians/{pembelian}', [PembelianApi::class, 'destroy'])->middleware('permission:delete-pembelians');


        //toko
        Route::get('tokos', [TokoApi::class, 'index'])->middleware('permission:view-tokos');
        Route::post('tokos', [TokoApi::class, 'store'])->middleware('permission:create-tokos');
        Route::get('tokos/{toko}', [TokoApi::class, 'show'])->middleware('permission:show-tokos');
        Route::put('tokos/{toko}', [TokoApi::class, 'update'])->middleware('permission:edit-tokos');
        Route::delete('tokos/{toko}', [TokoApi::class, 'destroy'])->middleware('permission:delete-tokos');

        //app versions
        Route::get('app-versions', [AppVersionApi::class, 'index'])->middleware('permission:view-app-versions');
        Route::post('app-versions', [AppVersionApi::class, 'store'])->middleware('permission:create-app-versions');
        Route::get('app-versions/{appVersion}', [AppVersionApi::class, 'show'])->middleware('permission:show-app-versions');
        Route::put('app-versions/{appVersion}', [AppVersionApi::class, 'update'])->middleware('permission:edit-app-versions');
        Route::delete('app-versions/{appVersion}', [AppVersionApi::class, 'destroy'])->middleware('permission:delete-app-versions');
    });
});

Route::prefix('internal')->group(function () {

    Route::post('/login', [AuthController::class, 'loginInternal']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [UserController::class, 'me']);
        Route::get('/users', [UserController::class, 'index'])->middleware('role:admin');
        Route::post('/update-password', [UserController::class, 'updatePassword']);

        // - cek harga
        //resource hargaemas
        Route::get('harga-emas', [HargaEmasApi::class, 'index'])->middleware('permission:view-harga-emas');
        Route::post('harga-emas', [HargaEmasApi::class, 'store'])->middleware('permission:create-harga-emas');

        // - harga terkini kasih masing2 dari produk
        Route::get('harga-emas/{hargaEmas}', [HargaEmasApi::class, 'show'])->middleware('permission:show-harga-emas');
        Route::put('harga-emas/{hargaEmas}', [HargaEmasApi::class, 'update'])->middleware('permission:edit-harga-emas');
        Route::delete('harga-emas/{hargaEmas}', [HargaEmasApi::class, 'destroy'])->middleware('permission:delete-harga-emas');



        // - saldo terkini
        //resource transaksi
        Route::get('transaksis', [TransaksiApi::class, 'index'])->middleware('permission:view-transaksis');
        Route::post('transaksis', [TransaksiApi::class, 'store'])->middleware('permission:create-transaksis');
        Route::get('transaksis/current-saldo', [TransaksiApi::class, 'getCurrentSaldo'])->middleware('permission:view-transaksis');
        Route::get('transaksis/total-nominal-transaksi-masuk', [TransaksiApi::class, 'getTotalNominalTransaksiMasuk'])->middleware('permission:view-transaksis');
        Route::get('transaksis/total-nominal-transaksi-keluar', [TransaksiApi::class, 'getTotalNominalTransaksiKeluar'])->middleware('permission:view-transaksis');
        Route::get('transaksis/{transaksi}', [TransaksiApi::class, 'show'])->middleware('permission:show-transaksis');
        Route::put('transaksis/{transaksi}', [TransaksiApi::class, 'update'])->middleware('permission:edit-transaksis');
        Route::delete('transaksis/{transaksi}', [TransaksiApi::class, 'destroy'])->middleware('permission:delete-transaksis');

        //resource transaksi-kategori
        Route::get('transaksi-kategoris', [TransaksiKategoriApi::class, 'index'])->name('transaksi-kategoris.index')->middleware('permission:view-transaksi-kategoris');
        Route::post('transaksi-kategoris', [TransaksiKategoriApi::class, 'store'])->name('transaksi-kategoris.store')->middleware('permission:create-transaksi-kategoris');
        Route::get('transaksi-kategoris/{transaksiKategori}', [TransaksiKategoriApi::class, 'show'])->name('transaksi-kategoris.show')->middleware('permission:show-transaksi-kategoris');
        Route::put('transaksi-kategoris/{transaksiKategori}', [TransaksiKategoriApi::class, 'update'])->name('transaksi-kategoris.update')->middleware('permission:edit-transaksi-kategoris');
        Route::delete('transaksi-kategoris/{transaksiKategori}', [TransaksiKategoriApi::class, 'destroy'])->name('transaksi-kategoris.destroy')->middleware('permission:delete-transaksi-kategoris');

        // - pembelian
        Route::get('pembelians', [PembelianApi::class, 'indexPembelian'])->middleware('permission:view-pembelians');
        Route::post('pembelians', [PembelianApi::class, 'storeInternal'])->middleware('permission:create-pembelians');
        Route::get('pembelians/{pembelian}', [PembelianApi::class, 'show'])->middleware('permission:show-pembelians');
        Route::put('pembelians/{pembelian}', [PembelianApi::class, 'updateInternal'])->middleware('permission:edit-pembelians');
        Route::delete('pembelians/{pembelian}', [PembelianApi::class, 'destroy'])->middleware('permission:delete-pembelians');


        //toko
        Route::get('tokos', [TokoApi::class, 'index'])->middleware('permission:view-tokos');
        Route::post('tokos', [TokoApi::class, 'store'])->middleware('permission:create-tokos');
        Route::get('tokos/{toko}', [TokoApi::class, 'show'])->middleware('permission:show-tokos');
        Route::put('tokos/{toko}', [TokoApi::class, 'update'])->middleware('permission:edit-tokos');
        Route::delete('tokos/{toko}', [TokoApi::class, 'destroy'])->middleware('permission:delete-tokos');

        //app versions
        Route::get('app-versions', [AppVersionApi::class, 'index'])->middleware('permission:view-app-versions');
        Route::post('app-versions', [AppVersionApi::class, 'store'])->middleware('permission:create-app-versions');
        Route::get('app-versions/{appVersion}', [AppVersionApi::class, 'show'])->middleware('permission:show-app-versions');
        Route::put('app-versions/{appVersion}', [AppVersionApi::class, 'update'])->middleware('permission:edit-app-versions');
        Route::delete('app-versions/{appVersion}', [AppVersionApi::class, 'destroy'])->middleware('permission:delete-app-versions');
    });
});
