<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Auth")]
class ProfileController extends Controller
{
    #[OA\Get(
        path: '/api/profile',
        summary: 'Get User Profile',
        security: [['sanctum' => []]],
        tags: ['Profile'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent()
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized'
            )
        ]
    )]
    public function profile(Request $request)
    {
        $user = Auth::user();
        return response()->json($user);
    }
}
