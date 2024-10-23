<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;


#[OA\Tag(name: "Auth")]
class LoginController extends BaseController
{

    #[OA\Post(
        path: '/api/auth/login',
        summary: 'Login',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', example: 'password123'),
                    new OA\Property(property: 'device_name', type: 'string', example: 'android'),
                ]
            )
        ),
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string', example: 'xxxxxxxxxxxxxx'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Error: Unprocessable Content',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The email field is required. (and 2 more errors)'),
                        new OA\Property(
                            property: 'errors',
                            properties: [
                                new OA\Property(
                                    property: 'email',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The email field is required.')
                                ),
                                new OA\Property(
                                    property: 'password',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The password field is required.')
                                ),
                            ],
                            type: 'object'
                        ),
                    ]
                )
            )
        ]
    )]
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken($request->device_name)->plainTextToken;

            return response()->json([
                'token' => $token,
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid login credentials.',
        ], 401);
    }


    #[OA\Post(
        path: '/api/auth/logout',
        summary: 'Logout',
        security: [['sanctum' => []]],
        tags: ['Auth'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Successfully logged out.'),
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
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ], 200);
    }

}
