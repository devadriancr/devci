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
            $table->string('item_number');
            $table->string('item_description');
            $table->double('opening_balance');
            $table->double('minimum_balance');
            $table->unsignedBigInteger('measurement_type_id');
            $table->unsignedBigInteger('item_type_id');
            $table->unsignedBigInteger('item_class_id');
            $table->unsignedBigInteger('standard_pack_id');
            $table->timestamps();

            $table->foreign('measurement_type_id')->references('id')->on('measurement_types');
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
