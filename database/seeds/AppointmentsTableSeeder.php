<?php

use \App\Models\Appointment;
use Illuminate\Database\Seeder;

class AppointmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Appointment::updateOrCreate(['name' => 'мастер'], ['name' => 'мастер']);
		Appointment::updateOrCreate(['name' => 'ученик'], ['name' => 'ученик']);
    }
}
