<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('post', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('category_id')->unsigned();
            $table->string('title', 250);
            $table->string('slug')->unique();
            $table->string('description');
            $table->string('preview_thumbnail')->nullable();
            $table->mediumText('content');
            $table->mediumText('blockcontent');
            $table->string('image');
            $table->tinyInteger('status')->default(0);
            $table->tinyInteger('clicks_all_time')->default(0);
            $table->integer('post_request_id')->unsigned()->nullable();
            $table->string('internal_comments');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')
                ->references('id')->on('user')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('post');
    }
}
