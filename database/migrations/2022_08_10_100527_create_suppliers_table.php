<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('employee_id')->unsigned()->nullable();
            $table->string("name")->nullable();
            $table->string("address")->nullable();
            $table->string("phone")->nullable();
            $table->string("commerical_register")->nullable();
            $table->string("tax_card")->nullable();
            $table->string("responsible_name")->nullable();
            $table->string("responsible_phone")->nullable();
            $table->boolean("active")->nullable();
            $table->string("payment_type")->nullable();
            $table->string("account_number")->nullable();
            $table->string("payment_phone")->nullable();
            $table->string("payment_responsible_name")->nullable();
            $table->string("payment_responsible_phone")->nullable();
            $table->string("payment_responsible_card_number")->nullable();
            $table->foreign('employee_id')->references('id')->on('employees');
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
        Schema::dropIfExists('suppliers');
    }
}
