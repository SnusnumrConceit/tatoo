<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')
                ->nullable()
                ->comment('клиент');
            $table->unsignedInteger('tatoo_id')
                ->nullable()
                ->comment('татуировка');
            $table->tinyInteger('status');
            $table->date('note_date')
                ->comment('дата и время записи');
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
           $table->foreign('user_id')
               ->references('id')
               ->on('users')
               ->onDelete('set null');

            $table->foreign('tatoo_id')
                ->references('id')
                ->on('tatoos')
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
        if (Schema::hasColumn('orders', 'tatoo_id'))
        {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['tatoo_id']);
            });
        }

        if (Schema::hasColumn('orders', 'user_id'))
        {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
            });
        }

        Schema::dropIfExists('orders');
    }
}
