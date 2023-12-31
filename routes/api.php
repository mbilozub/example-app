<?php

use App\Http\Controllers\Api\Contacts\IndexController;
use App\Http\Controllers\Api\Contacts\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => ['auth:sanctum']], static function () {
    Route::get('/user', fn(Request $request) => $request->user());

    Route::get('contacts', IndexController::class);
    Route::post('contacts', StoreController::class);
});
