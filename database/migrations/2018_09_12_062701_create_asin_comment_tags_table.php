<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAsinCommentTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('asin_comment_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('asin_comment_id')->unsigned()->index();
            $table->string('name',64);
            $table->timestamps();

            $table->foreign('asin_comment_id')
                ->references('id')->on('asin_comments')
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
        Schema::dropIfExists('asin_comment_tags');
    }
}
