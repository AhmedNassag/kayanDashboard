<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertisingPackageTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertising_package_translations', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->foreignId('package_id')->constrained('advertising_packages')->cascadeOnDelete();
            $table->unique('package_id');
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
        Schema::dropIfExists('advertising_package_translations');
    }
}
