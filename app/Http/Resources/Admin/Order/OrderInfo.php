<?php

namespace App\Http\Resources\Admin\Order;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderInfo extends JsonResource
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
            'customer'  =>  $this->getFullName($this->customer),
            'tatoo'     =>  $this->tatoo,
            'price'     =>  $this->tatoo->price,
            'status'    =>  $this->getStatus($this->status),
            'url'       =>  str_replace('public', 'storage', $this->tatoo->url),
            'note_date' =>  $this->convertDate($this->note_date),
            'created_at'=>  $this->convertDate($this->createad_at)
        ];
    }

    public function getStatus($status)
    {
        switch($status) {
            case 1: return 'Отказано'; break;
            case 2: return 'Предзаказ'; break;
            case 3: return 'Завершён'; break;
            default: break;
        }
    }

    public function getFullName($customer)
    {
        return $customer->last_name . ' ' . $customer->first_name;
    }

    public function convertDate($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i');
    }
}
