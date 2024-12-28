<?php

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;

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

Route::middleware(["auth:sanctum"])->get("/user", function (Request $request) {
    $user = $request->user();
    $user->profile_picture = $user->profile_picture
        ? "data:image/jpeg;base64," . base64_encode($user->profile_picture)
        : null;
    return $request->user();
});

// blog table

//public route
Route::get("/blogs/all", [BlogController::class, "all"]);
Route::get("/blogs/popular", [BlogController::class, "latest"]);
Route::get("/blogs/{id}", [BlogController::class, "show"]);
Route::get("/blogs/category/{category_id}", [
    BlogController::class,
    "filterByCategory",
]);

Route::get("/categories/all", [CategoryController::class, "all"]);

Route::get("/comments/{blog_id}", [CommentController::class, "all"]);

//private  rotues
Route::prefix("blogs")
    ->middleware("auth:sanctum")
    ->controller(BlogController::class)
    ->group(function () {
        // Route::get('/all', 'all');
        Route::post("/store", "store");
        Route::put("/edit/{id}", "edit");
        Route::delete("/delete/{id}", "delete");
        Route::post("/{id}/subscription", "subscription");
        // Route::post('/{id}/comment/store', 'storeComment');
        // Route::put('/comment/edit/{id}', 'updateComment');
        // Route::delete('/comment/delete/{id}', 'deleteComment');
    });

//e.o blog table

// comment table
Route::prefix("comments")
    ->middleware("auth:sanctum")
    ->controller(CommentController::class)
    ->group(function () {
        Route::post("{blogId}/store", "store");
        Route::put("/edit/{id}", "edit");
        Route::delete("/delete/{id}", "delete");
    });
