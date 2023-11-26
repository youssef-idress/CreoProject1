<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\UserController;

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

Route::post('/auth/login', [SessionController::class, 'login'])->name('auth.login')->middleware('auth:sanctum');
Route::post('/auth/logout', [SessionController::class, 'logout'])->name('auth.logout')->middleware('auth:sanctum');
Route::middleware(['auth:sanctum']->group(function() {
  Route::resource('users', UserController::class);
}));
?>
