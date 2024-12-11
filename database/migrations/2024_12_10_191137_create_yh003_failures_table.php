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
        Schema::create('yh003_failures', function (Blueprint $table) {
            $table->id();
            $table->string('H3CONO')->nullable();
            $table->string('H3DDTE')->nullable();
            $table->string('H3DTIM')->nullable();
            $table->string('H3PROD')->nullable();
            $table->string('H3SUCD')->nullable();
            $table->string('H3SENO')->nullable();
            $table->string('H3RQTY')->nullable();
            $table->string('H3CUSR')->nullable();
            $table->string('H3RDTE')->nullable();
            $table->string('H3RTIM')->nullable();
            $table->boolean('status')->nullable();
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
        Schema::dropIfExists('yh003_failures');
    }
};
