<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TravelListResource extends JsonResource
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
            'from' => $this->resource->from->format('Y-m-d'),
            'to' => $this->resource->to->format('Y-m-d'),
            'longitude' => $this->resource->longitude,
            'latitude' => $this->resource->latitude,
            'favourite' => $this->resource->favourite,
            'created' => Carbon::parse($this->resource->created_at)->diffForHumans(),
            'image' => new ImageResource($this->resource->images?->last()),
        ];
    }
}
