<?php

namespace App\Exports;

use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UserExport implements FromCollection, WithHeadings, WithMapping, Responsable
{

    use Exportable;

    private $fileName = 'users.xlsx';

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::with('orders')->get();
    }

    public function headings() : array
    {
        return [
            'Фамилия',
            'Имя',
            'Дата рождения',
            'Email',
            'Зарегистрирован',
            'Заказы'
        ];
    }

    public function map($user) : array
    {
        return [
            $user->last_name,
            $user->first_name,
            Carbon::parse($user->birthday)->format('d.m.Y'),
            $user->email,
            Carbon::parse($user->created_at)->format('d.m.Y H:i'),
            $this->getTattoos($user->orders),
        ];
    }

    public function getTattoos($tattoos)
    {
        $res = '';
        foreach ($tattoos as $tattoo) {
            $res .= $tattoo->name . ' ';
        }
        return $res;
    }
}
