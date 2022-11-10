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
            $table->string('trans_mode')->nullable();
            $table->string('ct_no')->nullable();
            $table->string('ct_gr')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('module_no')->nullable();
            $table->string('parts_no')->nullable();
            $table->string('clr')->nullable();
            $table->integer('parts_qty')->nullable();
            $table->string('vanning')->nullable();
            $table->string('time')->nullable();
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
        Schema::dropIfExists('shipping_instructions');
    }
};
