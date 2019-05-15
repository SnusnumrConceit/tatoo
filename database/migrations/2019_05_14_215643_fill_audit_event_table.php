<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class FillAuditEventTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $events = [
            [
                'event' => 'Добавление пользователя'
            ],
            [
                'event' => 'Редактирование пользователя'
            ],
            [
                'event' => 'Удаление пользователя'
            ],
            [
                'event' => 'Добавление татуировки'
            ],
            [
                'event' => 'Редактирование татуировки'
            ],
            [
                'event' => 'Удаление татуировки'
            ],
            [
                'event' => 'Добавление работника'
            ],
            [
                'event' => 'Редактирование работника'
            ],
            [
                'event' => 'Удаление работника'
            ],
            [
                'event' => 'Добавление должности'
            ],
            [
                'event' => 'Редактирование должности'
            ],
            [
                'event' => 'Удаление должности'
            ],
            [
                'event' => 'Добавление заказа'
            ],
            [
                'event' => 'Редактирование заказа'
            ],
            [
                'event' => 'Удаление заказа'
            ],
            [
                'event' => 'Регистрация нового пользователя'
            ],
            [
                'event' => 'Вход в систему под учётной записью'
            ],
            [
                'event' => 'Выход из системы под учётной записью'
            ],
        ];
        DB::table('audit_events')->insert($events);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('audit_events')->truncate();
        DB::statement('ALTER TABLE audit_events AUTO_INCREMENT = 0');
    }
}
