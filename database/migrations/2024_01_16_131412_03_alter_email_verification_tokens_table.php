<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('email_verification_tokens', function (Blueprint $table) {
            $table->foreign('user_id', 'fk_email_verification_tokens_users_id_user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('email_verification_tokens', function (Blueprint $table) {
            $table->dropForeign('fk_email_verification_tokens_users_id_user_id');
        });
    }
};
