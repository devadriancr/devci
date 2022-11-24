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
        Schema::create('shipping_detail_in_outs', function (Blueprint $table) {
            $table->id()->unique()->nullable();
            $table->date('date_Scan')->nullable();
            $table->time('time_scan')->nullable();
            $table->boolean('status')->nullable();
            $table->unsignedBigInteger('shipping_id')->nullable();
            $table->unsignedBigInteger('consignment_id')->nullable();
            $table->timestamps();
            $table->foreign('consignment_id')->references('id')->on('consignment_instructions');
            $table->foreign('shipping_id')->references('id')->on('shipping_in_outs');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_detail_in_outs');
    }
};
