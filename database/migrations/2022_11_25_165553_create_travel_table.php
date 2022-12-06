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
        Schema::create('travel', function (Blueprint $table) {
            $table->id();
            $table->string('carta_porte')->unique();;
            $table->string('invoice_number')->unique();;
            $table->string('name')->nullable();
            $table->string('car_plates')->nullable();
            $table->string('car_operator')->nullable();
            $table->string('arrival_date')->nullable();
            $table->string('departure_date')->nullable();
            $table->boolean('finish')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            // llave a locations
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
        Schema::dropIfExists('travel');
    }
};
