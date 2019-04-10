<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAuditsTable extends Migration
{
    public function up()
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')
                ->nullable()
                ->comment('совершивший действие');
            $table->unsignedInteger('type')
                ->comment('тип события');
            $table->json('status')
                ->comment('статус');
            $table->timestamps();
        });

        Schema::table('audits', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });

        Schema::table('audits', function (Blueprint $table) {
            $table->foreign('type')
                ->references('id')
                ->on('audit_events')
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
        if (Schema::hasColumn('audits', 'type')) {
            Schema::table('audits', function (Blueprint $table) {
                $table->dropIndex(['type']);
            });
        }

        if (Schema::hasColumn('audits', 'user_id')) {
            Schema::table('audits', function (Blueprint $table) {
                $table->dropIndex(['user_id']);
            });
        }

        Schema::dropIfExists('audits');
    }
}
