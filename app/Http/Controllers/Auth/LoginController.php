<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
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
                description: 'Succces',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'token', type: 'string', example: 'token'),
                        new OA\Property(property: 'user'),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Invalid login credentials'
            )
        ]
    )]
    public function login(Request $request)
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
                'user' => new UserResource($user),
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid login credentials',
        ], 401);
    }


    #[OA\Post(
        path: '/api/auth/logout',
        summary: 'Logout',
        security: [['sanctum' => []]],
        tags: ['Auth'],
        parameters: [
            new OA\Parameter(
                name: 'Accept',
                in: 'header',
                required: true,
                schema: new OA\Schema(
                    type: 'string',
                    default: 'application/json'
                )
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successfully logged out'
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
            )
        ]
    )]
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ], 200);
    }

}
