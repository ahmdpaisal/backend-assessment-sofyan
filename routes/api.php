<?php

use App\Http\Controllers\AuthController;
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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
});

//Using sanctum
Route::middleware('auth:sanctum')->group(function () {

    //Logout Route
    Route::controller(AuthController::class)->group(function () {
        Route::get('current-user', 'currentUser');
        Route::post('logout', 'logout');
    });

    //Book Routes
    Route::controller(BookController::class)->group(function () {
        Route::get('/books', 'index');
        Route::post('/books/create', 'store');
        Route::get('/books/{id}', 'show');
        Route::put('/books/{id}/update', 'update');
        Route::delete('/books/{id}/delete', 'destroy');
    });
    

    //Member Routes
    Route::controller(MemberController::class)->group(function () {
        Route::get('/anggota', 'index');
        Route::post('/anggota/create', 'store');
        Route::get('/anggota/{id}', 'show');
        Route::put('/anggota/{id}/update', 'update');
        Route::delete('/anggota/{id}/delete', 'destroy');
    });
});
