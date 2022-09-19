<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{

    public static $wrap = 'user';

    public function with($request)
    {
        return [
            'success' => true,
        ];
    }

    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
                'id' => $this->resource->id,
                'name' => $this->resource->name,
                'email' => $this->resource->email,
                'phone' => $this->resource->phone,
                'position' => $this->resource->position->name,
                'position_id' => $this->resource->position_id,
                'photo' => asset('storage/' . $this->resource->photo),
        ];
    }
}
