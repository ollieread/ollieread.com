<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRedirectsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('redirects');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirects', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('from')->unique();
            $table->string('to')->index();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();

            $table->unique(['from', 'to']);
        });
    }
}
