<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePasswordResetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });
//        Schema::create('users', function (Blueprint $table) {
//            $table->increments('id');
////            $table->string('name');
//            $table->string('email')->unique();
//            $table->string('password');
////            $table->string('avatar', 100)->default("");
//            $table->string('openId');
//            $table->string('nickName');
//            $table->integer('gender');
//            $table->string('language');
//            $table->string('city');
//            $table->string('province');
//            $table->string('country');
//            $table->string('avatarUrl');
//            $table->string('loginStamp');
//            $table->rememberToken();
//            $table->timestamps();
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('password_resets');
//        Schema::dropIfExists('users');
    }
}
