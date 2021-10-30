<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGalleryData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gallery_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('gallery_id');
            $table->string('image_thumb', 100);
            $table->string('image_big', 100);
            $table->text('description', 50);
            $table->integer('sort_order');
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
        Schema::drop('gallery_data');
    }
}
