<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageViewPackagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_view_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('advertising_packages')->cascadeOnDelete();
            $table->foreignId('page_view_id')->constrained('advertising_page_advertising_views')->cascadeOnDelete();
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
        Schema::dropIfExists('page_view_packages');
    }
}
