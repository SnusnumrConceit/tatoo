<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersPermissions extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_permissions', function (Blueprint $table) {
			$table->integer('user_id')->unsigned();
			$table->string('permission_name', 50);
		
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->onUpdate('cascade')
				->onDelete('cascade');
		
			$table->foreign('permission_name')
				->references('name')
				->on('permissions')
				->onUpdate('cascade')
				->onDelete('cascade');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (Schema::hasColumn('users_permissions', 'user_id')) {
			Schema::table('users_permissions', function (Blueprint $table) {
				$table->dropForeign(['user_id']);
			});
		}
	
		if (Schema::hasColumn('users_permissions', 'permission_name')) {
			Schema::table('users_permissions', function (Blueprint $table) {
				$table->dropForeign(['permission_name']);
			});
		}
	
		Schema::dropIfExists('users_permissions');
	}
}
