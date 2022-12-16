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
        Schema::create('outputs', function (Blueprint $table) {
            $table->id();
            $table->string('supplier')->nullable();;
            $table->string('serial')->nullable();
            $table->unsignedBigInteger('item_id');
            $table->double('item_quantity');
            $table->unsignedBigInteger('transaction_type_id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('travel_id')->nullable();
            $table->timestamps();
            //  ------------------------------------------------------------------------------
            // llave items
            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            // llave transaction type
            $table->foreign('transaction_type_id')->references('id')->on('transaction_types')->onDelete('set null');
            // llave a locations
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
             // llave a travel
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
        Schema::dropIfExists('outputs');
    }
};
