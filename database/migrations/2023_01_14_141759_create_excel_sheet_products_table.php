<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExcelSheetProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('excel_sheet_products', function (Blueprint $table) {
            $table->id();
            $table->string('Description AR')->nullable();
            $table->string('Description')->nullable();
            $table->string('Code')->default(0);
            $table->string('Sales Price')->nullable();
            $table->string('Commercial Name EN')->nullable();
            $table->string('Commercial Name AR')->nullable();
            $table->string('Product Division')->nullable();
            $table->string('Sub-Division')->nullable();
            $table->string('Product Category')->nullable();
            $table->string('Company')->nullable();
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
        Schema::dropIfExists('excel_sheet_products');
    }
}
