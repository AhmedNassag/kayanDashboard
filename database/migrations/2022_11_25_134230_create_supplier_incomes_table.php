<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierIncomesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplier_incomes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('supplier_id')->unsigned()->nullable();
            $table->bigInteger('income_id')->unsigned()->nullable();
            $table->bigInteger('treasury_id')->unsigned()->nullable();
            $table->foreignId('purchase_return_id')->nullable()->constrained('purchase_returns')->cascadeOnDelete();
            $table->double('amount',25,2);
            $table->text('notes')->nullable();
            $table->date('payment_date');
            $table->bigInteger('user_id')->unsigned()->nullable();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('treasury_id')->references('id')->on('treasuries')->onDelete('cascade');
            $table->foreign('income_id')->references('id')->on('incomes')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
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
        Schema::dropIfExists('supplier_incomes');
    }
}
