<?php

namespace App\Http\Resources\Admin\User;

use App\Http\Resources\Admin\Order\OrderCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserInfo extends JsonResource
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
            'full_name' => $this->getFullName($this),
            'orders' => new OrderCollection($this->orders)
        ];
    }

    public function getFullName($user)
    {
        return $user->last_name . ' ' . $user->first_name;
    }
}
