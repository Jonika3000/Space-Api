<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BodyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'description' => $this->description,
            'image_path' => $this->image_path,
            'galaxy' => new GalaxyResource($this->whenLoaded('galaxy')),
            'posts' => PostResource::collection($this->whenLoaded('posts'))
        ];
    }
}
