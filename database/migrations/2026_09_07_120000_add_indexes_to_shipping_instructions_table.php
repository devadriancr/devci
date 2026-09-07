<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('shipping_instructions', function (Blueprint $table) {
            // Cubre ShippingInstructionController@index: where('status', 1)->orderBy('id', 'DESC')
            $table->index(['status', 'id'], 'idx_shipping_status_id');

            // Cubre CheckQRCodeRegistrationJob, QRCodeConsignmentImport y noFound():
            // where container/arrival_date/arrival_time/part_no/serial
            $table->index(['container', 'arrival_date', 'arrival_time', 'part_no', 'serial'], 'idx_shipping_container_arrival_part_serial');

            // Cubre storeScan/storeBarCode: where serial + search
            $table->index(['serial', 'search'], 'idx_shipping_serial_search');
        });
    }

    public function down()
    {
        Schema::table('shipping_instructions', function (Blueprint $table) {
            $table->dropIndex('idx_shipping_status_id');
            $table->dropIndex('idx_shipping_container_arrival_part_serial');
            $table->dropIndex('idx_shipping_serial_search');
        });
    }
};
