<?php

namespace App\Http\Resources\Order;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderForm extends JsonResource
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
            'customer'  => $this->customer,
            'tatoo'     =>  $this->tatoo,
            'id'        => $this->id,
            'price'     =>  $this->price,
            'status'    => $this->status,
            'tatoo_id'  =>  $this->tatoo_id,
            'note_date' =>  Carbon::parse($this->note_date)->format('Y-m-d'),
            'note_time' =>  $this->getTime($this->note_date),
            'user_id'   =>  $this->user_id,
            'master'    =>  $this->master
        ];
    }

    public function getTime($date)
    {
        $tmp = Carbon::parse($date)->format('H:i');
        $time = (object) [];
        $time->HH = $tmp[0].$tmp[1];
        $time->mm = $tmp[3].$tmp[4];
        return  $time;
    }
}
