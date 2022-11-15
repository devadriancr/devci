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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('item');
            $table->string('description');
            $table->double('opening_balance');
            $table->double('minimum_balance');
            $table->double('maximum_balance');
            $table->string('status');
            $table->unsignedBigInteger('item_type_id');
            $table->unsignedBigInteger('item_class_id');
            $table->unsignedBigInteger('measurement_unit_id');
            $table->timestamps();

            $table->foreign('item_type_id')->references('id')->on('item_types');
            $table->foreign('item_class_id')->references('id')->on('item_classes');
            $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
};
