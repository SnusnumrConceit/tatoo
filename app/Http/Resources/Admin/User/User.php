<?php

namespace App\Http\Resources\Admin\User;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'id'            => $this->id,
            'name'          => $this->full_name,
            'email'         => $this->email,
            'birthday'      => $this->display_birthday,
            'created_at'    => $this->display_created_at,
        ];
    }
}
