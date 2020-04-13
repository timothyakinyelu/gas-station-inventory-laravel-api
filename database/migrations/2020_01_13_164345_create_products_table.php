<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')
            ->onDelete('cascade');
            $table->bigInteger('product_type_id')->unsigned();
            $table->foreign('product_type_id')->references('id')->on('product_types')
            ->onDelete('cascade');
            $table->bigInteger('product_code_id')->unsigned();
            $table->foreign('product_code_id')->references('id')->on('product_codes')
            ->onDelete('cascade');
            $table->string('name');
            $table->float('price');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
