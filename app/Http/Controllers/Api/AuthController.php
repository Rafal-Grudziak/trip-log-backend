<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use OpenApi\Attributes as OA;



 #[OA\Tag(name: "Auth")]
class AuthController extends Controller
{
     #[OA\Post(
         path: '/api/login',
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
                         new OA\Property(property: 'token', type: 'string', example: 'your_jwt_token'),
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
                'user' => $user,
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid login credentials',
        ], 401);
    }

     #[OA\Post(
         path: '/api/register',
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
                         new OA\Property(property: 'token', type: 'string', example: 'your_jwt_token'),
                         new OA\Property(property: 'user'),
                     ]
                 )
             ),
             new OA\Response(
                 response: 422,
                 description: 'Validation error'
             )
         ]
     )]
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed',
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
            'user' => $user,
        ], 201);
    }

     #[OA\Post(
         path: '/api/logout',
         summary: 'Logout',
         security: [['sanctum' => []]],
         tags: ['Auth'],
         responses: [
             new OA\Response(
                 response: 200,
                 description: 'Successfully logged out'
             ),
             new OA\Response(
                 response: 401,
                 description: 'Unauthorized'
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
