<?php

namespace App\Models;

use App\Models\Pembelian\Pembelian;
use Illuminate\Database\Eloquent\Model;

class TransaksiPembelian extends Model
{
    // Model untuk tabel pivot transaksi_pembelians
    protected $table = 'transaksi_pembelians';
    public $timestamps = false;

    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class, 'transaksi_id');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }
}
