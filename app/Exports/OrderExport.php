<?php

namespace App\Exports;

use App\Model\Order;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrderExport implements FromCollection, WithMapping, WithHeadings, Responsable
{
    use Exportable;

    private $fileName = 'orders.xlsx';

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Order::all();
    }

    public function headings(): array
    {
        return [
            'Татуировка',
            'Стоимость ₽',
            'Покупатель',
            'Дата и время оформления заказа',
            'Дата и время записи',
            'Статус'
        ];
    }

    public function map($order): array
    {
        return [
            $order->tatoo->name,
            $order->tatoo->price,
            $this->getFullName($order->customer),
            Carbon::parse($order->created_at)->format('d.m.Y H:i'),
            Carbon::parse($order->note_date)->format('d.m.Y H:i'),
            $this->getStatus($order->status)
        ];
    }

    public function getFullName($customer)
    {
        return $customer->last_name . ' ' . $customer->first_name;
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
