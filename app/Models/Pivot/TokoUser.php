<?php

namespace App\Models\Pivot;

use Illuminate\Database\Eloquent\Model;
use App\Models\Toko;
use App\Models\User;

class TokoUser extends Model
{
    // Pivot model for the toko_users table
    protected $table = 'toko_users';
    public $timestamps = false;

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
