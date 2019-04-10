<?php

namespace App\Http\Resources\Admin\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderTatooExtends extends JsonResource
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
            'id'   =>  $this->id,
            'name' =>  $this->formatTatooData($this)
        ];
    }

    public function formatTatooData($tatoo)
    {
        return $tatoo->name . ' ' . $tatoo->price;
    }
}
