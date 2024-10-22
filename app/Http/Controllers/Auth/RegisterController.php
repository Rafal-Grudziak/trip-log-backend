<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use OpenApi\Attributes as OA;


#[OA\Tag(name: "Auth")]
class RegisterController extends BaseController
{

     #[OA\Post(
         path: '/api/auth/register',
         summary: 'Register',
         requestBody: new OA\RequestBody(
             required: true,
             content: new OA\JsonContent(
                 properties: [
                     new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                     new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                     new OA\Property(property: 'password', type: 'string', example: 'password123'),
                     new OA\Property(property: 'password_confirmation', type: 'string', example: 'password123'),
                     new OA\Property(property: 'device_name', type: 'string', example: 'android'),
                 ]
             )
         ),
         tags: ['Auth'],
         responses: [
             new OA\Response(
                 response: 201,
                 description: 'Registration successful',
                 content: new OA\JsonContent(
                     properties: [
                         new OA\Property(property: 'token', type: 'string', example: 'token'),
                     ]
                 )
             ),
             new OA\Response(
                 response: 422,
                 description: 'Validation error'
             )
         ]
     )]
    public function register(Request $request): JsonResponse
     {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'device_name' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'token' => $token,
        ], 201);
    }

}
