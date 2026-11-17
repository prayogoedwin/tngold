<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


// this is not real Model, just a template structure for copy paste purposes
class Toko extends Model
{
    use SoftDeletes;
    protected $table = 'tokos';
    protected $fillable = [
        'name',
        'code',
        'address',
        'phone',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    protected $casts = [
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function structure()
    {
        return [
            'parent' => null,
            'table' => 'tokos',
            'title' => 'Toko',
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
                    'label' => 'Name',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'code',
                    'data' => 'code',
                    'display_value' => 'code',
                    'type' => 'string',
                    'validation' => 'nullable|string|max:255',
                    'label' => 'Code',
                    'required' => false,
                    'inTable' => false,
                    'inCreateForm' => false,
                    'inEditForm' => false,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'address',
                    'data' => 'address',
                    'display_value' => 'address',
                    'type' => 'string',
                    'validation' => 'nullable|string|max:255',
                    'label' => 'Address',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'phone',
                    'data' => 'phone',
                    'display_value' => 'phone',
                    'type' => 'string',
                    'validation' => 'nullable|string|max:255',
                    'label' => 'Phone',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                
            ]
        ];
    }


}
