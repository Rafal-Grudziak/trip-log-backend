<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlaceShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'name' => $this->resource->name,
            'description' => $this->resource->description,
            'category' => new EnumResource($this->resource->category),
            'longitude' => $this->resource->longitude,
            'latitude' => $this->resource->latitude,
            'images' => ImageResource::collection($this->resource->images),
        ];
    }
}
