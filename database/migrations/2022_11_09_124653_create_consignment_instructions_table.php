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
        Schema::create('consignment_instructions', function (Blueprint $table) {
            $table->id();
            $table->string('serial')->unique();
            $table->unsignedBigInteger('container_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->timestamps();

            // $table->foreign('container_id')->references('id')->on('containers')->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('consignment_instructions');
    }
};
