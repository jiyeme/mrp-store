<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;

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
    return view('index');
});

Route::get('/hello', function () {
    return "Hello World!";
});

Route::get('/Store/App/list/slug/{slug}/{page?}', [AppController::class, 'list', 'page']);

Route::get('/upload', function () {
    return view('upload');
});
