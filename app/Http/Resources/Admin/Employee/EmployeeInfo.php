<?php

namespace App\Http\Resources\Admin\Employee;

use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeInfo extends JsonResource
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
            'name'          => $this->name,
            'description'   =>  $this->description,
            'appointment'   =>  $this->appointment->name,
            'url'           =>  $this->setPath($this->url),
            'tatoos'        =>  $this->tatoos
        ];
    }

    public function setPath($url)
    {
        return str_replace('public', 'storage', $url);
    }
}
