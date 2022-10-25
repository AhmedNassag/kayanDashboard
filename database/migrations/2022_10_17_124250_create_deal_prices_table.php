<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDealPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deal_prices', function (Blueprint $table) {
            $table->id();
            $table->decimal('pharmacyPrice')->nullable();
            $table->decimal('publicPrice')->nullable();
            $table->decimal('clientDiscount')->nullable();
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('deal_id')->nullable()->constrained('deals');
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
        Schema::dropIfExists('deal_prices');
    }
}
