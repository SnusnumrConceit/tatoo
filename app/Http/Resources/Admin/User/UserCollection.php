<?php

namespace App\Http\Resources\Admin\User;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
{
    public static $wrap = 'users';

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->toArray();
    }

    public function withResponse($request, $response)
    {
        $response->setStatusCode(200);
    }
}
