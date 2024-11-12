<?php

use App\Http\Controllers\API\V1\Orders\MakeOrderController;
use Illuminate\Support\Facades\Route;

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::prefix('/v1')->group(function () {
    Route::prefix('/orders')->group(function () {
        Route::post('/', MakeOrderController::class);
    });
});
