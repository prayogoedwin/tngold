<?php

use App\Http\Controllers\AppVersionController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\Settings;
use App\Http\Controllers\TokoController;
use App\Http\Controllers\Transaksi\TransaksiKategoriController;
use App\Http\Controllers\Transaksi\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HargaEmas\HargaEmasController;
use App\Http\Controllers\HargaEmas\HargaEmasHistoryController;
use App\Http\Controllers\PembelianController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
})->name('home');

//alfay merubah sesuatu aku ubah lagi

Route::get('dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

//bisa tanpa login
Route::get('app-versions', [AppVersionController::class, 'index'])->name('app-versions.index');
Route::get('app-versions/indexTable', [AppVersionController::class, 'indexTable'])->name('app-versions.indexTable');

Route::middleware(['auth'])->group(function () {
    Route::get('api-token', [UserController::class, 'getApiToken'])->name('api-token');

    Route::get('settings/profile', [Settings\ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [Settings\ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [Settings\ProfileController::class, 'destroy'])->name('settings.profile.destroy');
    Route::get('settings/password', [Settings\PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [Settings\PasswordController::class, 'update'])->name('settings.password.update');
    Route::get('settings/appearance', [Settings\AppearanceController::class, 'edit'])->name('settings.appearance.edit');
    Route::put('settings/appearance', [Settings\AppearanceController::class, 'update'])->name('settings.appearance.update');

    // Roles Management - dengan permission check
    Route::get('roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:view-roles');
    Route::get('roles/export', [RoleController::class, 'export'])->name('roles.export')->middleware('permission:download-roles');
    Route::get('roles/create', [RoleController::class, 'create'])->name('roles.create')->middleware('permission:create-roles');
    Route::post('roles', [RoleController::class, 'store'])->name('roles.store')->middleware('permission:create-roles');
    Route::get('roles/{role}', [RoleController::class, 'show'])->name('roles.show')->middleware('permission:show-roles');
    Route::get('roles/{role}/edit', [RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:edit-roles');
    Route::put('roles/{role}', [RoleController::class, 'update'])->name('roles.update')->middleware('permission:edit-roles');
    Route::delete('roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:delete-roles');

    // Permissions Management - dengan permission check
    Route::get('permissions', [PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:view-permissions');
    Route::get('permissions/export', [PermissionController::class, 'export'])->name('permissions.export')->middleware('permission:download-permissions');
    Route::get('permissions/create', [PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:create-permissions');
    Route::post('permissions', [PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:create-permissions');
    Route::get('permissions/{permission}', [PermissionController::class, 'show'])->name('permissions.show')->middleware('permission:show-permissions');
    Route::get('permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:edit-permissions');
    Route::put('permissions/{permission}', [PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:edit-permissions');
    Route::delete('permissions/{permission}', [PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:delete-permissions');

    // Users Management - dengan permission check
    Route::get('users', [UserController::class, 'index'])->name('users.index')->middleware('permission:view-users');
    Route::get('users/export', [UserController::class, 'export'])->name('users.export')->middleware('permission:download-users');
    Route::get('users/create', [UserController::class, 'create'])->name('users.create')->middleware('permission:create-users');
    Route::post('users', [UserController::class, 'store'])->name('users.store')->middleware('permission:create-users');
    Route::get('users/{user}', [UserController::class, 'show'])->name('users.show')->middleware('permission:show-users');
    Route::get('users/{user}/edit', [UserController::class, 'edit'])->name('users.edit')->middleware('permission:edit-users');
    Route::put('users/{user}', [UserController::class, 'update'])->name('users.update')->middleware('permission:edit-users');
    Route::delete('users/{user}', [UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete-users');

    // Resource Toko
    Route::get('tokos', [TokoController::class, 'index'])->name('tokos.index')->middleware('permission:view-tokos');
    Route::get('tokos/indexTable', [TokoController::class, 'indexTable'])->name('tokos.indexTable')->middleware('permission:view-tokos');
    Route::get('tokos/export', [TokoController::class, 'export'])->name('tokos.export')->middleware('permission:download-tokos');
    Route::get('tokos/create', [TokoController::class, 'create'])->name('tokos.create')->middleware('permission:create-tokos');
    Route::post('tokos', [TokoController::class, 'store'])->name('tokos.store')->middleware('permission:create-tokos');
    Route::get('tokos/{toko}', [TokoController::class, 'show'])->name('tokos.show')->middleware('permission:show-tokos');
    Route::get('tokos/{toko}/edit', [TokoController::class, 'edit'])->name('tokos.edit')->middleware('permission:edit-tokos');
    Route::put('tokos/{toko}', [TokoController::class, 'update'])->name('tokos.update')->middleware('permission:edit-tokos');
    Route::delete('tokos/{toko}', [TokoController::class, 'destroy'])->name('tokos.destroy')->middleware('permission:delete-tokos');

    // Resource Transaksi
    Route::get('transaksis', [TransaksiController::class, 'index'])->name('transaksis.index')->middleware('permission:view-transaksis');
    Route::get('transaksis/indexTable', [TransaksiController::class, 'indexTable'])->name('transaksis.indexTable')->middleware('permission:view-transaksis');
    Route::get('transaksis/export', [TransaksiController::class, 'export'])->name('transaksis.export')->middleware('permission:download-transaksis');
    Route::get('transaksis/create', [TransaksiController::class, 'create'])->name('transaksis.create')->middleware('permission:create-transaksis');
    Route::post('transaksis', [TransaksiController::class, 'store'])->name('transaksis.store')->middleware('permission:create-transaksis');
    Route::get('transaksis/{transaksi}', [TransaksiController::class, 'show'])->name('transaksis.show')->middleware('permission:show-transaksis');
    Route::get('transaksis/{transaksi}/edit', [TransaksiController::class, 'edit'])->name('transaksis.edit')->middleware('permission:edit-transaksis');
    Route::put('transaksis/{transaksi}', [TransaksiController::class, 'update'])->name('transaksis.update')->middleware('permission:edit-transaksis');
    Route::delete('transaksis/{transaksi}', [TransaksiController::class, 'destroy'])->name('transaksis.destroy')->middleware('permission:delete-transaksis');

    //resource transaksi-kategoris
    Route::get('transaksi-kategoris', [TransaksiKategoriController::class, 'index'])->name('transaksi-kategoris.index')->middleware('permission:view-transaksi-kategoris');
    Route::get('transaksi-kategoris/indexTable', [TransaksiKategoriController::class, 'indexTable'])->name('transaksi-kategoris.indexTable')->middleware('permission:view-transaksi-kategoris');
    Route::get('transaksi-kategoris/export', [TransaksiKategoriController::class, 'export'])->name('transaksi-kategoris.export')->middleware('permission:download-transaksi-kategoris');
    Route::get('transaksi-kategoris/create', [TransaksiKategoriController::class, 'create'])->name('transaksi-kategoris.create')->middleware('permission:create-transaksi-kategoris');
    Route::post('transaksi-kategoris', [TransaksiKategoriController::class, 'store'])->name('transaksi-kategoris.store')->middleware('permission:create-transaksi-kategoris');
    Route::get('transaksi-kategoris/{transaksiKategori}', [TransaksiKategoriController::class, 'show'])->name('transaksi-kategoris.show')->middleware('permission:show-transaksi-kategoris');
    Route::get('transaksi-kategoris/{transaksiKategori}/edit', [TransaksiKategoriController::class, 'edit'])->name('transaksi-kategoris.edit')->middleware('permission:edit-transaksi-kategoris');
    Route::put('transaksi-kategoris/{transaksiKategori}', [TransaksiKategoriController::class, 'update'])->name('transaksi-kategoris.update')->middleware('permission:edit-transaksi-kategoris');
    Route::delete('transaksi-kategoris/{transaksiKategori}', [TransaksiKategoriController::class, 'destroy'])->name('transaksi-kategoris.destroy')->middleware('permission:delete-transaksi-kategoris');

    //resource hargaemas
    Route::get('harga-emas', [HargaEmasController::class, 'index'])->name('harga-emas.index')->middleware('permission:view-harga-emas');
    Route::get('harga-emas/indexTable', [HargaEmasController::class, 'indexTable'])->name('harga-emas.indexTable')->middleware('permission:view-harga-emas');
    Route::get('harga-emas/export', [HargaEmasController::class, 'export'])->name('harga-emas.export')->middleware('permission:download-harga-emas');
    Route::get('harga-emas/create', [HargaEmasController::class, 'create'])->name('harga-emas.create')->middleware('permission:create-harga-emas');
    Route::post('harga-emas', [HargaEmasController::class, 'store'])->name('harga-emas.store')->middleware('permission:create-harga-emas');
    Route::get('harga-emas/{hargaEmas}', [HargaEmasController::class, 'show'])->name('harga-emas.show')->middleware('permission:show-harga-emas');
    Route::get('harga-emas/{hargaEmas}/edit', [HargaEmasController::class, 'edit'])->name('harga-emas.edit')->middleware('permission:edit-harga-emas');
    Route::put('harga-emas/{hargaEmas}', [HargaEmasController::class, 'update'])->name('harga-emas.update')->middleware('permission:edit-harga-emas');
    Route::delete('harga-emas/{hargaEmas}', [HargaEmasController::class, 'destroy'])->name('harga-emas.destroy')->middleware('permission:delete-harga-emas');

    Route::get('harga-emas/{hargaEmas}/history/indexTable', [HargaEmasController::class, 'historyIndexTable'])->name('harga-emas.history.indexTable')->middleware('permission:view-harga-emas');

    //resource hargaemas history
    Route::get('harga-emas-history', [HargaEmasHistoryController::class, 'index'])->name('harga-emas-history.index')->middleware('permission:view-harga-emas-history');
    Route::get('harga-emas-history/indexTable', [HargaEmasHistoryController::class, 'indexTable'])->name('harga-emas-history.indexTable')->middleware('permission:view-harga-emas-history');
    Route::get('harga-emas-history/export', [HargaEmasHistoryController::class, 'export'])->name('harga-emas-history.export')->middleware('permission:download-harga-emas-history');
    Route::get('harga-emas-history/create', [HargaEmasHistoryController::class, 'create'])->name('harga-emas-history.create')->middleware('permission:create-harga-emas-history');
    Route::post('harga-emas-history', [HargaEmasHistoryController::class, 'store'])->name('harga-emas-history.store')->middleware('permission:create-harga-emas-history');
    Route::get('harga-emas-history/{hargaEmasHistory}', [HargaEmasHistoryController::class, 'show'])->name('harga-emas-history.show')->middleware('permission:show-harga-emas-history');
    Route::get('harga-emas-history/{hargaEmasHistory}/edit', [HargaEmasHistoryController::class, 'edit'])->name('harga-emas-history.edit')->middleware('permission:edit-harga-emas-history');
    Route::put('harga-emas-history/{hargaEmasHistory}', [HargaEmasHistoryController::class, 'update'])->name('harga-emas-history.update')->middleware('permission:edit-harga-emas-history');
    Route::delete('harga-emas-history/{hargaEmasHistory}', [HargaEmasHistoryController::class, 'destroy'])->name('harga-emas-history.destroy')->middleware('permission:delete-harga-emas-history');

    //resource pembelians
    Route::get('pembelians/pilihToko', [PembelianController::class, 'pilihToko'])->name('pembelians.pilihToko')->middleware('permission:view-pembelians');
    Route::post('pembelians/simpantoko', [PembelianController::class, 'simpanPilihanToko'])->name('pembelians.simpantoko')->middleware('permission:view-pembelians');
    Route::post('pembelians/exittoko', [PembelianController::class, 'exitToko'])->name('pembelians.exittoko')->middleware('permission:view-pembelians');
    Route::get('pembelians', [PembelianController::class, 'index'])->name('pembelians.index')->middleware('permission:view-pembelians');
    Route::get('pembelians/indexTable', [PembelianController::class, 'indexTable'])->name('pembelians.indexTable')->middleware('permission:view-pembelians');
    Route::get('pembelians/export', [PembelianController::class, 'export'])->name('pembelians.export')->middleware('permission:download-pembelians');
    Route::get('pembelians/create', [PembelianController::class, 'create'])->name('pembelians.create')->middleware('permission:create-pembelians');
    Route::post('pembelians', [PembelianController::class, 'store'])->name('pembelians.store')->middleware('permission:create-pembelians');
    Route::get('pembelians/{pembelian}', [PembelianController::class, 'show'])->name('pembelians.show')->middleware('permission:show-pembelians');
    Route::get('pembelians/{pembelian}/edit', [PembelianController::class, 'edit'])->name('pembelians.edit')->middleware('permission:edit-pembelians');
    Route::put('pembelians/{pembelian}', [PembelianController::class, 'update'])->name('pembelians.update')->middleware('permission:edit-pembelians');
    Route::delete('pembelians/{pembelian}', [PembelianController::class, 'destroy'])->name('pembelians.destroy')->middleware('permission:delete-pembelians');



    Route::get('app-versions/export', [AppVersionController::class, 'export'])->name('app-versions.export')->middleware('permission:download-app-versions');
    Route::get('app-versions/create', [AppVersionController::class, 'create'])->name('app-versions.create')->middleware('permission:create-app-versions');
    Route::post('app-versions', [AppVersionController::class, 'store'])->name('app-versions.store')->middleware('permission:create-app-versions');
    Route::get('app-versions/{appVersion}', [AppVersionController::class, 'show'])->name('app-versions.show')->middleware('permission:show-app-versions');
    Route::get('app-versions/{appVersion}/edit', [AppVersionController::class, 'edit'])->name('app-versions.edit')->middleware('permission:edit-app-versions');
    Route::put('app-versions/{appVersion}', [AppVersionController::class, 'update'])->name('app-versions.update')->middleware('permission:edit-app-versions');
    Route::delete('app-versions/{appVersion}', [AppVersionController::class, 'destroy'])->name('app-versions.destroy')->middleware('permission:delete-app-versions');
});




require __DIR__ . '/auth.php';
