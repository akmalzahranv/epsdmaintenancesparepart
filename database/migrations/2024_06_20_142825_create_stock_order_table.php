<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_order', function (Blueprint $table) {
            $table->id('stock_order_id');
            $table->foreignId('user_id')->constrained('users');
            $table->string('username');
            $table->unsignedBigInteger('shelf_id');
            $table->foreign('shelf_id')->references('shelf_id')->on('shelf')->onDelete('cascade');
            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('product_id')->on('products')->onDelete('cascade');
            $table->integer("product_amount");      
            $table->string('status');          
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
        Schema::dropIfExists('stock_order');
    }
}
