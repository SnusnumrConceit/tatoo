<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id');
			$table->string('last_name', 50)
				->comment('user surname');
			
			$table->string('first_name', 50)
				->comment('user name');
			
			$table->date('birthday');
			
			$table->string('email')
				->unique();
			
			$table->string('password');
			
			$table->string('role_name')
				->nullable();
			
			$table->string('api_token', 100)
				->nullable();
			
			$table->rememberToken();
			$table->timestamps();
	
			$table->foreign('role_name')
				->references('name')
				->on('roles')
				->onUpdate('cascade')
				->onDelete('set null');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (Schema::hasColumn('users', 'role_name')) {
			Schema::table('users', function (Blueprint $table) {
				$table->dropForeign(['role_name']);
			});
		}
		
		Schema::dropIfExists('users');
	}
}