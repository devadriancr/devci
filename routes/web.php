<?php

use App\Http\Controllers\ConsignmentInstructionController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\ShippingInstructionController;
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
     * Route Shipping Instruction
     */
    Route::resource('shipping-instruction', ShippingInstructionController::class);

    /**
     * Route Consigment Instruction
     */
    Route::resource('consigment-instruction', ConsignmentInstructionController::class);
    Route::get('container-ci', [ConsignmentInstructionController::class, 'container'])->name('consigment-instruction.container');
    Route::post('check-ci', [ConsignmentInstructionController::class, 'check'])->name('consigment-instruction.check');

    /**
     * Route Container
     */
    Route::resource('container', ContainerController::class);
});
