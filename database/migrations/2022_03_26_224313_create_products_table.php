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
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('subcategorie_id');
            $table->string('title');
            $table->string('slug');
            $table->unsignedInteger('stock');
            $table->text('description');
            $table->unsignedInteger('price');
            $table->string('sucursal_code')->nullable();
            $table->unsignedTinyInteger('shipp_active')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('subcategorie_id')->references('id')->on('subcategories');
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
        Schema::dropIfExists('products');
    }
}
