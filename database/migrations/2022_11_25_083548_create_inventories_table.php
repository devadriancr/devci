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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->double('opening_balance');
            $table->double('minimum');
            $table->double('maximum');
            $table->double('quantity');
            $table->unsignedBigInteger('item_id')->unique()->nullable();
            $table->unsignedBigInteger('location_id')->unique()->nullable();
            $table->timestamps();

            $table->foreign('item_id')->references('id')->on('items')->onDelete('set null');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inventories');
    }
};
