<?php

use App\Http\Controllers\Api\ConsignmentInstructionController;
use App\Http\Controllers\Api\ContainerController;
use App\Http\Controllers\Api\MaterialController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('containers', [ContainerController::class, 'index']);

Route::post('scanned-material', [MaterialController::class, 'store']);

Route::post('check-material', [ConsignmentInstructionController::class, 'checkMaterial']);

Route::post('material-exit', [MaterialController::class, 'materialExit']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
