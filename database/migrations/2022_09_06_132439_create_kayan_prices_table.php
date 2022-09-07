<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKayanPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kayan_prices', function (Blueprint $table) {
            $table->id();
            $table->string('productPrice')->nullable();
            $table->string('collectionPrice')->nullable();
            $table->string('partialPrice')->nullable();
            $table->string('destributionPrice')->nullable();
            $table->string('averagePrice')->nullable();
            $table->string('productDiscount')->nullable();
            $table->string('publicPrice')->nullable();
            $table->string('pharmacyPrice')->nullable();
            $table->string('collectionKayanProfit')->nullable();
            $table->string('partialKayanProfit')->nullable();
            $table->string('destributionKayanProfit')->nullable();
            $table->string('collectionPercentageKayanProfit')->nullable();
            $table->string('partialPercentageKayanProfit')->nullable();
            $table->string('destributionPercentageKayanProfit')->nullable();
            $table->integer('maximumLimit')->nullable();
            $table->integer('reOrderLimit')->default(0);

            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('sub_category_id')->constrained('sub_categories')->cascadeOnDelete();
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
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
        Schema::dropIfExists('kayan_prices');
    }
}
