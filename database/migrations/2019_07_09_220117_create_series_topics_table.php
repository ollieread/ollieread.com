<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeriesTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series_topics', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('series_id');
            $table->unsignedBigInteger('topic_id');
            $table->timestamps();

            $table->foreign('series_id')->references('id')->on('series');
            $table->foreign('topic_id')->references('id')->on('topics');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series_topics');
    }
}
