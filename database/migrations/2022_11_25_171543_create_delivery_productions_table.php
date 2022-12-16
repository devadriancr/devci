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
        Schema::create('delivery_productions', function (Blueprint $table) {
            $table->id();
            $table->string('control_number');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->boolean('finish')->nullable();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('delivery__produtions');
    }
};
