<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
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
            $table->string('tank_code')->nullable();
            $table->integer('open_stock');
            $table->integer('close_stock');
            $table->integer('inventory_sold');
            $table->integer('inventory_received')->nullable();
            $table->date('date_of_inventory');
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
        Schema::dropIfExists('stocks');
    }
}
