<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRbacGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rbac_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rank_id');
            $table->integer('permission_id');
            $table->string('name');
            $table->string('url', 200);
            $table->boolean('revoke')->default(false);
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
        Schema::drop('rbac_groups');
    }
}
