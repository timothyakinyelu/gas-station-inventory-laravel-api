<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supplies', function (Blueprint $table) {
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
            $table->integer('inventory_received');
            $table->float('supply_price')->nullable();
            $table->date('date_of_supply');
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
        Schema::dropIfExists('supplies');
    }
}
