<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsinCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asin_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('asin_id',64)->index();
            $table->string('title',255)->nullable();
            $table->string('link_review_page',512)->nullable();
            $table->text('body')->nullable();
            $table->tinyInteger('review_score')->nullable();
            $table->string('date_of_review',32)->nullable();
            $table->string('author',255);
            $table->string('link_author',255)->nullable();
            $table->boolean('is_verified');
            $table->string('video_url',255)->nullable();
            $table->string('asin_child',64)->nullable();
            $table->timestamps();

            $table->foreign('asin_id')
                ->references('id')->on('asins')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('asin_comments');
    }
}
