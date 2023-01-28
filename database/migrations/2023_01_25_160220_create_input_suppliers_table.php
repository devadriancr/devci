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
        Schema::create('input_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier')->nullable();
            $table->string('order_no')->nullable();
            $table->string('sequence')->nullable();
            $table->unsignedBigInteger('item_id')->nullable();
            $table->string('snp')->nullable();
            $table->date('received_date')->nullable();
            $table->time('received_time')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('transaction_type_id')->nullable();
            $table->unsignedBigInteger('delivery_production_id')->nullable();
            $table->unsignedBigInteger('travel_id')->nullable();
            $table->longText('payload')->nullable();
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')->onDelete('set null');
            $table->foreign('delivery_production_id')->references('id')->on('delivery_productions')->onDelete('set null');
            $table->foreign('travel_id')->references('id')->on('travel')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('input_suppliers');
    }
};
