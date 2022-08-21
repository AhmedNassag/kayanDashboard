<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForeignKey extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sub_categories',  function(Blueprint $table)
        {
            $table->foreign('category_id')
            ->references('id')
            ->on('categories')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::table('products',  function(Blueprint $table)
        {
            $table->foreign('cat_id')
            ->references('id')
            ->on('categories')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::table('products',  function(Blueprint $table)
        {
            $table->foreign('sub_category_id')
            ->references('id')
            ->on('sub_categories')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::table('products',  function(Blueprint $table)
        {
            $table->foreign('company_id')
            ->references('id')
            ->on('companies')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::table('products',  function(Blueprint $table)
        {
            $table->foreign('tax_id')
            ->references('id')
            ->on('taxes')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });

        Schema::table('products',  function(Blueprint $table)
        {
            $table->foreign('unit_id')
            ->references('id')
            ->on('units')
            ->onUpdate('cascade')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
