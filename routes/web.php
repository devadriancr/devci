<?php

use App\Http\Controllers\ConsignmentInstructionController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\WHExtInOutController;
use App\Http\Controllers\ItemClassController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ShippingInstructionController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\WarehouseController;
use App\Models\TransactionType;
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

Route::get('/', function () {
    return view('welcome');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');


Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::view('forms', 'forms')->name('forms');
    Route::view('cards', 'cards')->name('cards');
    Route::view('charts', 'charts')->name('charts');
    Route::view('buttons', 'buttons')->name('buttons');
    Route::view('modals', 'modals')->name('modals');
    Route::view('tables', 'tables')->name('tables');
    Route::view('calendar', 'calendar')->name('calendar');

    /**
     * Routes Container
     */
    Route::resource('container', ContainerController::class);

    /**
     * Routes Warehouse
     */
    Route::resource('warehouse', WarehouseController::class);

     /**
     * Routes  In and Out Wherehouse extern
     */
    // Route::resource('Scan-In_Out',WHExtInOutController::class);
    // Route::POST('Scan-In_Out', [WHExtInOutController::class, 'store'])->name('WHExtInOutController.store');
    // Route::POST('shipping',[WHExtInOutController::class, 'shipping'])->name('WHExtInOutController.shipping');
    // Route::POST('saveshipping',[WHExtInOutController::class, 'saveshipping'])->name('WHExtInOutController.saveshipping');
    // Route::get('export', [WHExtInOutController::class, 'export'])->name('WHExtInOutController.export');
    // Route::get('exportdetail', [WHExtInOutController::class, 'exportDetail'])->name('WHExtInOutController.exportDetail');
    // Route::resource('shipping-instruction', ShippingInstructionController::class);

    /**
     * Routes Item Class
     */
    // Route::resource('item-class', ItemClassController::class);
    // Route::get('item-class-upload', [ItemClassController::class, 'upload']);

    /**
     * Routes Item
     */
    // Route::resource('item', ItemController::class);
    // Route::get('item-upload', [ItemController::class, 'upload']);

    /**
     * Routes Transaction Types
     */
    // Route::resource('transaction-type', TransactionTypeController::class);


});

