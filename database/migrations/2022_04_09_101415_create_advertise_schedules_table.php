<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertiseSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertise_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->dateTime('start')->nullable();
            $table->dateTime('end')->nullable();
            $table->text('link')->nullable();
            $table->decimal('price',8,2)->nullable();
            $table->decimal('visitor',8,2)->nullable();
            $table->string('transaction_id')->nullable();advertising_package_id
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('advertising_package_id')->constrained('advertising_packages')->cascadeOnDelete();
            $table->string('status')->default('pending');
            $table->boolean('active')->default(false);
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
        Schema::dropIfExists('advertise_schedules');
    }
}
