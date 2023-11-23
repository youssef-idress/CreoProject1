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

Route::post('/auth/register', [UserController::class, 'createUser'])->name('auth.register');
Route::post('/auth/login', [SessionController::class, 'login'])->name('auth.login')->middleware('auth:sanctum');
Route::post('/auth/logout', [SessionController::class, 'logout'])->name('auth.logout')->middleware('auth:sanctum');
Route::post('/auth/Delete', [UserController::class, 'Delete'])->name('auth.delete')->middleware('auth:sanctum');
Route::post('auth/Update',[UserController::class, 'Update'])->name('auth.update')->middleware('auth:sanctum');
Route::post('auth/Read', [UserController::class, 'read'])->name('auth.read')->middleware('auth:sanctum');
?>
