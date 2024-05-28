<?php



use App\Http\Controllers\UserController;
use App\Http\Middleware\ApiAuthMiddleware;
use Illuminate\Support\Facades\Route;


Route::withoutMiddleware([ApiAuthMiddleware::class])->group(function (){
    Route::post('/users/register', [UserController::class, 'register']);
    Route::post('/users/login', [UserController::class, 'login']);
});

Route::middleware([ApiAuthMiddleware::class])->group(function (){
    Route::get('/users/current',[UserController::class, 'get'])->name('current');
    Route::patch('/users/current',[UserController::class, 'update']);
});
