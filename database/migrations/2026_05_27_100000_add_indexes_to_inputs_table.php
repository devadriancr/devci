<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('inputs', function (Blueprint $table) {
            $table->index(['delivery_production_id', 'return_scan', 'created_at'], 'idx_inputs_delivery_return_created');
            $table->index(['serial', 'supplier', 'item_id'], 'idx_inputs_serial_supplier_item');
            $table->index(['supplier', 'serial', 'created_at'], 'idx_inputs_supplier_serial_created');
        });

        // Índice covering para la vista consignment_data
        // Cubre el WHERE (location_id, transaction_type_id, no_order) e incluye las columnas del SELECT
        DB::statement("
            CREATE NONCLUSTERED INDEX idx_inputs_consignment_view
            ON inputs (location_id, transaction_type_id, no_order)
            INCLUDE (id, item_id, item_quantity, supplier, serial, type_consignment)
        ");
    }

    public function down()
    {
        Schema::table('inputs', function (Blueprint $table) {
            $table->dropIndex('idx_inputs_delivery_return_created');
            $table->dropIndex('idx_inputs_serial_supplier_item');
            $table->dropIndex('idx_inputs_supplier_serial_created');
        });

        DB::statement("DROP INDEX IF EXISTS idx_inputs_consignment_view ON inputs");
    }
};
