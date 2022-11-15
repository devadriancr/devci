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
        Schema::create('wherehouse_in_outs', function (Blueprint $table) {
            $table->id();
            $table->string('serial')->nullable();
            $table->string('part_no')->nullable();
            $table->integer('part_qty')->nullable();
            $table->date('date_Scan')->nullable();
            $table->time('time_scan')->nullable();
            $table->boolean('status')->nullable();
            $table->string('shippign');
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
        Schema::dropIfExists('wherehouse_in_outs');
    }
};
