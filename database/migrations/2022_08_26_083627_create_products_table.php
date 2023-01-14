<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nameAr')->nullable();
            $table->string('nameEn')->nullable();
            $table->string('effectiveMaterial')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('barcode');
            $table->integer('Re_order_limit')->nullable();
            $table->integer('maximum_product')->nullable();
            $table->string('product_code')->nullable();
            $table->text('image')->nullable();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete();
            $table->boolean('status')->default(true);
            $table->foreignId('category_id')->nullable()->constrained('categories')->cascadeOnDelete();
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories')->cascadeOnDelete();
            $table->foreignId('tax_id')->nullable()->constrained('taxes')->cascadeOnDelete();
            $table->foreignId('pharmacistForm_id')->nullable()->constrained('pharmacist_forms')->cascadeOnDelete();
            $table->foreignId('main_measurement_unit_id')->nullable()->constrained('units')->cascadeOnDelete();
            $table->foreignId('sub_measurement_unit_id')->nullable()->constrained('units')->cascadeOnDelete();
            $table->integer('count_unit')->default(0);
            $table->boolean('sell_app')->default(true);
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
        Schema::dropIfExists('products');
    }
}
