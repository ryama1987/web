<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\checkingController;
use App\Http\Controllers\FullTextIndexController;

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

Route::get('/greeting', function () {
    return 'Hello World';
});

Route::get('check1', [checkingController::class,'check1']);
Route::get('check2', [checkingController::class,'check2']);
Route::get('card', [checkingController::class,'getCardData']);

Route::get('search', [FullTextIndexController::class,'getjobData']);


