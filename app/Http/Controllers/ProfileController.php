<?php

namespace App\Http\Controllers;

use App\Dtos\ProfileSearchDto;
use App\Dtos\ProfileUpdateDto;
use App\Http\Requests\Profile\ProfileSearchRequest;
use App\Http\Requests\Profile\ProfileUpdateRequest;
use App\Http\Resources\ProfileListResource;
use App\Http\Resources\ProfileResource;
use App\Http\Responses\PaginatedResponse;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;


#[OA\Tag(name: "Profiles")]
class ProfileController extends BaseController
{

    #[OA\Get(
        path: '/api/profiles/{user}',
        description: 'Get user\'s profile information including email, name, avatar, bio, and social media links.',
        summary: 'Get user\'s profile',
        security: [['sanctum' => []]],
        tags: ['Profiles'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'ID of the user to retrieve',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful profile retrieval',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'avatar', type: 'string', example: 'http://localhost/storage/avatars/gKlbUY4YuSJ4PttnzXiuf3cBTugJy5Yxqr9rd8Si.png'),
                        new OA\Property(property: 'facebook_link', type: 'string', example: 'https://facebook.com/johndoe'),
                        new OA\Property(property: 'instagram_link', type: 'string', example: 'https://instagram.com/johndoe'),
                        new OA\Property(property: 'x_link', type: 'string', example: 'https://x.com/johndoe'),
                        new OA\Property(property: 'bio', type: 'string', example: 'Traveler and photographer'),
                        new OA\Property(
                            property: 'travel_preferences',
                            type: 'array',
                            items: new OA\Items(
                            properties: [
                                new OA\Property(property: 'id', type: 'integer', example: 5),
                                new OA\Property(property: 'name', type: 'string', example: 'Deserts')
                            ]),
                        ),
                        new OA\Property(property: 'finished_travels_count', type: 'integer', example: 3),
                        new OA\Property(property: 'planned_travels_count', type: 'integer', example: 5),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'User not found',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'User not found')
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
    public function show(User $user): JsonResponse
    {
        return response()->json(new ProfileResource($user));
    }

    #[OA\Post(
        path: '/api/profiles/{user}/update',
        description: 'Allows updating the user\'s profile including email, name, avatar, bio, and social media links.',
        summary: 'Update user\'s profile',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: 'multipart/form-data',
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: 'email', type: 'string', example: 'user@example.com'),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'avatar', description: 'Avatar image file (optional)', type: 'string', format: 'binary'),
                        new OA\Property(property: 'facebook_link', type: 'string', example: 'https://facebook.com/johndoe'),
                        new OA\Property(property: 'instagram_link', type: 'string', example: 'https://instagram.com/johndoe'),
                        new OA\Property(property: 'x_link', type: 'string', example: 'https://x.com/johndoe'),
                        new OA\Property(property: 'bio', type: 'string', example: 'Traveler and photographer'),
                        new OA\Property(
                            property: 'travel_preferences[]',
                            type: 'array',
                            items: new OA\Items(type: 'integer', example: 1),
                        ),
                    ]
                )
            )
        ),
        tags: ['Profiles'],
        parameters: [
            new OA\Parameter(
                name: 'user',
                description: 'ID of the user to retrieve',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer', example: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profile updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'avatar', type: 'string', example: 'http://localhost/storage/avatars/gKlbUY4YuSJ4PttnzXiuf3cBTugJy5Yxqr9rd8Si.png'),
                        new OA\Property(property: 'facebook_link', type: 'string', example: 'https://facebook.com/johndoe'),
                        new OA\Property(property: 'instagram_link', type: 'string', example: 'https://instagram.com/johndoe'),
                        new OA\Property(property: 'x_link', type: 'string', example: 'https://x.com/johndoe'),
                        new OA\Property(property: 'bio', type: 'string', example: 'Traveler and photographer'),
                        new OA\Property(
                            property: 'travel_preferences',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 5),
                                    new OA\Property(property: 'name', type: 'string', example: 'Deserts')
                                ]),
                        ),
                        new OA\Property(property: 'trips_count', type: 'integer', example: 3),
                        new OA\Property(property: 'planned_trips_count', type: 'integer', example: 5),
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
                                    property: 'email',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The email field is required.')
                                ),
                                new OA\Property(
                                    property: 'name',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The name must not exceed 32 characters.')
                                ),
                                new OA\Property(
                                    property: 'avatar',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The avatar must be an image.')
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
    public function update(User $user, ProfileUpdateRequest $request, ProfileService $profileService): JsonResponse
    {
        if($user->id !== auth()->id()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        $dto = new ProfileUpdateDto(...$request->validated());
        $updatedUser = $profileService->updateProfile($user, $dto);

        return response()->json(new ProfileResource($updatedUser));
    }

    #[OA\Get(
        path: '/api/profiles/search',
        description: 'Search for profiles.',
        summary: 'Search profiles',
        security: [['sanctum' => []]],
        tags: ['Profiles'],
        parameters: [
            new OA\Parameter(
                name: 'query',
                description: 'Search query to filter profiles.',
                in: 'query',
                required: false,
                schema: new OA\Schema(type: 'string', example: 'John')
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
                description: 'Successful profiles retrieval with pagination',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: 'id', type: 'integer', example: 1),
                                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                                    new OA\Property(property: 'avatar', type: 'string', example: 'http://localhost/storage/avatars/gKlbUY4YuSJ4PttnzXiuf3cBTugJy5Yxqr9rd8Si.png'),
                                    new OA\Property(property: 'bio', type: 'string', example: 'Traveler and photographer'),
                                    new OA\Property(property: 'friend_status', type: 'integer', example: 1),
                                    new OA\Property(property: 'received_request_id', type: 'integer', example: 1),
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
                    ],
                    type: 'object'
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
            ),
        ]
    )]
    public function search(ProfileSearchRequest $request, ProfileService $profileService): JsonResponse
    {
        $dto = new ProfileSearchDto(...$request->validated());
        $results = $profileService->search($dto);

        return PaginatedResponse::format($results, ProfileListResource::class);
    }

}
