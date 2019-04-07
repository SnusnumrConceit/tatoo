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
                ->comment('фамилия');
            $table->string('first_name', 50)
                ->comment('имя');
            $table->date('birthday')
                ->comment('дата рождения');
            $table->string('email')
                ->comment('электронная почта')
                ->unique();
            $table->string('password')
                ->comment('пароль');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
