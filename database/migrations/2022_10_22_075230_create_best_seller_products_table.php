<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBestSellerProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('best_seller_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers');
            $table->foreignId('best_seller_id')->nullable()->constrained('best_sellers');
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
        Schema::dropIfExists('best_seller_products');
    }
}
