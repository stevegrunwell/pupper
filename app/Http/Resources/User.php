<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'username'    => $this->username,
            'displayName' => $this->display_name,
            'url'         => route('users.show', ['user' => $this]),
            'avatar'      => [
                'thumbnail' => $this->resource->getAvatarUrl(100),
                'large'     => $this->resource->getAvatarUrl(720),
            ],
        ];
    }
}
