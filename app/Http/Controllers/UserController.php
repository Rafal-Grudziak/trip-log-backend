<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "User")]
class UserController extends BaseController
{

    #[OA\Get(
        path: '/api/users/me',
        description: 'Returns the currently authenticated user’s profile data',
        summary: 'Get current authenticated user',
        security: [['sanctum' => []]],
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'avatar', type: 'string', nullable: true, example: 'avatars/user-avatar.jpg'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            )
        ]
    )]
    public function show(Request $request): JsonResponse
    {
        $user = auth()->user();
        return response()->json(new UserResource($user));
    }


    #[OA\Patch(
        path: '/api/users/me/update-password',
        description: 'Allows the authenticated user to update their password by providing the old password and a new one.',
        summary: 'Update the authenticated user\'s password',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'old_password', description: 'The current password of the user', type: 'string', example: 'password123'),
                    new OA\Property(property: 'password', description: 'The new password to set', type: 'string', example: 'newPassword123'),
                    new OA\Property(property: 'password_confirmation', description: 'Confirmation of the new password', type: 'string', example: 'newPassword123'),
                ]
            )
        ),
        tags: ['Users'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Password updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Password updated successfully')
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'The old password is incorrect',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The old password is incorrect')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'message',
                            type: 'string',
                            example: 'The given data was invalid.'
                        ),
                        new OA\Property(
                            property: 'errors',
                            properties: [
                                new OA\Property(
                                    property: 'old_password',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The old password field is required.')
                                ),
                                new OA\Property(
                                    property: 'password',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The password field is required.')
                                )
                            ],
                            type: 'object'
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
                    ]
                )
            )
        ]
    )]
    public function updatePassword(Request $request): JsonResponse
    {
        $request->validate([
            'old_password' => 'required|string',
            'password' => 'required|string|confirmed|min:8',
        ] );

        $user = auth()->user();

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json(['message' => 'The old password is incorrect'], 400);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return response()->json(['message' => 'Password updated successfully'], 200);
    }

}
