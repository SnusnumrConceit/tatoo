<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSubjectColumnToAudits extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->json('subject')
                ->after('id')
                ->comment('субъект');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('audits', 'subject'))
        {
            Schema::table('audits', function (Blueprint $table) {
                $table->dropColumn('subject');
            });
        }
    }
}
