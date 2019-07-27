<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSocialTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_social');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_social', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('social_id');
            $table->string('provider');
            $table->string('token');
            $table->string('refresh_token')->nullable();
            $table->unsignedBigInteger('expires_in')->nullable();
            $table->string('secret')->nullable();
            $table->json('metadata')->nullable();
            $table->string('avatar');
            $table->boolean('use_avatar')->default(0);
            $table->timestamps();

            $table->unique(['user_id', 'provider']);
            $table->unique(['provider', 'social_id']);
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
