<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')
                ->comment('имя работника');
            $table->date('birthday')
                ->comment('дата рождения');
            $table->text('description')
                ->comment('подробности');
            $table->string('url')
                ->comment('путь до фотографии');
            $table->unsignedInteger('appointment_id')
                ->nullable()
                ->comment('должность');
            $table->timestamps();
        });

        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
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
        if (Schema::hasColumn('employees', 'appointment_id'))
        {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropForeign(['appointment_id']);
            });
        }
        Schema::dropIfExists('employees');
    }
}
