<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePermissionsTable extends Migration
{

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('permissions', function (Blueprint $table) {
			$table->string('name', 50)->unique();
			$table->boolean('is_group')->default(0);
			$table->string('parent', 50)->nullable()->index();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		if (Schema::hasColumn('permissions', 'parent'))
		{
			Schema::table('permissions', function($table)
			{
				$table->dropIndex(['parent']);
			});
		}
		
		Schema::dropIfExists('permissions');
	}
}
