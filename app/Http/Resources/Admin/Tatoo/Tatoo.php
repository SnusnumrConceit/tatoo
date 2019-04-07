<?php

namespace App\Http\Resources\Admin\Tatoo;

use Illuminate\Http\Resources\Json\JsonResource;

class Tatoo extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
