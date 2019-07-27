<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRolesTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('roles');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('roles', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('ident')->unique();
            $table->text('description');
            $table->boolean('active');
            $table->unsignedBigInteger('level')->default(0);
            $table->timestamps();
        });
    }
}
