<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeSocialExpirationToDatetimeOnUserSocialTable extends Migration
{
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('user_social', static function (Blueprint $table) {
            $table->dropColumn('expires_at');
            $table->unsignedBigInteger('expires_in')->nullable();
        });
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('user_social', static function (Blueprint $table) {
            $table->dropColumn('expires_in');
            $table->dateTime('expires_at')->nullable();
        });
    }
}
