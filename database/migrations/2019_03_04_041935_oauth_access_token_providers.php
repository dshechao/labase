<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OauthAccessTokenProviders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create('oauth_access_token_providers',function (Blueprint $table){
            $table -> string('oauth_access_token_id',100) -> primary();
            $table -> string('provider') -> comment('权限服务提供者');
            $table -> timestamps();
            $table -> foreign('oauth_access_token_id')
                -> references('id') -> on('oauth_access_tokens')
                -> onDelete('cascade')
            ;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::drop('oauth_access_token_providers');
    }
}
