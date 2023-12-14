<?php

use App\Http\Controllers\ConsignmentInstructionController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ItemClassController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MeasurementTypeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShippingInstructionController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\TravelController;
use App\Http\Controllers\DeiveryProductionController;
use App\Http\Controllers\DeliverySupplierController;
use App\Http\Controllers\ManualAdjustments;
use App\Http\Controllers\OutputController;
use App\Http\Controllers\RequestListController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');


Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::view('/', 'dashboard');
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('forms', 'forms')->name('forms');
    Route::view('cards', 'cards')->name('cards');
    Route::view('charts', 'charts')->name('charts');
    Route::view('buttons', 'buttons')->name('buttons');
    Route::view('modals', 'modals')->name('modals');
    Route::view('tables', 'tables')->name('tables');
    Route::view('calendar', 'calendar')->name('calendar');

    /**
     * Routes Roles
     */
    Route::resource('role', RoleController::class);

    /**
     * Routes Users
     */
    Route::resource('user', UserController::class);


    /**
     * Route Measurement Types
     */
    Route::resource('measurement-type', MeasurementTypeController::class);

    /**
     * Routes Item Types
     */
    Route::resource('item-type', ItemTypeController::class);

    /**
     * Routes Item Classes
     */
    Route::resource('item-class', ItemClassController::class);
    Route::get('item-class-upload', [ItemClassController::class, 'upload'])->name('item-class.upload');

    /**
     * Routes Warehouses
     */
    Route::resource('warehouse', WarehouseController::class);
    Route::get('warehouse-upload', [WarehouseController::class, 'upload'])->name('warehouse.upload');

    /**
     * Routes Locations
     */
    Route::resource('location', LocationController::class);
    Route::get('location-upload', [LocationController::class, 'upload'])->name('location.upload');


    /**
     * Routes Transaction Types
     */
    Route::resource('transaction-type', TransactionTypeController::class);
    Route::get('transaction-type-upload', [TransactionTypeController::class, 'upload'])->name('transaction-type.upload');

    /**
     * Routes Items
     */
    Route::resource('item', ItemController::class);
    Route::get('item-upload', [ItemController::class, 'upload'])->name('item.upload');
    Route::get('item-safety-stock', [ItemController::class, 'safetyStock'])->name('item.safety-stock');

    /**
     * Routes Containers
     */
    Route::resource('container', ContainerController::class);

    /**
     * Routes Shipping Instructions
     */
    Route::resource('shipping-instruction', ShippingInstructionController::class);

    Route::get('report-si', [ShippingInstructionController::class, 'reportShipping'])->name('shipping-instruction.report-si');
    Route::post('download-si', [ShippingInstructionController::class, 'downloadShipping'])->name('shipping-instruction.download-si');
    Route::post('not-found-si', [ShippingInstructionController::class, 'noFound'])->name('shipping-instruction.not-found-si');

    Route::get('scan', [ShippingInstructionController::class, 'scan'])->name('shipping-instruction.scan');
    Route::post('store-scan', [ShippingInstructionController::class, 'storeScan'])->name('shipping-instruction.store-scan');
    Route::get('scan-barcode', [ShippingInstructionController::class, 'scanBarCode'])->name('shipping-instruction.scan-barcode');
    Route::post('save-barcode', [ShippingInstructionController::class, 'storeBarCode'])->name('shipping-instruction.save-barcode');

    Route::get('report-consignments', [ShippingInstructionController::class, 'reportConsignments'])->name('shipping-instruction.report-consignments');
    Route::post('download-consignment', [ShippingInstructionController::class, 'downloadConsignment'])->name('shipping-instruction.download-consignments');

    /**
     * Routes Travel
     */
    Route::resource('Travel', TravelController::class);
    Route::post('travel-store', [TravelController::class, 'store'])->name('travel.store');
    Route::post('travel-update', [TravelController::class, 'update'])->name('travel.update');
    Route::POST('travel-export', [TravelController::class, 'export'])->name('travel.export');
    Route::post('travel-new', [TravelController::class, 'index'])->name('travel.new');
    Route::get('travel-show', [TravelController::class, 'show'])->name('travel.show');
    /**
     * Routes output external House
     */
    Route::resource('output', TravelController::class);
    Route::get('output-search', [OutputController::class, 'search'])->name('output.search');
    Route::POST('output-store', [OutputController::class, 'store'])->name('output.store');
    Route::POST('output-create1', [OutputController::class, 'create1'])->name('output.create1');
    Route::POST('output-createacan', [OutputController::class, 'createscan'])->name('output.createscan');
    Route::POST('output-update', [OutputController::class, 'update'])->name('output.update');
    Route::POST('output-destroy', [OutputController::class, 'destroy'])->name('output.destroy');
    Route::POST('output-scanbar', [OutputController::class, 'scanbar'])->name('output.scanbar');
    Route::POST('output-return', [OutputController::class, 'returnitem'])->name('output.returnitem');
    Route::get('output', [OutputController::class, 'index'])->name('output.index');
    /**
     * Routes output Delivery line
     */
    Route::resource('Delivery', DeiveryProductionController::class);
    Route::POST('DeliveryProduction-store', [DeiveryProductionController::class, 'store'])->name('Delivery.store');
    Route::POST('DeliveryProduction-create', [DeiveryProductionController::class, 'create'])->name('Delivery.create');
    Route::POST('DeliveryProduction-update', [DeiveryProductionController::class, 'update'])->name('Delivery.update');
    Route::POST('DeliveryProduction-updatebar', [DeiveryProductionController::class, 'updatebar'])->name('Delivery.updatebar');
    Route::POST('Delivery-export', [DeiveryProductionController::class, 'export'])->name('Delivery.export');
    Route::POST('Delivery-destroy', [DeiveryProductionController::class, 'destroy'])->name('Delivery.destroy');
    Route::POST('Delivery-scanbar', [DeiveryProductionController::class, 'scanbar'])->name('Delivery.scanbar');
    Route::POST('Delivery-scanqr', [DeiveryProductionController::class, 'scanqr'])->name('Delivery.scanqr');

    /**
     * Routes Consignment Instructions
     */
    Route::get('consignment-instruction-container', [ConsignmentInstructionController::class, 'consignment_container'])->name('consignment-instruction.container');
    Route::get('consignment-instruction-create', [ConsignmentInstructionController::class, 'consignment_create'])->name('consignment-instruction.create');
    Route::post('consignment-instruction-store', [ConsignmentInstructionController::class, 'consignment_store'])->name('consigment-instruction.store');
    Route::post('consignment-instruction-check', [ConsignmentInstructionController::class, 'consignment_check'])->name('consigment-instruction.check');
    Route::post('consignment-instruction-report-not-found', [ConsignmentInstructionController::class, 'reportNotFount'])->name('consigment-instruction.not-found');
    Route::post('consignment-instruction-report-found', [ConsignmentInstructionController::class, 'reportFount'])->name('consigment-instruction.found');
    Route::post('consignment-instruction-finish', [ConsignmentInstructionController::class, 'consignment_finish'])->name('consigment-instruction.finish');

    Route::get('data-upload-index', [ConsignmentInstructionController::class, 'data_upload_index'])->name('consigment-instruction.data-upload-index');
    Route::post('data-upload-store', [ConsignmentInstructionController::class, 'data_upload_store'])->name('consigment-instruction.data-upload-store');
    Route::get('data-upload-inventory', [ConsignmentInstructionController::class, 'data_upload_inventory'])->name('consigment-instruction.data-upload-inventory');

    Route::post('barcode', [ConsignmentInstructionController::class, 'barcode'])->name('consigment-instruction.barcode');
    Route::post('store-barcode', [ConsignmentInstructionController::class, 'storeBarcode'])->name('consigment-instruction.store-barcode');

    Route::get('consigment-barcode-index', [ConsignmentInstructionController::class, 'consigmentBarcodeIndex'])->name('consigment-instruction.consigment-barcode-index');
    Route::post('consignment-barcode-store', [ConsignmentInstructionController::class, 'consignmentBarcodeStore'])->name('consigment-instruction.consignment-barcode-store');

    /**
     * Route Inventory
     */
    Route::resource('inventory', InventoryController::class);
    Route::get('inventory-upload', [InventoryController::class, 'upload'])->name('inventory.upload');
    Route::post('upload-file', [InventoryController::class, 'uploadFile'])->name('inventory.upload-file');
    Route::get('duplicate-entries', [InventoryController::class, 'duplicateEntries'])->name('inventory.duplicate-entries');
    Route::post('duplicate-entry-file', [InventoryController::class, 'duplicateEntryFile'])->name('inventory.duplicate-entry-file');
    // Route::get('inventory-opening-balance', [InventoryController::class, 'openingBalance'])->name('inventory.opening-balance');

    /**
     * Route Input
     */
    Route::resource('input', InputController::class);
    Route::get('item-report', [InputController::class, 'itemReport'])->name('input.item-report');
    Route::post('download-report', [InputController::class, 'downloadItemReport'])->name('input.download-report');

    /**
     * Route report request list
     *
     */
    Route::resource('Requestlist', RequestListController::class);
    Route::get('Requestlist-list', [RequestListController::class, 'list_order'])->name('RequestList.list_order');
    Route::get('Requestlist-send', [RequestListController::class, 'send'])->name('RequestList.send');
    Route::get('Requestlist-receipt', [RequestListController::class, 'receipt'])->name('RequestList.receipt');
    Route::get('Requestlist-orderdetail', [RequestListController::class, 'order_detail'])->name('RequestList.order_detail');
    Route::Post('Requestlist-order', [RequestListController::class, 'order_detail'])->name('RequestList.order');
    Route::Post('Requestlist-quitorder', [RequestListController::class, 'quitorder'])->name('RequestList.quit');
    Route::Post('Requestlist-create', [RequestListController::class, 'create_order'])->name('RequestList.create_order');
    Route::Post('Requestlist-export', [RequestListController::class, 'export'])->name('RequestList.export');
    Route::Post('Requestlist-Quitorder', [RequestListController::class, 'Quitorderinformation'])->name('RequestList.Quitorderinformation');

    /**
     * Route Supplier
     */
    Route::resource('supplier', SupplierController::class);

    /**
     * Route Supplier
     */
    Route::resource('ManualAdjustment', ManualAdjustments::class);

    /**
     * Delivery Supplier
     */
    Route::resource('DeliverySupplier', DeliverySupplierController::class);
    Route::POST('DeliverySupplier-store', [DeliverySupplierController::class, 'store'])->name('DeliverySupplier.store');
    Route::POST('DeliverySupplier-create', [DeliverySupplierController::class, 'create'])->name('DeliverySupplier.create');
    Route::POST('DeliverySupplier-update', [DeliverySupplierController::class, 'update'])->name('DeliverySupplier.update');
    Route::POST('DeliverySupplier-updatebar', [DeliverySupplierController::class, 'updatebar'])->name('DeliverySupplier.updatebar');
    Route::POST('DeliverySupplier-export', [DeliverySupplierController::class, 'export'])->name('DeliverySupplier.export');
    Route::POST('DeliverySupplier-destroy', [DeliverySupplierController::class, 'destroy'])->name('DeliverySupplier.destroy');
    Route::POST('DeliverySupplier-scanqr', [DeliverySupplierController::class, 'scanqr'])->name('DeliverySupplier.scanqr');

    /**
     *
     */
    Route::get('supplier-input', [SupplierController::class, 'indexSupplierInput'])->name('supplier-input.index');
    Route::get('supplier-output', [SupplierController::class, 'indexSupplierOutput'])->name('supplier-output.index');
});
