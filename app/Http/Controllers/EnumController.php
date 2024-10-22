<?php

namespace App\Http\Controllers;

use App\Http\Resources\EnumResource;
use App\Models\TravelPreference;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Enums")]
class EnumController extends BaseController
{

    #[OA\Get(
        path: '/api/enums/travel-preferences',
        summary: 'Get Travel Preferences',
        security: [['sanctum' => []]],
        tags: ['Enums'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Success',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'id', type: 'integer', example: 1),
                            new OA\Property(property: 'name', type: 'string', example: 'Mountains')
                        ],
                    ),
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
    public function getTravelPreferences(Request $request)
    {
        return response()->json(EnumResource::collection(TravelPreference::all()));
    }
}
