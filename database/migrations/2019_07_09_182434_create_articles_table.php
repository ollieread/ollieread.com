<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Ollieread\Core\Support\Status;

class CreateArticlesTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('series_id')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('title')->nullable();
            $table->string('heading')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->string('image')->nullable();
            $table->boolean('active')->default(1);
            $table->unsignedTinyInteger('status')->default(Status::DRAFT);
            $table->dateTime('post_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('series_id')->references('id')->on('series');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('parent_id')->references('id')->on('articles');
        });
    }
}
