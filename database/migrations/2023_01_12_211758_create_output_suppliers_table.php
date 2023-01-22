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
        Schema::create('output_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('supplier')->nullable();
            $table->string('Identificationcard')->nullable();
            $table->string('order_number')->nullable();
            $table->string('sequence')->nullable();
            $table->double('quantity');
            $table->unsignedBigInteger('item_id')->nullable();
            $table->unsignedBigInteger('transaction_type_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('delivery_production_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            //  ------------------------------------------------------------------------------
            // llave items
            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            // llave transaction type
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')->onDelete('set null');
            // llave a locations
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');

            // llave a delivery
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
        Schema::dropIfExists('output_suppliers');
    }
};
