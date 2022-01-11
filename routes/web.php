<?php

use Illuminate\Foundation\Application;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AppController;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');

Route::get('/hello', function () {
    return "Hello World!";
});

Route::get('/list', function () {
    return view('index');
});

Route::get('/Store/App/list/slug/{slug}/{page?}', [AppController::class, 'list']);

Route::get('/Store/App/info/{id}', [AppController::class, 'info']);

Route::get('/upload', function () {
    return view('upload');
});

Route::get('/support', function () {
    return view('support');
});

Route::post('api/download', [ApiController::class, 'download']);

Route::any('/api.php', [ApiController::class, 'api']);
