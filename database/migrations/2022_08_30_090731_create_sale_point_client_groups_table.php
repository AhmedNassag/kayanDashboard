<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalePointClientGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_point_client_groups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sale_point_id')->unsigned()->nullable();
            $table->bigInteger('client_group_id')->unsigned()->nullable();
            $table->foreign('sale_point_id')->references('id')->on('sale_points');
            $table->foreign('client_group_id')->references('id')->on('client_groups');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_point_client_groups');
    }
}
