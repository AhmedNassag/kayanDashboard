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
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->text('charge')->nullable();
            $table->text('maxMount')->nullable();
            $table->text('barCode')->nullable();
            $table->unsignedBigInteger('cat_id');
            $table->unsignedBigInteger('sub_category_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('tax_id');
            $table->unsignedBigInteger('unit_id');

            // $table->index(["cat_id"], 'cat_id');
            // $table->foreign('cat_id', 'cat_id')
            //       ->references('id')->on('categories')
            //       ->onDelete('cascade')
            //       ->onUpdate('cascade');

            // $table->index(["sub_category_id"], 'sub_category_id');
            // $table->foreign('sub_category_id', 'sub_category_id')
            //       ->references('id')->on('sub_categories')
            //       ->onDelete('cascade')
            //       ->onUpdate('cascade');

            // $table->index(["company_id"], 'company_id');
            // $table->foreign('company_id', 'company_id')
            //       ->references('id')->on('sub_companies')
            //       ->onDelete('cascade')
            //       ->onUpdate('cascade');

            // $table->index(["tax_id"], 'tax_id');
            // $table->foreign('tax_id', 'tax_id')
            //       ->references('id')->on('taxes')
            //       ->onDelete('cascade')
            //       ->onUpdate('cascade');

            $table->boolean('status')->default(true);
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
