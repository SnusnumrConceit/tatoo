<?php

namespace App\Http\Resources\Admin\User;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Admin\Order\OrderCollection;

class UserDetail extends JsonResource
{
    public static $wrap = 'user';

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
            'full_name'     => $this->full_name,
            'email'         => $this->email,
            'birthday'      => $this->display_birthday,
            'created_at'    => $this->display_created_at,
            'permissions'   => $this->permissions,
            'orders'        => new OrderCollection($this->orders()->paginate())
        ];
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(200);
    }
}
