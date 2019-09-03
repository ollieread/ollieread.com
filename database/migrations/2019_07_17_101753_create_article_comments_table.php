<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleCommentsTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('article_comments');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('article_comments', static function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('article_id');
            $table->unsignedBigInteger('author_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->boolean('active')->default(1);
            $table->boolean('private')->default(0);
            $table->longText('comment');
            $table->unsignedTinyInteger('reaction')->default(0);
            $table->string('deleted_reason')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('author_id')->references('id')->on('users');
            $table->foreign('parent_id')->references('id')->on('article_comments');
        });
    }
}
