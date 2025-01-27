<?php

namespace App\Http\Controllers;


use App\Dtos\TravelStoreDto;
use App\Http\Requests\Travel\TravelDeleteRequest;
use App\Http\Requests\Travel\TravelListRequest;
use App\Http\Requests\Travel\TravelShowRequest;
use App\Http\Requests\Travel\TravelStoreRequest;
use App\Http\Requests\Travel\TravelUpdateRequest;
use App\Http\Resources\TravelListResource;
use App\Http\Resources\TravelShowResource;
use App\Http\Responses\PaginatedResponse;
use App\Models\Travel;
use App\Models\User;
use App\Services\TravelService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Travels")]
class TravelController extends BaseController
{
//    #[OA\Get(
//        path: '/api/travels',
//        summary: 'Get list of travels',
//        security: [['sanctum' => []]],
//        tags: ['Travels'],
//        parameters: [
//            new OA\Parameter(
//                name: 'page',
//                description: 'Page number',
//                in: 'query',
//                required: false,
//                schema: new OA\Schema(type: 'integer', default: 1)
//            ),
//        ],
//        responses: [
//            new OA\Response(
//                response: 200,
//                description: 'List of travels',
//                content: new OA\JsonContent(
//                    properties: [
//                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(
//                            properties: [
//                                new OA\Property(property: 'id', type: 'integer', example: 1),
//                                new OA\Property(property: 'name', type: 'string', example: 'Trip to Paris'),
//                                new OA\Property(property: 'description', type: 'string', example: 'Amazing journey'),
//                                new OA\Property(property: 'from', type: 'string', format: 'date', example: '2024-03-20'),
//                                new OA\Property(property: 'to', type: 'string', format: 'date', example: '2024-03-25'),
//                                new OA\Property(property: 'longitude', type: 'number', format: 'double', example: 2.3522),
//                                new OA\Property(property: 'latitude', type: 'number', format: 'double', example: 48.8566),
//                                new OA\Property(property: 'favourite', type: 'boolean', example: false),
//                            ]
//                        )),
//                        new OA\Property(property: 'links', type: 'object'),
//                        new OA\Property(property: 'meta', type: 'object'),
//                    ]
//                )
//            ),
//        ]
//    )]
//    public function index(TravelService $travelService)
//    {
//        $travels = $travelService->getAll();
//        return TravelResource::collection($travels);
//    }

