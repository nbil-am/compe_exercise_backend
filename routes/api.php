<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
// auth user
Route::post('register',[AuthController::class,'register']);
Route::post('login',[AuthController::class,'login']);
Route::post('logout',[AuthController::class,'logOut'])->middleware('auth:sanctum');
// post crrud
Route::post('add/post',[PostController::class,'add'])->middleware('auth:sanctum');
Route::get('all/posts',[PostController::class,'getAll'])->middleware('auth:sanctum');
Route::put('edit/post/{id}',[PostController::class,'edit'])->middleware('auth:sanctum');
Route::delete('delete/post/{id}',[PostController::class,'delete'])->middleware('auth:sanctum');
// like and comment
Route::post('like',[PostController::class,'like'])->middleware('auth:sanctum');
Route::post('comment',[PostController::class,'comment'])->middleware('auth:sanctum');