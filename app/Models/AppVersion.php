<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AppVersion extends Model
{
    use SoftDeletes;
    protected $table = 'app_versions';
    protected $fillable = [
        'tipe',
        'tipe_version',
        'version',
        'kode_akses',
        'url_apk_file',
        'catatan',
        'release_at',
        'release_by',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function structure()
    {
        return [
            'parent' => null,
            'table' => 'app-versions',
            'title' => 'App Version',
            'table_has_actions' => true,
            'table_has_timestamps' => true,
            'index_has_download' => false,


            'columns' => [
                [
                    'name' => 'tipe',
                    'data' => 'tipe',
                    'display_value' => 'tipe',
                    'type' => 'enum',
                    'validation' => 'required|in:"Pembelian","Internal"',
                    'label' => 'Tipe',
                    'options' => ['Pembelian', 'Internal'],
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'tipe_version',
                    'data' => 'tipe_version',
                    'display_value' => 'tipe_version',
                    'type' => 'enum',
                    'validation' => 'required|in:"debug","rilis"',
                    'label' => 'Tipe Version',
                    'options' => ['debug', 'rilis'],
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'version',
                    'data' => 'version',
                    'display_value' => 'version',
                    'type' => 'string',
                    'validation' => 'required|string|max:255',
                    'label' => 'Version',
                    'required' => true,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'kode_akses',
                    'data' => 'kode_akses',
                    'display_value' => 'kode_akses',
                    'type' => 'string',
                    'validation' => 'nullable|string|max:255',
                    'label' => 'Kode Akses',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => false,
                    'inEditForm' => false,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'url_apk_file',
                    'data' => 'url_apk_file',
                    'display_value' => 'url_apk_file',
                    'type' => 'string',
                    'validation' => 'nullable|string|max:255',
                    'label' => 'URL APK File',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => false,
                    'inEditForm' => false,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'catatan',
                    'data' => 'catatan',
                    'display_value' => 'catatan',
                    'type' => 'string',
                    'validation' => 'nullable|string|max:255',
                    'label' => 'Catatan',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'release_at',
                    'data' => 'release_at',
                    'display_value' => 'release_at',
                    'type' => 'date',
                    'validation' => 'required|date',
                    'label' => 'Release At',
                    'required' => false,
                    'inTable' => true,
                    'inCreateForm' => true,
                    'inEditForm' => true,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'released_by',
                    'data' => 'released_by.name',
                    'display_value' => 'releasedBy.name',
                    'type' => 'select',
                    'validation' => 'nullable|exists:users,id',
                    'label' => 'Released By',
                    'options' => User::all()->pluck('name', 'id')->toArray(),
                    'required' => false,
                    'inTable' => false,
                    'inCreateForm' => false,
                    'inEditForm' => false,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'created_by',
                    'data' => 'created_by.name',
                    'display_value' => 'createdBy.name',
                    'type' => 'select',
                    'validation' => 'nullable|exists:users,id',
                    'label' => 'Created By',
                    'options' => User::all()->pluck('name', 'id')->toArray(),
                    'required' => false,
                    'inTable' => false,
                    'inCreateForm' => false,
                    'inEditForm' => false,
                    'inShowPage' => true,
                ],
                [
                    'name' => 'updated_by',
                    'data' => 'updated_by.name',
                    'display_value' => 'updatedBy.name',
                    'type' => 'select',
                    'validation' => 'nullable|exists:users,id',
                    'label' => 'Updated By',
                    'options' => User::all()->pluck('name', 'id')->toArray(),
                    'required' => false,
                    'inTable' => false,
                    'inCreateForm' => false,
                    'inEditForm' => false,
                    'inShowPage' => true,
                ],
                
            ]
        ];
    }

    //untuk keluaran api
    public function transformed()
    {
        
        return [
            'id' => $this->id,
            // etc
        ];
    }

    public function latestRelease()
    {
        return $this->hasOne(AppVersion::class)->latestOfMany();
    }

    public function releasedBy()
    {
        return $this->belongsTo(User::class, 'released_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
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
