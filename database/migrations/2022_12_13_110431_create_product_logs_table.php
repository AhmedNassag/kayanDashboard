<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('price_id')->nullable()->constrained('prices')->cascadeOnDelete();
            $table->integer('diff_qty')->nullable();
            $table->integer('total_qty')->nullable();
            $table->integer('sold_quantity')->default(0);
            $table->double('pharmacyPrice')->nullable();
            $table->double('publicPrice')->nullable();
            $table->double('clientDiscount')->nullable();
            $table->double('kayanDiscount')->nullable();
            $table->double('kayanProfit')->nullable();
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
        Schema::dropIfExists('product_logs');
    }
}
