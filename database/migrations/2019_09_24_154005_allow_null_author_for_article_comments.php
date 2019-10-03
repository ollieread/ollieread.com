<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AllowNullAuthorForArticleComments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('article_comments', static function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->nullable()->change();
            $table->dropForeign('article_comments_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('article_comments', static function (Blueprint $table) {
            $table->unsignedBigInteger('author_id')->change();
            $table->dropForeign('article_comments_author_id_foreign');
            $table->foreign('author_id')->references('id')->on('users');
        });
    }
}
