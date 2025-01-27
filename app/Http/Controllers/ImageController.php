<?php

namespace App\Http\Controllers;

use App\Dtos\ImageStoreDto;
use App\Http\Requests\Image\ImageStoreRequest;
use App\Models\Travel;
use App\Services\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use OpenApi\Attributes as OA;

#[OA\Tag(name: "Images")]
class ImageController extends BaseController
{

    #[OA\Post(
        path: '/api/images',
        description: 'Allows updating images.',
        summary: 'Update image',
        security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
            mediaType: 'multipart/form-data',
            schema: new OA\Schema(
                properties: [
                    new OA\Property(property: 'imageable_type', type: 'string', example: Travel::class),
                    new OA\Property(property: 'imageable_id', type: 'integer', example: 1),
                    new OA\Property(property: 'image', description: 'Image file', type: 'string', format: 'binary'),
                ]
            )
        )
        ),
        tags: ['Images'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Image uploaded successfully',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'url', type: 'string', example: 'http://localhost/storage/images/gKlbUY4YuSJ4PttnzXiuf3cBTugJy5Yxqr9rd8Si.png'),

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
                                    property: 'image',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The image must be an image.')
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
    public function store(ImageStoreRequest $request, ImageService $imageService): JsonResponse
    {
        $dto = new ImageStoreDto(...$request->validated());
        $image = $imageService->store($dto);

        return response()->json(['url' => Storage::url($image->path)]);
    }
}
