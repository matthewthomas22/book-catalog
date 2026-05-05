<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AuthorController;
use illuminate\Support\Facades\Route;

Route::apiResource('authors', AuthorController::class);

Route::get('/test', function(){
    return 'API WORKING';
});