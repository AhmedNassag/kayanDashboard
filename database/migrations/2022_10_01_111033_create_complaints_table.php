<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('kind')->nullable();
            $table->foreignId('type')->nullable()->constrained('type_of_complaints')->nullOnDelete();
            $table->string('platform')->nullable();
            $table->string('phone')->nullable();
            $table->string('name')->nullable();
            $table->text('content')->nullable();
            $table->text('reply')->nullable();
            $table->foreignId('responser_id')->nullable()->constrained('users')->cascadeOnDelete();
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
        Schema::dropIfExists('complaints');
    }
}
