<?php

use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('blogs')->controller(BlogController::class)->group(function(){
    Route::get('/all','all');
    Route::post('/add', 'add');
    Route::put('/edit/{id}', 'edit');
    Route::delete('/delete/{id}', 'delete');
    Route::post('/{id}/comment/store', 'storeComment');
});