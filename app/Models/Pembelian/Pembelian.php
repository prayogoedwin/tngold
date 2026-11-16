<?php

namespace App\Models\Pembelian;

use App\Models\Pembelian\PembelianDetail;
use App\Models\Toko;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pembelian extends Model
{
    use SoftDeletes;
    protected $table = 'pembelians';
    protected $fillable = [
        'no_invoice',
        'nama_pembeli',
        'no_hp_pembeli',
        'keterangan',
        'status',
        'tanggal',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $casts = [
        'tanggal' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();

                //format no_invoice = P[tahun][bulan]#[id]
                $no_invoice = 'temp';
                $model->no_invoice = $no_invoice;
            }
        });
        static::created(function ($model) {
            if (auth()->check()) {
                $model->created_by = auth()->id();
                $model->updated_by = auth()->id();

                //format no_invoice = P[tahun][bulan]#[id]
                $no_invoice = 'P' . now()->format('Ym') . '#' . $model->id;
                $model->no_invoice = $no_invoice;
                $model->save();
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
            'table' => 'pembelians',
            'title' => 'Pembelian',
            'table_has_actions' => true,
            'table_has_timestamps' => true,

            'columns' => [
                [
                    'name' => 'no_invoice',
                    'data' => 'no_invoice',
                    'display_value' => 'no_invoice',
                    'type' => 'string',
                    'validation' => 'nullable|string|max:255',
                    'label' => 'No Invoice',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => false,
                    'inEditForm' => false,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'nama_pembeli',
                    'data' => 'nama_pembeli',
                    'display_value' => 'nama_pembeli',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Nama Pembeli',
                    'required' => false,
                    'inTable' => false,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],[
                    'name' => 'no_hp_pembeli',
                    'data' => 'no_hp_pembeli',
                    'display_value' => 'no_hp_pembeli',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'No HP Pembeli',
                    'required' => false,
                    'inTable' => false,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'tanggal',
                    'data' => 'tanggal',
                    'display_value' => 'tanggal',
                    'type' => 'date',
                    'default' => now(),
                    'validation' => 'required|date',
                    'label' => 'Tanggal',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                
                [
                    'name' => 'status',
                    'data' => 'status',
                    'display_value' => 'status',
                    'type' => 'enum',
                    'validation' => 'required|in:draft,completed,cancelled',
                    'label' => 'Status',
                    'options' => ['draft', 'completed', 'cancelled'],
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
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                
            ]
        ];
    }

    public function details()
    {
        return $this->hasMany(PembelianDetail::class, 'pembelian_id');
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
