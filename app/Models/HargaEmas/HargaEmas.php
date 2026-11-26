<?php

namespace App\Models\HargaEmas;

use App\Models\HargaEmas\HargaEmasHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class HargaEmas extends Model
{
    use SoftDeletes;
    protected $table = 'harga_emas';
    protected $fillable = [
        'emas_asal_id',
        'name',
        'sumber',
        'kadar',
        'satuan',
        'jumlah',
        'harga_beli', ////harga beli per jumlah/satuan
        'keterangan',
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
            //auto generate name based on emas_asal_id, kadar, and satuan
            $model->name = \App\Models\HargaEmas\HargaEmasEmasAsal::find($model->emas_asal_id)->name 
                . ' ' . $model->kadar . 'K ' . $model->satuan;
            $model->name = 'temp';
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
            'table' => 'harga-emas',
            'title' => 'Harga Emas',
            'table_has_actions' => true,
            'table_has_timestamps' => true,
            'index_has_download' => false,

            'columns' => [
                [
                    'name' => 'emas_asal_id',
                    'data' => 'emas_asal.name',
                    'display_value' => 'emasAsal.name',
                    'type' => 'select',
                    'validation' => 'required|exists:harga_emas_emas_asal,id',
                    'label' => 'Emas Asal',
                    'options' => \App\Models\HargaEmas\HargaEmasEmasAsal::all()->pluck('name', 'id')->toArray(),
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'sumber',
                    'data' => 'sumber',
                    'display_value' => 'sumber',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Sumber',
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
                    'name' => 'keterangan',
                    'data' => 'keterangan',
                    'display_value' => 'keterangan',
                    'type' => 'text',
                    'validation' => 'nullable|string|max:255',
                    'label' => 'Keterangan',
                    'required' => false,
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
            'emas_asal' => $this->emas_asal,
            'sumber' => $this->sumber,
            'kadar' => $this->kadar,
            'satuan' => 'per' . $this->jumlah . ' ' . $this->satuan,
            'jumlah' => $this->jumlah,
            'harga_beli' => $this->harga_beli,
            'keterangan' => $this->keterangan,
            'updated_at' => $this->updated_at,
        ];
    }

    public function hargaEmasHistories()
    {
        return $this->hasMany(HargaEmasHistory::class, 'harga_emas_id');
    }


    public function recordHargaHistory()
    {
        $this->hargaEmasHistories()->create([
            'tanggal' => now(),
            'harga_beli' => $this->harga_beli,
        ]);
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
