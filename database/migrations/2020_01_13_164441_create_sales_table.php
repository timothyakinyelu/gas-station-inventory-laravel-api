<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('station_id')->unsigned();
            $table->foreign('station_id')->references('id')->on('stations')
            ->onDelete('cascade');
            $table->bigInteger('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')
            ->onDelete('cascade');
            $table->bigInteger('product_code_id')->unsigned();
            $table->foreign('product_code_id')->references('id')->on('product_codes')
            ->onDelete('cascade');
            $table->float('unit_price');
            $table->string('pump_code')->nullable();
            $table->integer('start_metre')->nullable();
            $table->integer('end_metre')->nullable();
            $table->integer('quantity_sold');
            $table->date('date_of_sale');
            $table->float('amount');
            $table->softDeletes();
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
        Schema::dropIfExists('sales');
    }
}
