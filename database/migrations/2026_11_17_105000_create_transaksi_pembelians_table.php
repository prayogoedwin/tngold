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
        //table junction transaksi - pembelian
        Schema::create('transaksi_pembelians', function (Blueprint $table) {
            $table->primary(['transaksi_id', 'pembelian_id']);
            $table->foreignId('transaksi_id')->constrained()->onDelete('cascade');
            $table->foreignId('pembelian_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_pembelians');
    }
};
