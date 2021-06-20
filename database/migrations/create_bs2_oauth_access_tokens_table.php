<?php

use Illuminate\Database\Migrations\Migration;

class CreateBs2OauthAccessTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bs2_oauth_access_tokens', function (Blueprint $table) {
            $table->increments('id');
            $table->string('access_token');
            $table->string('token_type');
            $table->string('expires_in');
            $table->string('refresh_token');
            $table->string('scope');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bs2_oauth_access_tokens');
    }
}
