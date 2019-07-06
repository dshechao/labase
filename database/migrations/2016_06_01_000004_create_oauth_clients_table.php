<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOauthClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth_clients', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->index()->nullable()->comment('');
            $table->string('name')->comment('');
            $table->string('secret', 100)->comment('');
            $table->text('redirect')->comment('');
            $table->boolean('personal_access_client')->comment('');
            $table->boolean('password_client')->comment('');
            $table->boolean('revoked')->comment('是否撤销  0=未撤销  1=已撤销');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('oauth_clients');
    }
}
