<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProfileResource extends JsonResource
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
            'avatar' => $this->resource->avatar ? Storage::url($this->resource->avatar) : null,
            'facebook_link' => $this->resource->facebook_link,
            'instagram_link' => $this->resource->instagram_link,
            'x_link' => $this->resource->x_link,
            'bio' => $this->resource->bio,
            'travel_preferences' => EnumResource::collection($this->resource->travelPreferences),
            'finished_travels_count' => $this->resource->travels()->finished()->count(),
            'planned_travels_count' => $this->resource->travels()->planned()->count(),
        ];
    }
}
