<?php


use App\Http\Controllers\BookController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Support\Facades\Route;


Route::withoutMiddleware([ApiAuthMiddleware::class])->group(function (){
    Route::post('/users/register', [UserController::class, 'register']);
    Route::post('/users/login', [UserController::class, 'login']);

    Route::post('/books/create', [BookController::class,'store']);
});

Route::middleware([ApiAuthMiddleware::class])->group(function (){

    Route::get('/users/current',[UserController::class, 'get']);
    Route::patch('/users/current',[UserController::class, 'update']);
    Route::delete('/users/logout',[UserController::class, 'logout']);

    Route::post('/transaction/borrow', [TransactionController::class,'borrowed']);
    Route::post('/transaction/return', [TransactionController::class,'returned']);
    Route::get('/transaction/get', [TransactionController::class,'get']);
});
