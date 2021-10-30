<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogEntries extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_entries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('blog_id');
            $table->string('title', 40);
            $table->text('truncated_text');
            $table->text('text');
            $table->text('nomod_text');
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
        Schema::drop('blog_entries');
    }
}
