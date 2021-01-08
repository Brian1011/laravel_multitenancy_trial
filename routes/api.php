<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\UnitController;
use \App\Http\Controllers\AuthController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::group([], function (){
//    Route::get('/units', [UnitController::class, 'getAll']);
//
//});

Route::group(['middleware' => 'auth:api'], function (){
    Route::get('/units', [UnitController::class, 'getAll']);
    Route::get('/userPermissions', [AuthController::class, 'getUserPermissions']);
});

Route::group([], function (){
    //Route::get('/units', [UnitController::class, 'getAll']);
    Route::post('/signup', [AuthController::class,'signUp']);
    Route::post('/login', [AuthController::class, 'mobileLogin']);
});
