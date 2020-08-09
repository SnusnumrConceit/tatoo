<?php

use \App\Models\Role;
use Illuminate\Database\Seeder;

class BaseRolesPermissionsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Role::whereSlug('admin')->first()->permissions()->sync(['full']);
		
		Role::whereSlug('customer')->first()->permissions()->sync([
			'tattoos_view',
			'employees_view',
			'orders_create',
			'orders_view'
		]);
	}
}
