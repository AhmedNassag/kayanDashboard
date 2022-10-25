<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulePageMobilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('schedule_page_mobiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_mobile_id')->constrained('advertising_page_mobile_advertising_views')->cascadeOnDelete();
            $table->foreignId('schedule_id')->constrained('advertise_schedules')->cascadeOnDelete();
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
        Schema::dropIfExists('schedule_page_mobiles');
    }
}
