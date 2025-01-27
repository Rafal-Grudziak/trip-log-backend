<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProfileListResource extends JsonResource
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
            'bio' => $this->resource->bio,
            'friend_status' => $this->resource->getFriendshipStatus(),
            'received_request_id' => $this->resource->getReceivedFriendRequestId(),
        ];
    }
}
