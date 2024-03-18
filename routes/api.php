<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

//Route::group([
   // 'middleware' => 'api',
    //'prefix' => 'auth'

//], );

Route::group(['middleware'=>'api'],function($routes){
    Route::post('/register',[UserController:: class, 'register']);
    Route::post('/login',[UserController::class, 'login']);
    Route::post('/profile',[UserController::class, 'profile']);
    Route::post('/refresh',[UserController::class, 'refresh']);
    Route::post('/logout',[UserController::class, 'logout']);

});