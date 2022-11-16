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
            $table->string('item')->unique();
            $table->string('description')->nullable();
            $table->double('opening_balance')->nullable();
            $table->double('minimum_balance')->nullable();
            $table->string('status')->nullable();
            $table->string('item_type')->nullable();
            $table->string('item_class')->nullable();
            $table->string('measurement_unit')->nullable();
            $table->string('creation_date')->nullable();
            $table->string('creation_time')->nullable();
            $table->timestamps();

            // $table->foreign('item_type_id')->references('id')->on('item_types');
            // $table->foreign('item_class_id')->references('id')->on('item_classes');
            // $table->foreign('measurement_unit_id')->references('id')->on('measurement_units');
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
