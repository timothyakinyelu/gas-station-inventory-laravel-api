<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->unsigned();
            $table->foreign('company_id')->references('id')->on('companies')
            ->onDelete('cascade');
            $table->bigInteger('station_id')->unsigned()->nullable();
            $table->foreign('station_id')->references('id')->on('stations')
            ->onDelete('cascade');
            $table->string('firstName');
            $table->string('secondName')->nullable();
            $table->string('lastName');
            $table->string('phone');
            $table->string('address');
            $table->date('date_of_birth');
            $table->string('role');
            $table->string('salary');
            $table->date('date_hired');
            $table->string('avatar')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
