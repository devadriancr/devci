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
        Schema::create('standard_packs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->double('quantity');
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('standard_type_id')->nullable();
            $table->timestamps();

            $table->foreign('standard_type_id')->references('id')->on('standard_types')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('standard_packs');
    }
};
