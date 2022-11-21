<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlternativePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alternative_prices', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('quantity')->nullable();
            $table->double('pharmacyPrice')->nullable();
            $table->double('publicPrice')->nullable();
            $table->double('clientDiscount')->nullable();
            $table->double('kayanDiscount')->nullable();
            $table->double('kayanProfit')->nullable();
            $table->foreignId('alternative_id')->nullable()->constrained('alternatives')->cascadeOnDelete();
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->cascadeOnDelete();
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
        Schema::dropIfExists('alternative_prices');
    }
}
