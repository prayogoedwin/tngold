<?php

namespace App\Models\HargaEmas;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HargaEmasKadar extends Model
{
    use SoftDeletes;
    protected $table = 'harga_emas_kadars';
    protected $fillable = [
        'name',
        'created_by',
        'updated_by',
        'deleted_by',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'kadar' => 'integer',
        'jumlah' => 'integer',
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
            $model->recordHargaHistory();
        });

        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
        static::updated(function ($model) {
            // History hanya dibuat jika harga_beli berubah
            if ($model->wasChanged('harga_beli')) {
                $model->recordHargaHistory();
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
            'table' => 'harga-emas-kadars',
            'title' => 'Kadar',
            'table_has_actions' => true,
            'table_has_timestamps' => true,
            'index_has_download' => false,

            'columns' => [
                [
                    'name' => 'name',
                    'data' => 'name',
                    'display_value' => 'name',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Nama',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                

            ]
        ];
    }

    //untuk response api
    public function transformed()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
        ];
    }

    public function hargaEmas()
    {
        return $this->hasMany(HargaEmas::class, 'kadar_id');
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
