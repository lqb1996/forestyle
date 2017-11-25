<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
//            $table->string('name');
            $table->string('email')->unique();
//            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
//            $table->string('avatar', 100)->default("");

            $table->string('openId');
            $table->string('nickName');
            $table->integer('gender');
            $table->string('language');
            $table->string('city');
            $table->string('province');
            $table->string('country');
            $table->string('avatarUrl');
            $table->string('loginStamp');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
