<?php

namespace App\Exports;

use App\Models\Appointment;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AppointmentExport implements FromCollection, WithHeadings, WithMapping, Responsable
{
    use Exportable;

    private $fileName = 'appointments.xlsx';

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Appointment::all();
    }

    public function headings() : array
    {
        return [
          'Наименование'
        ];
    }

    public function map($appointment) : array
    {
        return [
            $appointment->name
        ];
    }
}
