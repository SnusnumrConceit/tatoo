<?php

use \App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::updateOrCreate(['name' => 'full'],			['name' => 'full',			'is_group' => true]);
        
        Permission::updateOrCreate(['name' => 'appointments'],			['name' => 'appointments',			'is_group' => true]);
        Permission::updateOrCreate(['name' => 'appointments_create'],	['name' => 'appointments_create',	'is_group' => false, 'parent' => 'appointments']);
        Permission::updateOrCreate(['name' => 'appointments_edit'],		['name' => 'appointments_edit',		'is_group' => false, 'parent' => 'appointments']);
        Permission::updateOrCreate(['name' => 'appointments_delete'],	['name' => 'appointments_delete',	'is_group' => false, 'parent' => 'appointments']);
        Permission::updateOrCreate(['name' => 'appointments_view'],		['name' => 'appointments_view',		'is_group' => false, 'parent' => 'appointments']);
    
        Permission::updateOrCreate(['name' => 'employees'],				['name' => 'employees',				'is_group' => true]);
        Permission::updateOrCreate(['name' => 'employees_create'],		['name' => 'employees_create',		'is_group' => false, 'parent' => 'employees']);
        Permission::updateOrCreate(['name' => 'employees_edit'],		['name' => 'employees_edit',		'is_group' => false, 'parent' => 'employees']);
        Permission::updateOrCreate(['name' => 'employees_delete'],		['name' => 'employees_delete',		'is_group' => false, 'parent' => 'employees']);
        Permission::updateOrCreate(['name' => 'employees_view'],		['name' => 'employees_view',		'is_group' => false, 'parent' => 'employees']);
    
        Permission::updateOrCreate(['name' => 'tattoos'],				['name' => 'tattoos',				'is_group' => true]);
        Permission::updateOrCreate(['name' => 'tattoos_create'],		['name' => 'tattoos_create',		'is_group' => false, 'parent' => 'tattoos']);
        Permission::updateOrCreate(['name' => 'tattoos_edit'],			['name' => 'tattoos_edit',			'is_group' => false, 'parent' => 'tattoos']);
        Permission::updateOrCreate(['name' => 'tattoos_delete'],		['name' => 'tattoos_delete',		'is_group' => false, 'parent' => 'tattoos']);
        Permission::updateOrCreate(['name' => 'tattoos_view'],			['name' => 'tattoos_view',			'is_group' => false, 'parent' => 'tattoos']);
    
        Permission::updateOrCreate(['name' => 'orders'],				['name' => 'orders',				'is_group' => true]);
        Permission::updateOrCreate(['name' => 'orders_create'],			['name' => 'orders_create',			'is_group' => false, 'parent' => 'orders']);
        Permission::updateOrCreate(['name' => 'orders_edit'],			['name' => 'orders_edit',			'is_group' => false, 'parent' => 'orders']);
        Permission::updateOrCreate(['name' => 'orders_delete'],			['name' => 'orders_delete',			'is_group' => false, 'parent' => 'orders']);
        Permission::updateOrCreate(['name' => 'orders_view'],			['name' => 'orders_view',			'is_group' => false, 'parent' => 'orders']);
        
        Permission::updateOrCreate(['name' => 'roles'],					['name' => 'roles',					'is_group' => true]);
        Permission::updateOrCreate(['name' => 'roles_create'],			['name' => 'roles_create',			'is_group' => false, 'parent' => 'roles']);
        Permission::updateOrCreate(['name' => 'roles_edit'],			['name' => 'roles_edit',			'is_group' => false, 'parent' => 'roles']);
        Permission::updateOrCreate(['name' => 'roles_delete'],			['name' => 'roles_delete',			'is_group' => false, 'parent' => 'roles']);
        Permission::updateOrCreate(['name' => 'roles_view'],			['name' => 'roles_view',			'is_group' => false, 'parent' => 'roles']);
    
        Permission::updateOrCreate(['name' => 'users'],					['name' => 'users',					'is_group' => true]);
        Permission::updateOrCreate(['name' => 'users_create'],			['name' => 'users_create',			'is_group' => false, 'parent' => 'users']);
        Permission::updateOrCreate(['name' => 'users_edit'],			['name' => 'users_edit',			'is_group' => false, 'parent' => 'users']);
        Permission::updateOrCreate(['name' => 'users_delete'],			['name' => 'users_delete',			'is_group' => false, 'parent' => 'users']);
        Permission::updateOrCreate(['name' => 'users_view'],			['name' => 'users_view',			'is_group' => false, 'parent' => 'users']);
    }
}
