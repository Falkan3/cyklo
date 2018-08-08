<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_options', function(Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('product_item_id')->unsigned()->nullable();
            $table->string('name');
            $table->string('description')->nullable()->default(null);
            $table->float('price')->nullable()->default(null);
            $table->float('tax')->nullable()->default(null);
            $table->integer('stock')->unsigned()->nullable()->default(0);
            $table->timestamps();

            $table->foreign('product_item_id')->references('id')->on('product_items')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('product_options');
    }
}
