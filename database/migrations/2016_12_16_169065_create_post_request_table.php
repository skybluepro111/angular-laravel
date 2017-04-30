<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostRequestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_request', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description', 2048);
            $table->decimal('price_per_post', 6, 2);
            $table->string('recurring', 2048);
            $table->tinyInteger('status')->default(0);
            $table->integer('user_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('post_request');
    }
}
