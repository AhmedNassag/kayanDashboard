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
            $table->text('description')->nullable();
            $table->string('charge')->nullable();
            $table->string('maxMount')->nullable();
            $table->string('barCode')->nullable();
            $table->string('saleMethods')->nullable();
            $table->boolean('status')->default(true);

            $table->foreignId('productName_id')->constrained('product_names')->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->foreignId('sub_category_id')->constrained('sub_categories')->cascadeOnDelete();
            $table->foreignId('company_id')->constrained('companies')->cascadeOnDelete()->nullable();
            $table->foreignId('supplier_id')->constrained('suppliers')->cascadeOnDelete()->nullable();
            $table->foreignId('tax_id')->constrained('taxes')->cascadeOnDelete();
            $table->foreignId('unit_id')->constrained('units')->cascadeOnDelete();

            $table->timestamps();

            // $table->unsignedBigInteger('category_id');
            // $table->index(["category_id"], 'category_id');
            // $table->foreign('category_id', 'category_id')
            //       ->references('id')->on('categories')
            //       ->onDelete('cascade')
            //       ->onUpdate('cascade');

            // $table->unsignedBigInteger('sub_category_id');
            // $table->index(["sub_category_id"], 'sub_category_id');
            // $table->foreign('sub_category_id', 'sub_category_id')
            //       ->references('id')->on('sub_categories')
            //       ->onDelete('cascade')
            //       ->onUpdate('cascade');

            // $table->unsignedBigInteger('company_id');
            // $table->index(["company_id"], 'company_id');
            // $table->foreign('company_id', 'company_id')
            //       ->references('id')->on('companies')
            //       ->onDelete('cascade')
            //       ->onUpdate('cascade');

            // $table->unsignedBigInteger('tax_id');
            // $table->index(["tax_id"], 'tax_id');
            // $table->foreign('tax_id', 'tax_id')
            //       ->references('id')->on('taxes')
            //       ->onDelete('cascade')
            //       ->onUpdate('cascade');

            // $table->unsignedBigInteger('unit_id');
            // $table->index(["unit_id"], 'unit_id');
            // $table->foreign('unit_id', 'unit_id')
            //       ->references('id')->on('units')
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
        Schema::dropIfExists('products');
    }
}
