<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use OpenApi\Attributes as OA;


class AppVersionApi extends Controller
{
    protected AppVersion $model;


    public function __construct()
    {
        $this->model = new AppVersion();
    }


    #[OA\Get(
        path: '/app-versions',
        operationId: 'getAppVersionList',
        summary: 'Get list of app versions',
        description: 'Retrieves all app version data.',
        tags: ['App Versions'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'per_page',
                description: 'Number of items per page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 10)
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Page number',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'App Versions retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'App Versions retrieved successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'version', type: 'string', example: '1.0.0'),
                                    new OA\Property(property: 'release_date', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                    new OA\Property(property: 'changelog', type: 'string', example: 'Initial release.'),
                                    new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                    new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z')
                                ]
                            )
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - missing permission view-app-versions',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            )
        ]
    )]
    public function index(Request $request)
    {
        $per_page = $request->integer('per_page', 10);
        $page = $request->integer('page', 1);


        $hargaEmas = AppVersion::paginate($per_page, ['*'], 'page', $page);


        $data = $hargaEmas->getCollection()
                ->map(
                    fn(AppVersion $h) =>
                    $h->transformed()
                );

        $output = $hargaEmas->toArray();
        $output['data'] = $data;

        return $this->apiResponseOk(
            data: $output,
            message: 'App Versions retrieved successfully.'
        );
    }

    #[OA\Get(
        path: '/app-versions/{appVersion}',
        operationId: 'getAppVersionDetail',
        summary: 'Get app version detail',
        description: 'Retrieves a single app version along with its latest 20 price history records. Requires the show-app-versions permission.',
        tags: ['App Versions'],
        security: [['bearerAuth' => []]],
        parameters: [
            new OA\Parameter(
                name: 'appVersion',
                description: 'ID of the app version',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'App version detail retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'version', type: 'string', example: '1.0.0'),
                                new OA\Property(property: 'release_date', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z')
                                
                            ]
                        ),
                        
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - missing permission show-app-versions',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'App version not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'No query results for model [App\\Models\\AppVersion] 1')
                    ]
                )
            )
        ]
    )]
    public function show(AppVersion $appVersion)
    {
        $data = $appVersion;


        return $this->apiResponseOk(
            data: $data->transformed(),
            message: 'App version retrieved successfully.'
        );
    }


    #[OA\Post(
        path: '/app-versions',
        operationId: 'createAppVersion',
        summary: 'Create app version',
        description: 'Creates a new app version record. Requires the create-app-versions permission.',
        tags: ['App Versions'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['tipe', 'tipe_version', 'version', 'release_at'],
                properties: [
                    new OA\Property(property: 'tipe', type: 'string', example: 'iOS'),
                    new OA\Property(property: 'tipe_version', type: 'string', example: '1.0.0'),
                    new OA\Property(property: 'version', type: 'string', example: '1.0.0'),
                    new OA\Property(property: 'release_at', type: 'string', format: 'date', example: '2024-06-01')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'App version created successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'App version created successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'version', type: 'string', example: '1.0.0'),
                                new OA\Property(property: 'platform', type: 'string', example: 'iOS'),
                                new OA\Property(property: 'release_date', type: 'string', format: 'date', example: '2024-06-01')
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - missing permission create-app-versions',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The version field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'version',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The version field is required.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        //file handling
        if ($request->has('url_apk_file')) {
            // dd('masuk sini');

            //format file = tngold_{tipe}_{versi}.apk

            $file = $request->file('url_apk_file');
            $filename = 'tngold_' . $validatedData['tipe'] . '_' . $validatedData['version'] . '.' . $file->getClientOriginalExtension();
            $file->storeAs('apk_files', $filename, 'public');
            $validatedData['url_apk_file'] = $filename;
        }
        else {
            //jika tida ada maka tetap generate
            $validatedData['url_apk_file'] = 'tngold_' . $validatedData['tipe'] . '_' . $validatedData['version'] . '.apk';
        }

        $object = AppVersion::create($validatedData);

        return $this->apiResponseOk(
            data: $object->transformed(),
            message: 'App Version created successfully.'
        );
    }


    #[OA\Put(
        path: '/app-versions/{appVersion}',
        operationId: 'updateAppVersion',
        summary: 'Update app version',
        description: 'Updates an existing app version record. Requires the update-app-versions permission.',
        tags: ['App Versions'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['id', 'tipe', 'tipe_version', 'version', 'release_at'],
                properties: [
                    new OA\Property(property: 'id', type: 'integer', example: 1),
                    new OA\Property(property: 'tipe', type: 'string', example: 'iOS'),
                    new OA\Property(property: 'tipe_version', type: 'string', example: '1.0.0'),
                    new OA\Property(property: 'version', type: 'string', example: '1.0.0'),
                    new OA\Property(property: 'release_at', type: 'string', format: 'date', example: '2024-06-01')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'App version updated successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'App version updated successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'version', type: 'string', example: '1.0.0'),
                                new OA\Property(property: 'tipe', type: 'string', example: 'iOS'),
                                new OA\Property(property: 'release_at', type: 'string', format: 'date', example: '2024-06-01')
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthenticated',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - missing permission update-app-versions',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The version field is required.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'version',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The version field is required.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function update(Request $request, AppVersion $appVersion)
    {
        $validatedData = $request->validate([
            ...$this->model->getValidationRules()
        ]);

        //file handling
        if ($request->has('url_apk_file')) {
            // dd('masuk sini');

            //format file = tngold_{tipe}_{versi}.apk

            //delete old file if exists
            if ($appVersion->url_apk_file) {
                Storage::disk('public')->delete('apk_files/' . $appVersion->url_apk_file);
            }

            $file = $request->file('url_apk_file');
            $filename = 'tngold_' . $validatedData['tipe'] . '_' . $validatedData['version'] . '.' . $file->getClientOriginalExtension();
            $file->storeAs('apk_files', $filename, 'public');
            $validatedData['url_apk_file'] = $filename;
        }
        else {
            //jika tida ada maka tetap generate
            $validatedData['url_apk_file'] = 'tngold_' . $validatedData['tipe'] . '_' . $validatedData['version'] . '.apk';
        }

        $appVersion->update($validatedData);

        return $this->apiResponseOk(
            data: $appVersion->transformed(),
            message: 'App Version updated successfully.'
        );
    }

    #[OA\Delete(
        path: '/app-versions/{appVersion}',
        operationId: 'deleteAppVersion',
        summary: 'Delete app version',
        description: 'Deletes an existing app version record. Requires the delete-app-versions permission.',
        security: [
            ['bearerAuth' => []]
        ],
        tags: ['App Versions'],
        parameters: [
            new OA\Parameter(
                name: 'appVersion',
                in: 'path',
                required: true,
                description: 'ID of the app version to delete',
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'App version deleted successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'App Version deleted successfully.')
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: 'Forbidden - missing permission delete-app-versions',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            )
        ]
    )]
    public function destroy(AppVersion $appVersion)
    {
        $appVersion->delete();
        return $this->apiResponseOk(
            data: null,
            message: 'App Version deleted successfully.'
        );
    }
}
