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
        Schema::create('harga_emas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('emas_asal_id')->constrained('harga_emas_emas_asal')->onDelete('cascade');
            $table->string('sumber');
            $table->foreignId('kadar_id')->constrained('harga_emas_kadars')->onDelete('cascade');
            $table->string('satuan')->default('gram');
            $table->integer('jumlah')->default(1);
            $table->integer('harga_beli'); //harga beli per jumlah/satuan
            $table->string('keterangan')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('deleted_by')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_emas');
    }
};
