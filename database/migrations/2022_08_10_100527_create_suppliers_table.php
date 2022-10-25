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
            $table->string("name")->nullable();
            $table->string("address")->nullable();
            $table->string("phone")->nullable();
            $table->string("commerical_register")->nullable();
            $table->string("tax_card")->nullable();
            $table->string("responsible_name")->nullable();
            $table->string("responsible_phone")->nullable();
            $table->string("type")->nullable();
            $table->boolean("active")->default(1);
            $table->boolean("is_our_supplier")->default(0);
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
