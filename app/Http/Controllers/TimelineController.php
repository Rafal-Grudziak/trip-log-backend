<?php

namespace App\Http\Controllers;

use App\Dtos\TimelineIndexDto;
use App\Http\Requests\Timeline\TimelineIndexRequest;
use App\Http\Resources\TimelineResource;
use App\Http\Responses\PaginatedResponse;
use App\Services\TimelineService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Timeline")]
class TimelineController extends BaseController
{

    #[OA\Get(
        path: '/api/timeline',
        summary: 'Get related travels',
        security: [['sanctum' => []]],
        tags: ['Timeline'],
        parameters: [
            new OA\Parameter(
                name: 'page',
                description: 'Select page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: '')
            ),
            new OA\Parameter(
                name: 'date_from',
                description: 'Date from filter.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: '12-12-2024')
            ),
            new OA\Parameter(
                name: 'date_to',
                description: 'Date to filter.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: '19-12-2024')
            ),
            new OA\Parameter(
                name: 'sort_direction',
                description: 'Sorting direction.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'asc')
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Travels returned successfully',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                type: 'array',
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: 'travel', properties: [
                                            new OA\Property(property: 'id', description: 'ID of the travel', type: 'integer', example: 1),
                                            new OA\Property(property: 'name', description: 'Name of the travel', type: 'string', example: 'Mountain Adventure'),
                                            new OA\Property(property: 'description', description: 'Description of the travel', type: 'string', example: 'A trip to explore the mountains'),
                                            new OA\Property(property: 'from', description: 'Start date of the travel', type: 'string', format: 'date', example: '2024-12-01'),
                                            new OA\Property(property: 'to', description: 'End date of the travel', type: 'string', format: 'date', example: '2024-12-10'),
                                            new OA\Property(property: 'longitude', description: 'Longitude of the location', type: 'number', format: 'float', example: 23.634501),
                                            new OA\Property(property: 'latitude', description: 'Latitude of the location', type: 'number', format: 'float', example: -102.552784),
                                            new OA\Property(property: 'favourite', description: 'Whether the travel is marked as favourite', type: 'boolean', example: true),
                                            new OA\Property(property: 'created', description: 'Record creation', type: 'string', example: 'One minute ago'),
                                        ], type: 'object'),
                                        new OA\Property(property: 'user', properties: [
                                            new OA\Property(property: 'id', description: 'ID of the user', type: 'integer', example: 2),
                                            new OA\Property(property: 'email', description: 'Email of the user', type: 'string', example: 'user2@example.com'),
                                            new OA\Property(property: 'name', description: 'Name of the user', type: 'string', example: 'Jan'),
                                            new OA\Property(property: 'avatar', description: 'Avatar of the user', type: 'string', example: null),
                                        ], type: 'object'),
                                    ],
                                    type: 'object'
                                )
                            ),
                            new OA\Property(
                                property: 'meta',
                                properties: [
                                    new OA\Property(property: 'current_page', type: 'integer', example: 1),
                                    new OA\Property(property: 'last_page', type: 'integer', example: 5),
                                    new OA\Property(property: 'per_page', type: 'integer', example: 10),
                                    new OA\Property(property: 'total', type: 'integer', example: 50),
                                ],
                                type: 'object'
                            ),
                        ]
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.')
                    ]
                )
            )
        ]
    )]
    public function index(TimelineIndexRequest $request, TimelineService $timelineService): JsonResponse
    {

        $dto = new TimelineIndexDto(...$request->validated());
        return PaginatedResponse::format($timelineService->filter($dto)->paginate(10), TimelineResource::class);

    }

}
