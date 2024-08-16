<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('profile', [AuthController::class, 'userProfile']);
    Route::put('profile', [AuthController::class, 'updateProfile']);
    Route::post('logout', [AuthController::class, 'logout']);
});



Route::apiResource('books', BookController::class)->only(['index', 'show']);
Route::apiResource('author', AuthorController::class)->only(['index', 'show']);
Route::apiResource('category', CategoryController::class)->only(['index', 'show']);
Route::apiResource('transaction', TransactionController::class)->only(['index', 'show']);


Route::middleware(['role:admin'])->group(function () {
    Route::apiResource('books', BookController::class)->except(['index', 'show']);
    Route::apiResource('author', AuthorController::class)->except(['index', 'show']);
    Route::apiResource('category', CategoryController::class)->except(['index', 'show']);
    Route::apiResource('transaction', TransactionController::class)->except(['index', 'show']);
});
