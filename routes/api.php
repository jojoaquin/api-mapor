<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\IndexController;
use App\Http\Controllers\Api\InformationController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::post('/create', [InformationController::class, 'createInformation']);
Route::delete('/delete/{slug}', [InformationController::class, 'deleteInformation']);
Route::put('/edit/{slug}', [InformationController::class, 'editInformation']);
Route::post('/editfile/{slug}', [InformationController::class, 'editInformationFile']);

Route::get('/news', [IndexController::class, 'news']);
Route::get('/news/{slug}', [IndexController::class, 'newsById']);
