<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create('oauth_access_tokens',function (Blueprint $table){
            $table -> string('id',100) -> primary();
            $table -> integer('user_id') -> index() -> nullable() -> comment('用户 ID');
            $table -> unsignedInteger('client_id') -> comment('客户端 ID');
            $table -> string('name') -> nullable() -> comment('名字');
            $table -> text('scopes') -> nullable() -> comment('作用域');
            $table -> boolean('revoked') -> comment('是否撤销  0=未撤销  1=已撤销');
            $table -> timestamps();
            $table -> dateTime('expires_at') -> nullable() -> comment('有效期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists('oauth_access_tokens');
    }
}
