<?php

use App\Http\Controllers\AttributionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ComputerController;
use Illuminate\Support\Facades\URL;

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

Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth'
], function () {
    Route::post('/',         [AuthController::class, 'login'])->name('authLogin');
    Route::post('/register',  [AuthController::class, 'register']);  
    Route::get('/',          [AuthController::class, 'TestToken']);    
});

Route::group([
    'midleware' => [ 'api'],
    'prefix'    => 'computers'
], function(){
    Route::get('/{date}',        [ComputerController::class, 'get']);
    Route::post('/',             [ComputerController::class, 'create']);
    Route::patch('/{id}/{name}', [ComputerController::class, 'update']);
    Route::delete('/{id}',       [ComputerController::class, 'delete']);
});


Route::group([
    'midleware' => 'api',
    'prefix'    => 'attributions'
], function($router){
    Route::post('/',       [AttributionController::class, 'add']);
    Route::delete('/{id}', [AttributionController::class, 'remove']);
});

Route::group([
    'midleware' => 'api',
    'prefix'    => 'clients'
], function($router){
    Route::get('/',         [ClientController::class, 'get']);
    Route::post('/',        [ClientController::class, 'create']);
    Route::delete('/{id}',  [ClientController::class, 'delete']);
    Route::patch('/{Squery}', [ClientController::class, 'search']);
});