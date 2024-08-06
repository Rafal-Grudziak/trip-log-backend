<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Auth")]
class ProfileController extends BaseController
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
    public function show(Request $request)
    {
        $user = Auth::user();
        return response()->json($user);
    }
}
