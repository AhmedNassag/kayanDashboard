<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellerCategoryTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seller_category_translations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('locale')->index();
            $table->foreignId('seller_category_id')->constrained('seller_categories')->cascadeOnDelete();
            $table->unique(['seller_category_id', 'locale']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seller_category_translations');
    }
}
