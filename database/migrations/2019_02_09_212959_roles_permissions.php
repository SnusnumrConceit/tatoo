<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RolesPermissions extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles_permissions', function (Blueprint $table) {
			$table->string('role_name', 30);
			$table->string('permission_name', 50);
			
			$table->foreign('role_name')
				->references('name')
				->on('roles')
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
		if (Schema::hasColumn('roles_permissions', 'role_name')) {
			Schema::table('roles_permissions', function (Blueprint $table) {
				$table->dropForeign(['role_name']);
			});
		}
	
		if (Schema::hasColumn('roles_permissions', 'permission_name')) {
			Schema::table('roles_permissions', function (Blueprint $table) {
				$table->dropForeign(['permission_name']);
			});
		}
		
		Schema::dropIfExists('roles_permissions');
	}
}
