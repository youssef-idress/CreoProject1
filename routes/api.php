<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

Route::post('/auth/register', [UserController::class, 'createUser'])->name('auth.register');
Route::post('/auth/login', [UserController::class, 'login'])->name('auth.login');
Route::post('/auth/logout', [UserController::class, 'logout'])->name('auth.logout')->middleware('auth:sanctum');

?>
