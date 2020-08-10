<?php

namespace App\Http\Resources\Admin\User;

use Illuminate\Http\Resources\Json\JsonResource;

class UserVuex extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'full_name' => $this->full_name,
            'role' => $this->role_name
        ];
    }
}
