<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

class UserController extends Controller
{
    #[OA\Get(
        path: '/api/users',
        operationId: 'getUsers',
        summary: 'Get list of users',
        description: 'Retrieves a paginated list of users along with their roles and permissions. Only accessible by users with the admin role.',
        tags: ['User Management'],
        security: [['bearerAuth' => []]],
        parameters: [
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
                description: 'Users retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                new OA\Property(
                                    property: 'data',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                            new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                                            new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                            new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                            new OA\Property(
                                                property: 'roles',
                                                type: 'array',
                                                items: new OA\Items(
                                                    type: 'object',
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                                        new OA\Property(property: 'name', type: 'string', example: 'admin'),
                                                        new OA\Property(
                                                            property: 'permissions',
                                                            type: 'array',
                                                            items: new OA\Items(
                                                                type: 'object',
                                                                properties: [
                                                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                                                    new OA\Property(property: 'name', type: 'string', example: 'manage-users')
                                                                ]
                                                            )
                                                        )
                                                    ]
                                                )
                                            )
                                        ]
                                    )
                                ),
                                new OA\Property(property: 'first_page_url', type: 'string', example: 'http://localhost/api/users?page=1'),
                                new OA\Property(property: 'from', type: 'integer', example: 1),
                                new OA\Property(property: 'last_page', type: 'integer', example: 3),
                                new OA\Property(property: 'last_page_url', type: 'string', example: 'http://localhost/api/users?page=3'),
                                new OA\Property(property: 'next_page_url', type: 'string', nullable: true, example: 'http://localhost/api/users?page=2'),
                                new OA\Property(property: 'path', type: 'string', example: 'http://localhost/api/users'),
                                new OA\Property(property: 'per_page', type: 'integer', example: 15),
                                new OA\Property(property: 'prev_page_url', type: 'string', nullable: true, example: null),
                                new OA\Property(property: 'to', type: 'integer', example: 15),
                                new OA\Property(property: 'total', type: 'integer', example: 45)
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
                description: 'Forbidden - user does not have admin role',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Forbidden')
                    ]
                )
            )
        ]
    )]
    public function index(): JsonResponse
    {
        $users = User::with('roles.permissions')->paginate(15);

        return response()->json([
            'data' => $users
        ]);
    }

    #[OA\Get(
        path: '/api/me',
        operationId: 'getAuthenticatedUser',
        summary: 'Get authenticated user',
        description: 'Retrieves the profile of the currently authenticated user along with their roles and permissions.',
        tags: ['User Management'],
        security: [['bearerAuth' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'User profile retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                        new OA\Property(
                            property: 'roles',
                            type: 'array',
                            items: new OA\Items(
                                type: 'object',
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'admin'),
                                    new OA\Property(
                                        property: 'permissions',
                                        type: 'array',
                                        items: new OA\Items(
                                            type: 'object',
                                            properties: [
                                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                                new OA\Property(property: 'name', type: 'string', example: 'manage-users')
                                            ]
                                        )
                                    )
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
            )
        ]
    )]
    public function me(Request $request): JsonResponse
    {
        return response()->json($request->user()->load('roles.permissions'));
    }

    #[OA\Post(
        path: '/api/update-password',
        operationId: 'updatePassword',
        summary: 'Update user password',
        description: 'Updates the password of the currently authenticated user. Requires the current password for verification and a confirmation of the new password.',
        tags: ['User Management'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                type: 'object',
                required: ['current_password', 'new_password', 'new_password_confirmation'],
                properties: [
                    new OA\Property(property: 'current_password', type: 'string', format: 'password', example: 'oldPassword123'),
                    new OA\Property(property: 'new_password', type: 'string', format: 'password', minLength: 8, example: 'newPassword123'),
                    new OA\Property(property: 'new_password_confirmation', type: 'string', format: 'password', example: 'newPassword123')
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Password updated successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Password updated successfully.'),
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 1),
                                new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                new OA\Property(property: 'email', type: 'string', format: 'email', example: 'john@example.com'),
                                new OA\Property(property: 'created_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', example: '2026-07-01T00:00:00.000000Z'),
                                new OA\Property(
                                    property: 'roles',
                                    type: 'array',
                                    items: new OA\Items(
                                        type: 'object',
                                        properties: [
                                            new OA\Property(property: 'id', type: 'integer', example: 1),
                                            new OA\Property(property: 'name', type: 'string', example: 'admin'),
                                            new OA\Property(
                                                property: 'permissions',
                                                type: 'array',
                                                items: new OA\Items(
                                                    type: 'object',
                                                    properties: [
                                                        new OA\Property(property: 'id', type: 'integer', example: 1),
                                                        new OA\Property(property: 'name', type: 'string', example: 'manage-users')
                                                    ]
                                                )
                                            )
                                        ]
                                    )
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Current password is incorrect',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Current password is incorrect')
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
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The new password field confirmation does not match.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'new_password',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The new password field confirmation does not match.')
                                )
                            ]
                        )
                    ]
                )
            )
        ]
    )]
    public function updatePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 400);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password updated successfully.',
            'data' => $user->load('roles.permissions')
        ]);
    }
}