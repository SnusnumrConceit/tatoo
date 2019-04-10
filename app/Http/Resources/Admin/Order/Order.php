<?php

namespace App\Http\Resources\Admin\Order;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class Order extends JsonResource
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
            'id'            =>  $this->id,
            'status'        =>  $this->status,
            'status_type'   =>  $this->getStatus($this->status),
            'note_date'     =>  $this->parseDate($this->note_date),
            'tatoo'         =>  $this->tatoo->name,
            'price'         =>  $this->tatoo->price,
            'customer'      =>  $this->getFullName($this->customer),
            'created_at'    =>  $this->parseDate($this->created_at)
        ];
    }

    public function getFullName($customer)
    {
        return $customer->last_name . ' ' . $customer->first_name;
    }

    public function parseDate($date)
    {
        return Carbon::parse($date)->format('d-m-Y H:i');
    }

    public function getStatus($status)
    {
        switch ($status) {
            case 1: return 'Отказано'; break;
            case 2: return 'Предзаказ'; break;
            case 3: return 'Завершён'; break;
            default: break;
        }
    }
}
