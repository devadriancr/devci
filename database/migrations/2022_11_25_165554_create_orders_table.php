<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orden_information_id')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->double('item_quantity');
            $table->foreign('orden_information_id')->references('id')->on('order_information')->onDelete('set null');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
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
        Schema::dropIfExists('orders');
    }
};
