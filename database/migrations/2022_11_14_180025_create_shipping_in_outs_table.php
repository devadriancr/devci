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
        Schema::create('shipping_in_outs', function (Blueprint $table) {
            $table->id();
            $table->string('usuario');
            $table->date('fecha_shi');
            $table->time('hora_shi');
            $table->string('transfer_flag');
            $table->String('wharehouse');
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
        Schema::dropIfExists('shipping_in_outs');
    }
};
