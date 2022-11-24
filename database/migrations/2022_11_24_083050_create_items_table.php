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
            $table->string('iid')->nullable();
            $table->string('item_number');
            $table->string('item_description')->nullable();
            $table->unsignedBigInteger('measurement_type_id')->nullable();
            $table->unsignedBigInteger('item_type_id')->nullable();
            $table->unsignedBigInteger('item_class_id')->nullable();
            $table->unsignedBigInteger('standard_pack_id')->nullable();
            $table->timestamps();

            $table->foreign('measurement_type_id')->references('id')->on('measurement_types')->onDelete('set null');
            $table->foreign('item_type_id')->references('id')->on('item_types')->onDelete('set null');
            $table->foreign('item_class_id')->references('id')->on('item_classes')->onDelete('set null');
            $table->foreign('standard_pack_id')->references('id')->on('standard_packs')->onDelete('set null');
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
