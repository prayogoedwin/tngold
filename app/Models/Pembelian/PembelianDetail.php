<?php

namespace App\Models\Pembelian;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PembelianDetail extends Model
{
    use SoftDeletes;
    protected $table = 'pembelian_details';
    protected $fillable = [
        'pembelian_id',
        'emas_asal',
        'kadar',
        'satuan',
        'jumlah',
        'harga_per_satuan', ////harga beli per jumlah/satuan
        'total_harga', // total harga = jumlah * harga_per_satuan
        'created_by',
        'updated_by',
        'deleted_by',
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
            'parent' => 'pembelians',
            'table' => 'pembelian-details',
            'title' => 'Pembelian Detail',
            'table_has_actions' => true,
            'table_has_timestamps' => true,

            'columns' => [
                [
                    'name' => 'emas_asal',
                    'data' => 'emas_asal',
                    'display_value' => 'emas_asal',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Emas Asal',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'kadar',
                    'data' => 'kadar',
                    'display_value' => 'kadar',
                    'type' => 'integer',
                    'validation' => 'required|integer|min:0',
                    'label' => 'Kadar',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'satuan',
                    'data' => 'satuan',
                    'display_value' => 'satuan',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Satuan',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'jumlah',
                    'data' => 'jumlah',
                    'display_value' => 'jumlah',
                    'type' => 'integer',
                    'validation' => 'required|integer|min:0',
                    'label' => 'Jumlah',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'harga_per_satuan',
                    'data' => 'harga_per_satuan',
                    'display_value' => 'harga_per_satuan',
                    'type' => 'integer',
                    'validation' => 'required|integer|min:0',
                    'label' => 'Harga Per Satuan',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'total_harga',
                    'data' => 'total_harga',
                    'display_value' => 'total_harga',
                    'type' => 'integer',
                    'validation' => 'required|integer|min:0',
                    'label' => 'Total Harga',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ]

            ]
        ];
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class, 'pembelian_id');
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
