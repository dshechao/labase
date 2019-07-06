<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema ::create('admins',function (Blueprint $table){
            $table -> increments('id');
            $table -> string('pid') -> nullable() -> comment('上级ID');
            $table -> string('name') -> comment('用户名');
            $table -> string('phone') -> unique() -> comment('电话');
            $table -> string('email') -> nullable() -> comment('邮箱');
            $table -> string('last_ip') -> nullable() -> comment('最后登录 IP');
            $table -> string('last_time') -> nullable() -> comment('最后登录时间');
            $table -> timestamp('email_verified_at') -> nullable() -> comment('邮箱验证时间');
            $table -> string('password') -> comment('密码');
            $table -> rememberToken();
            $table -> timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema ::dropIfExists('admin_users');
    }
}
