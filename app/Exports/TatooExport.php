<?php

namespace App\Exports;

use App\Models\Tatoo;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TatooExport implements FromCollection, WithMapping, WithHeadings, Responsable
{
    use Exportable;

    private $fileName = 'tattoos.xlsx';

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tatoo::all();
    }

    public function headings() : array
    {
        return [
            'Название',
            'Стоимость',
            'Описание'
        ];
    }

    public function map($tattoo) : array
    {
        return [
            $tattoo->name,
            $tattoo->price,
            $tattoo->description
        ];
    }
}
