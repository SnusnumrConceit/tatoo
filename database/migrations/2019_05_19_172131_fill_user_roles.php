<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\User;

class FillUserRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $users = User::all();
        $user_roles = [];
        foreach ($users as $user) {
            $user_role = [
                'user_id' => $user->id,
                'role_id' => 1,
                'created_at' => $user->creaated_at,
                'updated_at' => $user->updated_at
            ];
            array_push($user_roles, $user_role);
        }
        DB::table('role_user')->insert($user_roles);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('role_user')->truncate();
    }
}
