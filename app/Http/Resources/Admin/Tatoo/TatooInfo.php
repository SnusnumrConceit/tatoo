<?php

namespace App\Http\Resources\Admin\Tatoo;

use Illuminate\Http\Resources\Json\JsonResource;

class TatooInfo extends JsonResource
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
            'name'        => $this->name,
            'description' => $this->description,
            'price'       => $this->price,
            'url'         => $this->setPath($this->url),
            'masters'     => $this->masters
        ];
    }

    public function setPath($url)
    {
        return str_replace('public', 'storage', $url);
    }
}
