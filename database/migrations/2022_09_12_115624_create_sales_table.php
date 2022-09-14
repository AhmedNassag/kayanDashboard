<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_id')->nullable()->constrained('stocks')->cascadeOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnDelete();
            $table->string('type')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('purchase_type')->nullable();
            $table->double('price',25,2)->default(0);
            $table->double('visa_percentage',8,2)->default(0);
            $table->double('visa_value',8,2)->default(0);
            $table->double('added_tax_percentage',8,2)->default(0);
            $table->double('added_tax_value',8,2)->default(0);
            $table->double('discount_percentage',8,2)->default(0);
            $table->double('discount_value',8,2)->default(0);
            $table->double('other_discounts',8,2)->default(0);
            $table->double('transfer_price',8,2)->default(0);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('sales');
    }
}
