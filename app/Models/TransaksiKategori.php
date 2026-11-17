<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Toko;
use Illuminate\Database\Eloquent\SoftDeletes;


// this is not real Model, just a template structure for copy paste purposes
class TransaksiKategori extends Model
{
    use SoftDeletes;
    protected $table = 'transaksi_kategoris';
    protected $fillable = [
        'name',
        'type',
        'created_by',
        'updated_by',
        'deleted_by'

    ];
    protected $casts = [
        
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
            'table' => 'transaksi-kategoris',
            'title' => 'Kategori Transaksi',
            'table_has_actions' => true,
            'table_has_timestamps' => false,
            'index_has_download' => false,

            'columns' => [
                [
                    'name' => 'name',
                    'data' => 'name',
                    'display_value' => 'name',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Nama Kategori',
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


            ]
        ];
    }

    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
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
