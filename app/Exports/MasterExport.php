<?php

namespace App\Exports;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class MasterExport implements FromCollection, WithHeadings, WithMapping, Responsable
{
    use Exportable;

    private $fileName = 'masters.xlsx';
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Employee::with(['appointment', 'tatoos'])->get();
    }

    public function headings() : array
    {
        return [
            'Имя',
            'Должность',
            'Дата рождения',
            'Описание',
            'Татуировки'
        ];
    }

    public function map($master) : array
    {
        return [
            $master->name,
            $master->appointment->name,
            Carbon::parse($master->birthday)->format('d.m.Y'),
            $master->description,
            $this->getTattoos($master->tatoos)
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
