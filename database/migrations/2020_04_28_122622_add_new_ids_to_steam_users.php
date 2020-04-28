<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewIdsToSteamUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('steam_users', function (Blueprint $table) {
            $table->string("steamid3")->unique()->after('steamid');
            $table->string("steamid64")->unique()->after('steamid3');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('steam_users', function (Blueprint $table) {
            $table->dropColumn('steamid64');
            $table->dropColumn('steamid3');
        });
    }
}
