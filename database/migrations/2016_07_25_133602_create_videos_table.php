<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('videos', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->longText('labels');
            $table->string('image')->nullable();
            $table->timestamps();
        });

        Schema::create('video_translations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('video_id')->unsigned();
            $table->string('locale');
            $table->boolean('status')->default(0);
            $table->string('title');
            $table->string('slug')->nullable();
            $table->timestamps();
            $table->unique(['video_id', 'locale']);
            $table->unique(['locale', 'slug']);
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('video_translations');
        Schema::drop('videos');
    }
}
