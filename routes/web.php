<?php

use App\Http\Controllers\ContainerController;
use App\Http\Controllers\InputController;
use App\Http\Controllers\ItemClassController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ItemTypeController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\MeasurementTypeController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\WarehouseController;
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
     * Routes Consignment Instructions
     */
    Route::get('consignment-instruction-container', [InputController::class, 'consignment_container'])->name('consignment-instruction.container');
    Route::get('consignment-instruction-create', [InputController::class, 'consignment_create'])->name('consignment-instruction.create');
    Route::get('consignment-instruction-store', [InputController::class, 'consignment_store'])->name('consigment-instruction.store');
    Route::get('consignment-instruction-check', [InputController::class, 'consignment_check'])->name('consigment-instruction.check');
});
