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
        Schema::create('shipping_instructions', function (Blueprint $table) {
            $table->id();
            $table->string('container')->nullable();
            $table->string('invoice')->nullable();
            $table->string('serial')->nullable();
            $table->string('part_no')->nullable();
            $table->integer('part_qty')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_instructions');
    }
};
