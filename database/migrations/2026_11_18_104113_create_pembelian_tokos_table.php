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
        //table junction pembelian - toko
        Schema::create('pembelian_tokos', function (Blueprint $table) {
            $table->primary(['pembelian_id', 'toko_id']);
            $table->foreignId('pembelian_id')->constrained()->onDelete('cascade');
            $table->foreignId('toko_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_tokos');
    }
};
