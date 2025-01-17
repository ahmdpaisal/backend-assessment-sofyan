<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\MemberController;
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

Route::controller(BookController::class)->group(function () {
    Route::get('/books', 'index');
    Route::post('/books/create', 'store');
    Route::get('/books/{id}', 'show');
    Route::put('/books/{id}/update', 'update');
    Route::delete('/books/{id}/delete', 'destroy');
});

Route::controller(MemberController::class)->group(function () {
    Route::get('/anggota', 'index');
    Route::post('/anggota/create', 'store');
    Route::get('/anggota/{id}', 'show');
    Route::put('/anggota/{id}/update', 'update');
    Route::delete('/anggota/{id}/delete', 'destroy');
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
