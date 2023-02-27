<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\xxxxxxController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::group(['middleware' => 'auth'], function () {
    //履歴書作成
    Route::get('/mypage/resume/view', [MypageResumeCreateController::class, 'view'])->name('mypage.resume.view');
    //履歴書作成
    Route::post('/mypage/resume/create', [MypageResumeCreateController::class, 'wordCreate'])->middleware('resume')->name('mypage.resume.create');
});


require __DIR__ . '/auth.php';
