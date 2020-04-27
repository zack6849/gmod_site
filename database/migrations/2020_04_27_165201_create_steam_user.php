<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSteamUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('steam_users', function (Blueprint $table) {
            $table->id();
            $table->string("steamid")->unique();
            $table->string("avatar_url")->nullable();
            $table->string("name")->nullable();
            $table->string("last_address")->nullable();
            $table->boolean('is_online')->default(false);
            $table->boolean('is_connected')->default(false);
            $table->bigInteger("rank_id")->unsigned()->default(7);
            $table->timestamp('last_online_at')->nullable();
            $table->timestamp("last_connected_at")->nullable();
            $table->timestamps();

            $table->foreign('rank_id')->references('id')->on('ranks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('steam_user');
    }
}
