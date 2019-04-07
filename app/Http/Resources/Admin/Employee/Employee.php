<?php

namespace App\Http\Resources\Admin\Employee;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Employee extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'birthday' => $this->convertDate($this->birthday),
            'created_at' => $this->convertDate($this->created_at),
            'appointment' => $this->appointment->name
        ];
    }

    public function convertDate($date)
    {
        return Carbon::parse($date)->format('d.m.Y');
    }
}
