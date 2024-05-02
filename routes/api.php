<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

// blog table

//public route
Route::get('blogs/all', [BlogController::class, 'all']);

//private  rotues
Route::prefix('blogs')->middleware('auth:sanctum')->controller(BlogController::class)->group(function () {
    Route::post('/add', 'add');
    Route::put('/edit/{id}', 'edit');
    Route::delete('/delete/{id}', 'delete');
    Route::post('/{id}/comment/store', 'storeComment');
});

//e.o blog table