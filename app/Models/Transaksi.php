<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TransaksiKategori;
use App\Models\Toko;
use Illuminate\Database\Eloquent\SoftDeletes;


// this is not real Model, just a template structure for copy paste purposes
class Transaksi extends Model
{
    use SoftDeletes;
    protected $table = 'transaksis';
    protected $fillable = [
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

            'columns' => [
                
                [
                    'name' => 'transaksi_kategori_id',
                    'data' => 'transaksi_kategori.name',
                    'display_value' => 'transaksi_kategoris.name',
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
                    'display_value' => 'tokos.name',
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
                    'validation' => 'required|in:"IN","OUT"',
                    'label' => 'Type',
                    'options' => ['IN', 'OUT'],
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
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

    public function transaksiKategori()
    {
        return $this->belongsTo(TransaksiKategori::class);
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
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
