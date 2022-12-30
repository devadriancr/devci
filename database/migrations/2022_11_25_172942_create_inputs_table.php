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
        Schema::create('inputs', function (Blueprint $table) {
            $table->id();
            $table->string('supplier')->nullable();
            $table->string('serial')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->double('item_quantity');
            $table->string('purchase_order')->nullable();
            $table->unsignedBigInteger('container_id')->nullable();
            $table->unsignedBigInteger('transaction_type_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('travel_id')->nullable();
            $table->unsignedBigInteger('delivery_production_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            $table->foreign('container_id')->references('id')->on('containers')->onDelete('set null');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')->onDelete('set null');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('travel_id')->references('id')->on('travel')->onDelete('set null');
            $table->foreign('delivery_production_id')->references('id')->on('delivery_productions')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inputs');
    }
};
