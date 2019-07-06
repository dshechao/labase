<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthRefreshTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create('oauth_refresh_tokens',function (Blueprint $table){
            $table -> string('id',100) -> primary();
            $table -> string('access_token_id',100) -> index();
            $table -> boolean('revoked') -> comment('是否撤销  0=未撤销  1=已撤销');
            $table -> dateTime('expires_at') -> nullable() -> comment('有效期');
            $table -> foreign('access_token_id')
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
        Schema ::dropIfExists('oauth_refresh_tokens');
    }
}
