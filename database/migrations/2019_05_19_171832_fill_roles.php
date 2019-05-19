<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FillRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $roles = [
            [
                'name' => 'Пользователь',
                'slug' => 'client',
                'description' => 'клиент ресурса'
            ],
            [
                'name' => 'Администратор',
                'slug' => 'admin',
                'description' => 'администратор ресурса'
            ],
        ];

        DB::table('roles')->insert($roles);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('roles')->delete();
        DB::statement('ALTER TABLE roles AUTO_INCREMENT = 0');
    }
}
