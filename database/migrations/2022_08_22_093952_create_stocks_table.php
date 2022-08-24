<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('governorate')->nullable();
            $table->string('region')->nullable();
            $table->string('title')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();

            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('shift_id')->constrained('shifts')->cascadeOnDelete();

            $table->timestamps();

            // $table->unsignedBigInteger('empolyee_id');
            // $table->index(["empolyee_id"], 'empolyee_id');
            // $table->foreign('empolyee_id', 'empolyee_id')
            //       ->references('id')->on('empolyees')
            //       ->onDelete('cascade')
            //       ->onUpdate('cascade');

            // $table->unsignedBigInteger('shift_id');
            // $table->index(["shift_id"], 'shift_id');
            // $table->foreign('shift_id', 'shift_id')
            //       ->references('id')->on('shifts')
            //       ->onDelete('cascade')
            //       ->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
