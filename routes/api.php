<?php

use App\Http\Controllers\BookController;
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

Route::get('books', [BookController::class, 'index']);
Route::post('books/create', [BookController::class, 'store']);
Route::get('books/{id}', [BookController::class, 'show']);
Route::put('books/{id}/update', [BookController::class, 'update']);
Route::delete('books/{id}/delete', [BookController::class, 'destroy']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
