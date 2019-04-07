<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMasterTatoosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_tatoos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('employee_id')
                ->comment('работник');
            $table->unsignedInteger('tatoo_id')
                ->comment('тату');
        });

        Schema::table('master_tatoos', function (Blueprint $table) {
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('cascade');

            $table->foreign('tatoo_id')
                ->references('id')
                ->on('tatoos')
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
        if (Schema::hasColumn('master_tatoos', 'tatoo_id'))
        {
            Schema::table('master_tatoos', function (Blueprint $table) {
                $table->dropForeign(['tatoo_id']);
            });
        }

        if (Schema::hasColumn('master_tatoos', 'employee_id'))
        {
            Schema::table('master_tatoos', function (Blueprint $table) {
                $table->dropForeign(['employee_id']);
            });
        }

        Schema::dropIfExists('master_tatoos');
    }
}