    #[OA\Get(
        path: '/api/travels/{travel}',
        description: 'Show travel details.',
        summary: 'Show travel details',
        security: [['sanctum' => []]],
        tags: ['Travels'],
        parameters: [
            new OA\Parameter(
                name: 'travel',
                description: 'Travel ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Travel returned successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', description: 'ID of the travel', type: 'integer', example: 1),
                        new OA\Property(property: 'name', description: 'Name of the travel', type: 'string', example: 'Mountain Adventure'),
                        new OA\Property(property: 'description', description: 'Description of the travel', type: 'string', example: 'A trip to explore the mountains'),
                        new OA\Property(property: 'from', description: 'Start date of the travel', type: 'string', format: 'date', example: '2024-12-01'),
                        new OA\Property(property: 'to', description: 'End date of the travel', type: 'string', format: 'date', example: '2024-12-10'),
                        new OA\Property(property: 'longitude', description: 'Longitude of the location', type: 'number', format: 'float', example: 23.634501),
                        new OA\Property(property: 'latitude', description: 'Latitude of the location', type: 'number', format: 'float', example: -102.552784),
                        new OA\Property(property: 'favourite', description: 'Whether the travel is marked as favourite', type: 'boolean', example: true),
                    new OA\Property(
                        property: 'places',
                        description: 'List of places visited during the travel',
                        type: 'array',
                        items: new OA\Items(
                        properties: [
                            new OA\Property(property: 'name', description: 'Name of the place', type: 'string', example: 'Mount Everest Base Camp'),
                            new OA\Property(property: 'description', description: 'Description of the place', type: 'string', example: 'Base camp for Mount Everest climbers'),
                            new OA\Property(
                                property: 'category',
                                description: 'Category of the place',
                                properties: [
                                    new OA\Property(property: 'id', description: 'Category ID', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', description: 'Category name', type: 'string', example: 'Mountains')
                                ],
                                type: 'object'
                            ),
                            new OA\Property(property: 'longitude', description: 'Longitude of the place', type: 'number', format: 'float', example: 86.925026),
                            new OA\Property(property: 'latitude', description: 'Latitude of the place', type: 'number', format: 'float', example: 27.988056),
                        ],
                        type: 'object'
                        )
                    ),
                        new OA\Property(property: 'created', description: 'Record creation', type: 'string', example: 'One minute ago'),
                    ]
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
    public function show(TravelShowRequest $request, Travel $travel): JsonResponse
    {
        return response()->json(new TravelShowResource($travel));
    }



    #[OA\Post(
        path: '/api/travels',
        description: 'Creates a new travel record.',
        summary: 'Create Travel',
        security: [['sanctum' => []]],

        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    required: ['name', 'from', 'to', 'longitude', 'latitude'],
                    properties: [
                        new OA\Property(property: 'name', description: 'Name of the travel', type: 'string', example: 'Mountain Adventure'),
                        new OA\Property(property: 'description', description: 'Description of the travel', type: 'string', example: 'A trip to explore the mountains'),
                        new OA\Property(property: 'from', description: 'Start date of the travel', type: 'string', format: 'date', example: '2024-12-01'),
                        new OA\Property(property: 'to', description: 'End date of the travel', type: 'string', format: 'date', example: '2024-12-10'),
                        new OA\Property(property: 'longitude', description: 'Longitude of the location', type: 'number', format: 'float', example: 23.634501),
                        new OA\Property(property: 'latitude', description: 'Latitude of the location', type: 'number', format: 'float', example: -102.552784),
                        new OA\Property(
                            property: 'places',
                            description: 'List of places visited during the travel',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'name', description: 'Name of the place', type: 'string', example: 'Mount Everest Base Camp'),
                                    new OA\Property(property: 'description', description: 'Description of the place', type: 'string', example: 'Base camp for Mount Everest climbers'),
                                    new OA\Property(property: 'category_id', description: 'Category ID of the place', type: 'integer', example: 1),
                                    new OA\Property(property: 'longitude', description: 'Longitude of the place', type: 'number', format: 'float', example: 86.925026),
                                    new OA\Property(property: 'latitude', description: 'Latitude of the place', type: 'number', format: 'float', example: 27.988056),
                                ],
                                type: 'object'
                            )
                        )
                    ]
                )
            )
        ),
        tags: ['Travels'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Travel created successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', description: 'ID of the travel', type: 'integer', example: 1),
                        new OA\Property(property: 'name', description: 'Name of the travel', type: 'string', example: 'Mountain Adventure'),
                        new OA\Property(property: 'description', description: 'Description of the travel', type: 'string', example: 'A trip to explore the mountains'),
                        new OA\Property(property: 'from', description: 'Start date of the travel', type: 'string', format: 'date', example: '2024-12-01'),
                        new OA\Property(property: 'to', description: 'End date of the travel', type: 'string', format: 'date', example: '2024-12-10'),
                        new OA\Property(property: 'longitude', description: 'Longitude of the location', type: 'number', format: 'float', example: 23.634501),
                        new OA\Property(property: 'latitude', description: 'Latitude of the location', type: 'number', format: 'float', example: -102.552784),
                        new OA\Property(property: 'favourite', description: 'Whether the travel is marked as favourite', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'places',
                            description: 'List of places visited during the travel',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', description: 'ID of the place', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', description: 'Name of the place', type: 'string', example: 'Mount Everest Base Camp'),
                                    new OA\Property(property: 'description', description: 'Description of the place', type: 'string', example: 'Base camp for Mount Everest climbers'),
                                    new OA\Property(
                                        property: 'category',
                                        description: 'Category of the place',
                                        properties: [
                                            new OA\Property(property: 'id', description: 'Category ID', type: 'integer', example: 1),
                                            new OA\Property(property: 'name', description: 'Category name', type: 'string', example: 'Mountains')
                                        ],
                                        type: 'object'
                                    ),
                                    new OA\Property(property: 'longitude', description: 'Longitude of the place', type: 'number', format: 'float', example: 86.925026),
                                    new OA\Property(property: 'latitude', description: 'Latitude of the place', type: 'number', format: 'float', example: 27.988056),
                                ],
                                type: 'object'
                            )
                        ),
                        new OA\Property(property: 'created', description: 'Record creation', type: 'string', example: 'One minute ago'),
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
                            type: 'object',
                            example: [
                                'name' => ['The name field is required.'],
                                'from' => ['The from field must be a valid date.']
                            ],
                            additionalProperties: true
                        )
                    ]
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
    public function store(TravelStoreRequest $request, TravelService $travelService): JsonResponse
    {
        $dto = new TravelStoreDto(...$request->validated());

        $createdTravel = $travelService->storeTravel($dto);

        return response()->json(new TravelShowResource($createdTravel));
    }

    #[OA\Put(
        path: '/api/travels/{travel}/update',
        description: 'Update a travel.',
        summary: 'Update Travel',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'application/json',
                schema: new OA\Schema(
                    required: ['name', 'from', 'to', 'longitude', 'latitude'],
                    properties: [
                        new OA\Property(property: 'name', description: 'Name of the travel', type: 'string', example: 'Mountain Adventure'),
                        new OA\Property(property: 'description', description: 'Description of the travel', type: 'string', example: 'A trip to explore the mountains'),
                        new OA\Property(property: 'from', description: 'Start date of the travel', type: 'string', format: 'date', example: '2024-12-01'),
                        new OA\Property(property: 'to', description: 'End date of the travel', type: 'string', format: 'date', example: '2024-12-10'),
                        new OA\Property(property: 'longitude', description: 'Longitude of the location', type: 'number', format: 'float', example: 23.634501),
                        new OA\Property(property: 'latitude', description: 'Latitude of the location', type: 'number', format: 'float', example: -102.552784),
                        new OA\Property(
                            property: 'places',
                            description: 'List of places visited during the travel',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', description: 'Id of the place', type: 'integer', example: '1'),
                                    new OA\Property(property: 'name', description: 'Name of the place', type: 'string', example: 'Mount Everest Base Camp'),
                                    new OA\Property(property: 'description', description: 'Description of the place', type: 'string', example: 'Base camp for Mount Everest climbers'),
                                    new OA\Property(property: 'category_id', description: 'Category ID of the place', type: 'integer', example: 1),
                                    new OA\Property(property: 'longitude', description: 'Longitude of the place', type: 'number', format: 'float', example: 86.925026),
                                    new OA\Property(property: 'latitude', description: 'Latitude of the place', type: 'number', format: 'float', example: 27.988056),
                                ],
                                type: 'object'
                            )
                        )
                    ]
                )
            )
        ),
        tags: ['Travels'],
        parameters: [
            new OA\Parameter(
                name: 'travel',
                description: 'ID of the travel to update',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Travel updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', description: 'ID of the travel', type: 'integer', example: 1),
                        new OA\Property(property: 'name', description: 'Name of the travel', type: 'string', example: 'Mountain Adventure'),
                        new OA\Property(property: 'description', description: 'Description of the travel', type: 'string', example: 'A trip to explore the mountains'),
                        new OA\Property(property: 'from', description: 'Start date of the travel', type: 'string', format: 'date', example: '2024-12-01'),
                        new OA\Property(property: 'to', description: 'End date of the travel', type: 'string', format: 'date', example: '2024-12-10'),
                        new OA\Property(property: 'longitude', description: 'Longitude of the location', type: 'number', format: 'float', example: 23.634501),
                        new OA\Property(property: 'latitude', description: 'Latitude of the location', type: 'number', format: 'float', example: -102.552784),
                        new OA\Property(property: 'favourite', description: 'Whether the travel is marked as favourite', type: 'boolean', example: true),
                        new OA\Property(
                            property: 'places',
                            description: 'List of places visited during the travel',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', description: 'ID of the place', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', description: 'Name of the place', type: 'string', example: 'Mount Everest Base Camp'),
                                    new OA\Property(property: 'description', description: 'Description of the place', type: 'string', example: 'Base camp for Mount Everest climbers'),
                                    new OA\Property(
                                        property: 'category',
                                        description: 'Category of the place',
                                        properties: [
                                            new OA\Property(property: 'id', description: 'Category ID', type: 'integer', example: 1),
                                            new OA\Property(property: 'name', description: 'Category name', type: 'string', example: 'Mountains')
                                        ],
                                        type: 'object'
                                    ),
                                    new OA\Property(property: 'longitude', description: 'Longitude of the place', type: 'number', format: 'float', example: 86.925026),
                                    new OA\Property(property: 'latitude', description: 'Latitude of the place', type: 'number', format: 'float', example: 27.988056),
                                ],
                                type: 'object'
                            )
                        ),
                        new OA\Property(property: 'created', description: 'Record creation', type: 'string', example: 'One minute ago'),
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
                            type: 'object',
                            example: [
                                'name' => ['The name field is required.'],
                                'from' => ['The from field must be a valid date.']
                            ],
                            additionalProperties: true
                        )
                    ]
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
    public function update(TravelUpdateRequest $request, TravelService $travelService, Travel $travel): JsonResponse
    {
        $dto = new TravelStoreDto(...$request->validated());
        $updatedTravel = $travelService->updateTravel($dto, $travel);

        return response()->json(new TravelShowResource($updatedTravel));
    }

    #[OA\Delete(
        path: '/api/travels/{travel}/delete',
        summary: 'Delete travel',
        security: [['sanctum' => []]],
        tags: ['Travels'],
        parameters: [
            new OA\Parameter(
                name: 'travel',
                description: 'Travel ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Travel deleted successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Travel deleted successfully')
                    ]
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
            ),
            new OA\Response(response: 404, description: 'Travel not found')
        ]
    )]
    public function destroy(TravelDeleteRequest $request, Travel $travel,  TravelService $travelService): JsonResponse
    {
        $travelService->deleteTravel($travel);
        return response()->json(['message' => 'Travel deleted successfully']);
    }

    #[OA\patch(
        path: '/api/travels/{travel}/toggle-favourite',
        summary: 'Toggle favourite',
        security: [['sanctum' => []]],
        tags: ['Travels'],
        parameters: [
            new OA\Parameter(
                name: 'travel',
                description: 'Travel ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Travel updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'favourite', description: 'Whether the travel is marked as favourite', type: 'boolean', example: true),
                    ]
                )
            ),
            new OA\Response(response: 404, description: 'Travel not found')
        ]
    )]
    public function toggleFavourite(Travel $travel, TravelService $travelService): JsonResponse
    {
        $travel = $travelService->toggleFavourite($travel);
        return response()->json(['favourite' => $travel->favourite]);
    }

    #[OA\Get(
        path: '/api/travels/user/{user}',
        description: 'Show travels of specified user.',
        summary: 'Show travels of specified user',
        security: [['sanctum' => []]],
        tags: ['Travels'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'User ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Select page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: '')
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
                                        new OA\Property(property: 'id', description: 'ID of the travel', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', description: 'Name of the travel', type: 'string', example: 'Mountain Adventure'),
                                        new OA\Property(property: 'description', description: 'Description of the travel', type: 'string', example: 'A trip to explore the mountains'),
                                        new OA\Property(property: 'from', description: 'Start date of the travel', type: 'string', format: 'date', example: '2024-12-01'),
                                        new OA\Property(property: 'to', description: 'End date of the travel', type: 'string', format: 'date', example: '2024-12-10'),
                                        new OA\Property(property: 'longitude', description: 'Longitude of the location', type: 'number', format: 'float', example: 23.634501),
                                        new OA\Property(property: 'latitude', description: 'Latitude of the location', type: 'number', format: 'float', example: -102.552784),
                                        new OA\Property(property: 'favourite', description: 'Whether the travel is marked as favourite', type: 'boolean', example: true),
                                        new OA\Property(property: 'created', description: 'Record creation', type: 'string', example: 'One minute ago'),
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
    public function usersTravels(TravelListRequest $request, User $user): JsonResponse
    {
        return PaginatedResponse::format($user->travels()->paginate(10), TravelListResource::class);
    }

    #[OA\Get(
        path: '/api/travels/user/{user}/planned',
        description: 'Show planned travels of specified user.',
        summary: 'Show planned travels of specified user',
        security: [['sanctum' => []]],
        tags: ['Travels'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'User ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Select page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: '')
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
                                        new OA\Property(property: 'id', description: 'ID of the travel', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', description: 'Name of the travel', type: 'string', example: 'Mountain Adventure'),
                                        new OA\Property(property: 'description', description: 'Description of the travel', type: 'string', example: 'A trip to explore the mountains'),
                                        new OA\Property(property: 'from', description: 'Start date of the travel', type: 'string', format: 'date', example: '2024-12-01'),
                                        new OA\Property(property: 'to', description: 'End date of the travel', type: 'string', format: 'date', example: '2024-12-10'),
                                        new OA\Property(property: 'longitude', description: 'Longitude of the location', type: 'number', format: 'float', example: 23.634501),
                                        new OA\Property(property: 'latitude', description: 'Latitude of the location', type: 'number', format: 'float', example: -102.552784),
                                        new OA\Property(property: 'favourite', description: 'Whether the travel is marked as favourite', type: 'boolean', example: true),
                                        new OA\Property(property: 'created', description: 'Record creation', type: 'string', example: 'One minute ago'),
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
    public function usersPlannedTravels(TravelListRequest $request, User $user): JsonResponse
    {
        return PaginatedResponse::format($user->travels()->planned()->paginate(10), TravelListResource::class);
    }

    #[OA\Get(
        path: '/api/travels/user/{user}/finished',
        description: 'Show finished travels of specified user.',
        summary: 'Show finished travels of specified user',
        security: [['sanctum' => []]],
        tags: ['Travels'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'User ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Select page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: '')
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
                                        new OA\Property(property: 'id', description: 'ID of the travel', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', description: 'Name of the travel', type: 'string', example: 'Mountain Adventure'),
                                        new OA\Property(property: 'description', description: 'Description of the travel', type: 'string', example: 'A trip to explore the mountains'),
                                        new OA\Property(property: 'from', description: 'Start date of the travel', type: 'string', format: 'date', example: '2024-12-01'),
                                        new OA\Property(property: 'to', description: 'End date of the travel', type: 'string', format: 'date', example: '2024-12-10'),
                                        new OA\Property(property: 'longitude', description: 'Longitude of the location', type: 'number', format: 'float', example: 23.634501),
                                        new OA\Property(property: 'latitude', description: 'Latitude of the location', type: 'number', format: 'float', example: -102.552784),
                                        new OA\Property(property: 'favourite', description: 'Whether the travel is marked as favourite', type: 'boolean', example: true),
                                        new OA\Property(property: 'created', description: 'Record creation', type: 'string', example: 'One minute ago'),
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
    public function usersFinishedTravels(TravelListRequest $request, User $user): JsonResponse
    {
        return PaginatedResponse::format($user->travels()->finished()->paginate(10), TravelListResource::class);
    }

    #[OA\Get(
        path: '/api/travels/user/{user}/favourite',
        description: 'Show favourite travels of specified user.',
        summary: 'Show favourite travels of specified user',
        security: [['sanctum' => []]],
        tags: ['Travels'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'User ID',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
            new OA\Parameter(
                name: 'page',
                description: 'Select page',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'integer', example: '')
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
                                        new OA\Property(property: 'id', description: 'ID of the travel', type: 'integer', example: 1),
                                        new OA\Property(property: 'name', description: 'Name of the travel', type: 'string', example: 'Mountain Adventure'),
                                        new OA\Property(property: 'description', description: 'Description of the travel', type: 'string', example: 'A trip to explore the mountains'),
                                        new OA\Property(property: 'from', description: 'Start date of the travel', type: 'string', format: 'date', example: '2024-12-01'),
                                        new OA\Property(property: 'to', description: 'End date of the travel', type: 'string', format: 'date', example: '2024-12-10'),
                                        new OA\Property(property: 'longitude', description: 'Longitude of the location', type: 'number', format: 'float', example: 23.634501),
                                        new OA\Property(property: 'latitude', description: 'Latitude of the location', type: 'number', format: 'float', example: -102.552784),
                                        new OA\Property(property: 'favourite', description: 'Whether the travel is marked as favourite', type: 'boolean', example: true),
                                        new OA\Property(property: 'created', description: 'Record creation', type: 'string', example: 'One minute ago'),
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
    public function usersFavouriteTravels(TravelListRequest $request, User $user): JsonResponse
    {
        return PaginatedResponse::format($user->travels()->favourite()->paginate(10), TravelListResource::class);
    }

}
