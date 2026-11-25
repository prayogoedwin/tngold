<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('app_versions', function (Blueprint $table) {
            $table->id();
            $table->string('tipe'); // pembelian/internal
            $table->string('tipe_version'); //debug/rilis
            $table->string('version'); //contoh: 1.0.0
            $table->string('kode_akses')->nullable(); //contoh: ABC123
            $table->string('url_apk_file')->nullable(); //contoh: https://example.com/app.apk
            $table->text('catatan')->nullable(); //contoh: Catatan tambahan tentang versi aplikasi
            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('release_at')->nullable(); //contoh: 2026-07-01 00:00:00
            $table->foreignId('release_by')->nullable()->constrained('users'); //contoh: ID pengguna yang merilis versi aplikasi
            $table->foreignId('created_by')->nullable()->constrained('users'); //contoh: ID pengguna yang membuat versi aplikasi
            $table->foreignId('updated_by')->nullable()->constrained('users'); //contoh: ID pengguna yang terakhir memperbarui versi aplikasi
            $table->foreignId('deleted_by')->nullable()->constrained('users'); //contoh: ID pengguna yang menghapus versi aplikasi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_versions');
    }
};
