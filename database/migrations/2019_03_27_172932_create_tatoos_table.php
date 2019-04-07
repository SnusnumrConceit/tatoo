user_id<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTatoosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tatoos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')
                ->comment('название');
            $table->string('url')
                ->comment('путь к картинке');
            $table->integer('price')
                ->comment('цена');
            $table->string('description')
                ->comment('описание');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tatoos');
    }
}
