<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\UploadController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('upload/md5Check', [UploadController::class, 'md5Check']);

Route::post('upload/mrp', [UploadController::class, 'mrp']);

Route::post('upload/jar', [UploadController::class, 'jar']);


