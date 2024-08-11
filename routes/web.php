<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'show'])->name('home.metrics');
Route::get('/history', [HomeController::class, 'showHistory'])->name('home.history');
Route::get('/fetch-metrics', [HomeController::class, 'fetchMetrics'])->name('home.fetchMetrics');
Route::post('/save-metrics', [HomeController::class, 'saveMetrics'])->name('home.saveMetrics');

