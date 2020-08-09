<?php

use App\Models\Appointment;
use Faker\Generator as Faker;

$factory->define(Appointment::class, function (Faker $faker) {
	return [
		'name' => $faker->text(25)
	];
});
