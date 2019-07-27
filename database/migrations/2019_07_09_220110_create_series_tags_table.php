<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series_tags', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('series_id');
            $table->unsignedBigInteger('tag_id');
            $table->timestamps();

            $table->foreign('series_id')->references('id')->on('series');
            $table->foreign('tag_id')->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series_tags');
    }
}
