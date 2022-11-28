<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');

            $table->float('total_amount',11,2)->nullable();
            $table->float('subtotal',11,2)->nullable();
            $table->float('shipping_cost',11,2)->nullable();
            $table->float('taxes',11,2)->nullable();
            $table->string('receiver_address');
            $table->string('receiver_phone');
            $table->string('receiver_name');
            $table->string('refund_amount')->default(0);
            $table->float('discount_percentage',11,2)->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities')->cascadeOnDelete();
            $table->foreignId('area_id')->nullable()->constrained('areas')->cascadeOnDelete();
            $table->foreignId('representative_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string("invoice_id")->default(0);
            $table->string("transaction_id")->default(0);
            $table->tinyInteger("hold")->default(0);
            $table->string("order_from")->nullable();
            $table->string("order_status")->nullable();
            $table->string("payment_status")->nullable();
            $table->string("payment_method")->nullable();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('orders');
    }
}
