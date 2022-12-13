<?php

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
use App\Http\Controllers\OutputController;
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

    /**
     * Routes Containers
     */
    Route::resource('container', ContainerController::class);

    /**
     * Routes Shipping Instructions
     */
    Route::resource('shipping-instruction', ShippingInstructionController::class);

    /**
     * Routes Travel
     */
    Route::resource('Travel', TravelController::class);
    Route::post('travel-store', [TravelController::class, 'store'])->name('travel.store');
    Route::post('travel-update', [TravelController::class, 'update'])->name('travel.update');

    /**
     * Routes output
     */
    Route::get('output', [OutputController::class, 'index'])->name('output.index');
    Route::POST('output-store', [OutputController::class, 'store'])->name('output.store');
    Route::POST('output-create', [OutputController::class, 'create'])->name('output.create');
    Route::POST('output-update', [OutputController::class, 'update'])->name('output.update');

    /**
     * Routes Consignment Instructions
     */
    Route::get('consignment-instruction-container', [InputController::class, 'consignment_container'])->name('consignment-instruction.container');
    Route::get('consignment-instruction-create', [InputController::class, 'consignment_create'])->name('consignment-instruction.create');
    Route::post('consignment-instruction-store', [InputController::class, 'consignment_store'])->name('consigment-instruction.store');
    Route::post('consignment-instruction-check', [InputController::class, 'consignment_check'])->name('consigment-instruction.check');
    Route::post('consignment-instruction-report-not-found', [InputController::class, 'reportNotFount'])->name('consigment-instruction.not-found');
    Route::post('consignment-instruction-report-found', [InputController::class, 'reportFount'])->name('consigment-instruction.found');
    Route::post('consignment-instruction-finish', [InputController::class, 'consignment_finish'])->name('consigment-instruction.finish');

    /**
     * Route Inventory
     */
    Route::resource('inventory', InventoryController::class);
    Route::get('inventory-upload', [InventoryController::class, 'upload'])->name('inventory.upload');

    /**
     * Route Input
     */
    Route::resource('input', InputController::class);
});
