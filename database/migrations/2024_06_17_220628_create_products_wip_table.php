<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsWipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_wip', function (Blueprint $table) {
            $table->id('product_wip_id');
            $table->foreignId('user_id')->constrained('users');
            $table->string('product_code')->unique();
            $table->string('product_name');
            $table->string('problem_details');
            $table->string('specification');
            $table->string('maker')->nullable();
            $table->string('item_no')->nullable();
            $table->integer('quantity');
            $table->unsignedBigInteger('shelf_id');
            $table->foreign('shelf_id')->references('shelf_id')->on('shelf')->onDelete('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('cascade');
            $table->unsignedBigInteger('line_id');
            $table->foreign('line_id')->references('line_id')->on('line')->onDelete('cascade');
            $table->unsignedBigInteger('machine_id')->nullable();;
            $table->foreign('machine_id')->references('machine_id')->on('machine')->onDelete('cascade');
            $table->date('request_date');
            $table->string('requester');
            $table->date('order_date')->nullable();
            $table->string('supplier')->nullable();
            $table->date('estimate_time')->nullable();
            $table->date('arrival_time')->nullable();
            $table->date('installation_planning_schedule')->nullable();
            $table->date('installation_date')->nullable();
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
        Schema::dropIfExists('products_wip');
    }
}
