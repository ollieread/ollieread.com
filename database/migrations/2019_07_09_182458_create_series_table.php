<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ollieread\Core\Support\Status;

class CreateSeriesTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('series');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('series', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('heading')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('slug')->unique();
            $table->mediumText('content');
            $table->string('image')->nullable();
            $table->boolean('active')->default(1);
            $table->unsignedTinyInteger('status')->default(Status::DRAFT);
            $table->dateTime('post_at')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories');
        });
    }
}
