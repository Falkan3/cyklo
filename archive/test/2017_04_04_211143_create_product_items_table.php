<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_items', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('product_category_id')->unsigned()->nullable();
            $table->string('name');
            $table->integer('brand_id')->unsigned()->nullable();
            $table->string('description');
            $table->float('price');
            $table->float('tax');
            $table->float('deliveryprice')->nullable()->default(null);
            $table->string('deliverytime')->nullable()->default(null);
            //$table->integer('stock')->nullable()->default(0);
            $table->timestamps();

            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_items');
    }
}
