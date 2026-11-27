<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TransaksiKategori;
use App\Models\Toko;
use Illuminate\Database\Eloquent\SoftDeletes;


class Transaksi extends Model
{
    use SoftDeletes;
    protected $table = 'transaksis';
    protected $fillable = [
        'kode',
        'type',
        'transaksi_kategori_id',
        'tanggal',
        'toko_id',
        'nominal',
        'keterangan',
        'created_by',
        'updated_by',
        'deleted_by'

    ];
    protected $casts = [
        'nominal' => 'integer',
        'toko_id' => 'integer',
        'transaksi_kategori_id' => 'integer',
        'tanggal' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();
            }

            // auto-generate kode
            //contoh format = K202611#4

            $t = $model->type == 'IN' ? 'M' : 'K';
            $p = date('Ym');
            $count = self::where('type', $model->type)->whereYear('tanggal', date('Y'))->whereMonth('tanggal', date('m'))->count() + 1;
            $model->kode = $t . $p . '#' . $count;

        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });

        static::deleting(function ($model) {
            if (auth()->check()) {
                $model->deleted_by = auth()->id();
                $model->save();
            }
        });
    }

    public function structure()
    {
        return [
            'parent' => null,
            'table' => 'transaksis',
            'title' => 'Transaksi',
            'table_has_actions' => true,
            'table_has_timestamps' => true,
            'index_has_download' => false,

            'columns' => [
                [
                    'name' => 'kode',
                    'data' => 'kode',
                    'display_value' => 'kode',
                    'type' => 'text',
                    'validation' => 'nullable|string|max:255',
                    'label' => 'Kode',
                    'required' => false,
                    'inTable' => false,
                    'inCreateForm' => false,
                    'inEditForm' => false,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'transaksi_kategori_id',
                    'data' => 'transaksi_kategori.name',
                    'display_value' => 'transaksiKategori.name',
                    'type' => 'select',
                    'validation' => 'required|exists:transaksi_kategoris,id',
                    'label' => 'Kategori Transaksi',
                    'options' => TransaksiKategori::all()->pluck('name', 'id')->toArray(),
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'toko_id',
                    'data' => 'toko.name',
                    'display_value' => 'toko.name',
                    'type' => 'select',
                    'validation' => 'required|exists:tokos,id',
                    'label' => 'Toko',
                    'options' => Toko::all()->pluck('name', 'id')->toArray(),
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'type',
                    'data' => 'type',
                    'display_value' => 'type',
                    'type' => 'enum',
                    'validation' => 'nullable|in:"IN","OUT"',
                    'label' => 'Type',
                    'options' => ['IN', 'OUT'],
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => false,
                    'inEditForm' => false,
                    'inShowPage' => true,
                ],
                
                [
                    'name' => 'tanggal',
                    'data' => 'tanggal',
                    'display_value' => 'tanggal',
                    'type' => 'date',
                    'validation' => 'required|date',
                    'label' => 'Tanggal',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'nominal',
                    'data' => 'nominal',
                    'display_value' => 'nominal',
                    'type' => 'number',
                    'validation' => 'required|integer|min:0',
                    'label' => 'Nominal',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'keterangan',
                    'data' => 'keterangan',
                    'display_value' => 'keterangan',
                    'type' => 'text',
                    'validation' => 'required|string|max:255',
                    'label' => 'Keterangan',
                    'required' => true,
                    'inTable' => false,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],   
            ]
        ];
    }

    public function transformed()
    {
        return [
            'id' => $this->id,
            'kode' => $this->kode,
            'type' => $this->type,
            'nominal' => $this->nominal,
            'keterangan' => $this->keterangan,
            'transaksi_kategori_id' => $this->transaksi_kategori_id,
            'title' => $this->transaksiKategori ? $this->transaksiKategori->name : null,
            'toko_id' => $this->toko_id,
            'created_by' => $this->createdBy ? $this->createdBy->name : null,
            'created_at' => $this->created_at
        ];
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function transaksiKategori()
    {
        return $this->belongsTo(TransaksiKategori::class);
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function transaksiMasuk()
    {
        // yang type nya 'IN'
        return $this->where('type', 'IN');
    }

    public function transaksiKeluar()
    {
        // yang type nya 'OUT'
        return $this->where('type', 'OUT');
    }

    public function currentSaldo(int $tokoId)
    {
        $saldoMasuk = $this->transaksiMasuk()->where('toko_id', $tokoId)->sum('nominal');
        $saldoKeluar = $this->transaksiKeluar()->where('toko_id', $tokoId)->sum('nominal');
        return $saldoMasuk - $saldoKeluar;
    }

    public function currentSaldoAll()
    {
        $saldoMasuk = $this->transaksiMasuk()->sum('nominal');
        $saldoKeluar = $this->transaksiKeluar()->sum('nominal');
        return $saldoMasuk - $saldoKeluar;
    }

    public function getValidationRules(): array
    {
        $structure = $this->structure();

        $rules = [];

        foreach ($structure['columns'] as $column) {
            if (!empty($column['validation'])) {
                $rules[$column['name']] = $column['validation'];
            }
        }

        return $rules;
    }
}
