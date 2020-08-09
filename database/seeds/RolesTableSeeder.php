<?php

use \App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Role::updateOrCreate(['slug' => 'admin'],		['name' => 'administrator',	'slug' => 'admin',		'is_protected' => true ]);
		Role::updateOrCreate(['slug' => 'customer'],	['name' => 'customer',		'slug' => 'customer',	'is_protected' => false ]);
	}
}