<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/addItems',[CartController::class,'addItems']);
    Route::get('/getItems',[CartController::class,'getItems']);
    Route::post('/deleteItem',[CartController::class,'deleteItem']);
    Route::patch('/updateItem',[CartController::class,'updateItem']);
});


Route::get('/categories', [\App\Http\Controllers\Api\CategoryController::class, 'index']);


Route::get('/products', [\App\Http\Controllers\Api\ProductController::class, 'index']);
Route::post('/getToken', [\App\Http\Controllers\Api\Payment\ZAuth::class, 'getToken']);

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);
