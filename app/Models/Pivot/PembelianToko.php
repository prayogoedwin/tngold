<?php

namespace App\Models\Pivot;

use App\Models\Pembelian\Pembelian;
use Illuminate\Database\Eloquent\Model;
use App\Models\Toko;

class PembelianToko extends Model
{
    // Model untuk tabel pivot pembelian - toko
    protected $table = 'pembelian_tokos';
    public $timestamps = false;

    public function toko()
    {
        return $this->belongsTo(Toko::class, 'toko_id');
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
    }
}
