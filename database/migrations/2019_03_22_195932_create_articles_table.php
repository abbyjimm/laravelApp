<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('idcategory');
            $table->string('code', 50)->nullable();
            $table->string('name', 50)->unique();
            $table->decimal('sale_price', 11, 2);
            $table->integer('stock');
            $table->string('description', 256)->nullable();
            $table->boolean('condition')->default(1);
            $table->timestamps();

            $table->foreign('idcategory')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
