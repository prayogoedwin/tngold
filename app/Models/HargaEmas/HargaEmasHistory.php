<?php

namespace App\Models\HargaEmas;

use App\Models\HargaEmas\HargaEmas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class HargaEmasHistory extends Model
{
    use SoftDeletes;
    protected $table = 'harga_emas_histories';
    protected $fillable = [
        'harga_emas_id',
        'tanggal',
        'harga_beli', ////harga beli per jumlah/satuan
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal' => 'datetime',
        'harga_beli' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();
            }
        });

        static::created(function ($model) {
            if (auth()->check()) {
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
            'table' => 'harga_emas_histories',
            'title' => 'Harga Emas History',
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
                    'name' => 'harga_beli',
                    'data' => 'harga_beli',
                    'display_value' => 'harga_beli',
                    'type' => 'integer',
                    'validation' => 'required|integer|min:0',
                    'label' => 'Harga Beli',
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
                    'type' => 'timestamp',
                    'validation' => 'required|date',
                    'label' => 'Tanggal',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ]
                
            ]
        ];
    }

    public function hargaEmas()
    {
        return $this->belongsTo(HargaEmas::class, 'harga_emas_id');
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
