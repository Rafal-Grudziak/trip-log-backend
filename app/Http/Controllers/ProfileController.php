<?php

namespace App\Http\Controllers;

use App\Http\DTOs\ProfileUpdateDto;
use App\Http\Resources\ProfileResource;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;


#[OA\Tag(name: "Profiles")]
class ProfileController extends BaseController
{

    #[OA\Get(
        path: '/api/profiles/{user}',
        description: 'Allows updating the user\'s profile including email, name, avatar, bio, and social media links.',
        summary: 'Update the user profile',
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
                        new OA\Property(property: 'avatar', type: 'string', example: 'https://example.com/avatar.jpg'),
                        new OA\Property(property: 'facebook_link', type: 'string', example: 'https://facebook.com/johndoe'),
                        new OA\Property(property: 'instagram_link', type: 'string', example: 'https://instagram.com/johndoe'),
                        new OA\Property(property: 'x_link', type: 'string', example: 'https://x.com/johndoe'),
                        new OA\Property(property: 'bio', type: 'string', example: 'Traveler and photographer'),
                        new OA\Property(property: 'travel_preferences', type: 'array', items: new OA\Items(type: 'string'), example: ['mountains', 'beaches']),
                        new OA\Property(property: 'trips_count', type: 'integer', example: 3),
                        new OA\Property(property: 'planned_trips_count', type: 'integer', example: 5),
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
            )
        ]
    )]
    public function show(User $user): JsonResponse
    {
        return response()->json(new ProfileResource($user));
    }

    #[OA\Put(
        path: '/api/profiles/update',
        description: 'Allows updating the user\'s profile including email, name, avatar, bio, and social media links.',
        summary: 'Update the user profile',
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
                        new OA\Property(property: 'travel_preferences', type: 'array', items: new OA\Items(type: 'string'), example: ['mountains', 'beaches', 'forests']),
                    ]
                )
            )
        ),
        tags: ['Profiles'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Profile updated successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'id', type: 'integer', example: 1),
                        new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                        new OA\Property(property: 'avatar', type: 'string', example: 'https://example.com/avatar.jpg'),
                        new OA\Property(property: 'facebook_link', type: 'string', example: 'https://facebook.com/johndoe'),
                        new OA\Property(property: 'instagram_link', type: 'string', example: 'https://instagram.com/johndoe'),
                        new OA\Property(property: 'x_link', type: 'string', example: 'https://x.com/johndoe'),
                        new OA\Property(property: 'bio', type: 'string', example: 'Traveler and photographer'),
                        new OA\Property(property: 'travel_preferences', type: 'array', items: new OA\Items(type: 'string'), example: ['mountains', 'beaches']),
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
            )
        ]
    )]
    public function update(Request $request, ProfileService $profileService): JsonResponse
    {
    dd($request->all());
        $user = auth()->user();
        $dto = new ProfileUpdateDto(...$request->validated());
        $updatedUser = $profileService->updateProfile($user, $dto, $request->hasFile('avatar') ? $request->file('avatar') : null);

        return response()->json(new ProfileResource($updatedUser));
    }

}
