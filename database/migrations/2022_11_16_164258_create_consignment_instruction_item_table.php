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
        Schema::create('consignment_instruction_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('consignment_instruction_id');
            $table->unsignedBigInteger('item_id');
            $table->double('quantity');
            $table->timestamps();

            $table->foreign('consignment_instruction_id')->references('id')->on('consignment_instructions')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consignment_instruction_item');
    }
};
