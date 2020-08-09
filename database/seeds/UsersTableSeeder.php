<?php

use App\User;
use \Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		User::updateOrCreate(
			['email' => 'admin@test.com'],
			[
				'email' => 'admin@test.com',
				'last_name' => '',
				'birthday' => now()->format('Y-m-d'),
				'first_name' => 'Administrator',
				'password' => bcrypt('password'),
				'api_token' => Str::random(50),
				'role_name' => 'administrator'
			]
		);
	}
}
