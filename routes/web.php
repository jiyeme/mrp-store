<?php

use Illuminate\Foundation\Application;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\AppMagController;
use App\Http\Controllers\ResServerController;
use App\Http\Controllers\TestController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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
Route::get('/test', [TestController::class, 'index']);

Route::get('/', function () {
    return Inertia::render('Index', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('index');

Route::get('/old', function () {
    return view('index');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/list', function () {
    return view('index');
});

Route::get('/App/list', [AppController::class, 'list'])->name('applist');
Route::get('/App/info/{id}', [AppController::class, 'info'])->name('appinfo');

Route::get('/upload', function () {
    return Inertia::render('App/upload');
})->name('upload');

Route::get('/support', function () {
    return view('support');
})->name('support');

Route::post('/api/download', [ApiController::class, 'download']);

Route::any('/api.php', [ApiController::class, 'api']);

Route::get('/dash/appMag', [AppMagController::class, 'appList'])->name('appMag');

Route::get('/dash/appDetail/{id}', [AppMagController::class, 'getAppDetail'])->name('appDetail');
Route::delete('/dash/appDelete/{id}', [AppMagController::class, 'delApp'])->name('appDelete');
Route::delete('/dash/verDelete/{id}', [AppMagController::class, 'delVer'])->name('verDelete');

// 资源下载服务
Route::post('/simpleDownload', [ResServerController::class, 'simpleDownload']);
Route::post('/continueDownload', [ResServerController::class, 'continueDownload']);
