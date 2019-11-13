<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailIncomeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_income', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('idincome');
            $table->foreign('idincome')->references('id')->on('income')->onDelete('cascade');
            $table->unsignedBigInteger('idarticles');
            $table->foreign('idarticles')->references('id')->on('articles');
            $table->integer('quantity');
            $table->decimal('price', 11, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_income');
    }
}
