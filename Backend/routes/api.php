<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CalorieCalculatorController;
use App\Http\Controllers\FoodItemController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PlateController;
use App\Http\Controllers\TestFlaskController;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/user/update', [UserController::class, 'update'])->middleware('auth:sanctum');
Route::get('user/all',  [UserController::class, 'index']);
Route::get('user', [UserController::class, 'show'])->middleware('auth:sanctum');
Route::delete('user/delete', [UserController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('register', [RegisterController::class, 'register']);
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LogoutController::class, 'logout'])->middleware('auth:sanctum');
Route::post('set/calories', [CalorieCalculatorController::class, 'calc'])->middleware('auth:sanctum');
Route::post('/scan/plate', [PlateController::class, 'show'])->middleware('auth:sanctum');
Route::post('/add/plate', [PlateController::class, 'store'])->middleware('auth:sanctum');
Route::get('/show/total/', [PlateController::class, 'index'])->middleware('auth:sanctum');
Route::post('/upload-image', [TestFlaskController::class, 'store']);
Route::get('food_items/{search?}',[FoodItemController::class,'index'])->middleware('auth:sanctum');
Route::get('show/history',[PlateController::class , 'history'])->middleware('auth:sanctum');
//Route::get('show/history',[PlateController::class , 'history'])->middleware('auth:sanctum');
Route::post('manual/add',[PlateController::class , 'manual_store'])->middleware('auth:sanctum');
