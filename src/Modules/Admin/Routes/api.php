<?php

use Illuminate\Http\Request;
use Modules\Admin\Http\Controllers\CategoryPostController;

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
Route::get('categoryposts', [CategoryPostController::class, 'apiSearch'])->name('api.categoryposts');


