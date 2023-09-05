<?php
use Modules\Admin\Http\Controllers\DemoController;

use Modules\Admin\Http\Controllers\CategoryPostController;

// Posts
use Modules\Admin\Http\Controllers\PostsController;

Route::get('posts', [PostsController::class, 'index'])->name('posts')->middleware('auth');
Route::get('posts/create', [PostsController::class, 'create'])->name('posts.create')->middleware('auth');
Route::post('posts', [PostsController::class, 'store'])->name('posts.store')->middleware('auth');
Route::get('posts/{model}/edit', [PostsController::class, 'edit'])->name('posts.edit')->middleware('auth');
Route::put('posts/{model}', [PostsController::class, 'update'])->name('posts.update')->middleware('auth');
Route::delete('posts/{model}', [PostsController::class, 'destroy'])->name('posts.destroy')->middleware('auth');
Route::put('posts/{model}/restore', [PostsController::class, 'restore'])->name('posts.restore')->middleware('auth');
Route::post('/upload', 'UploadController@upload')->name('upload');
Route::post('/destroy', 'UploadController@destroy')->name('destroy');
Route::middleware('auth')->get('/uploads/lastest/{limit}','UploadController@getLastUpload')->name('upload.lastest');


//CategoryPostController
Route::get('categoryposts', [CategoryPostController::class, 'index'])->name('categoryposts')->middleware('auth');
Route::get('categoryposts/create', [CategoryPostController::class, 'create'])->name('categoryposts.create')->middleware('auth');
Route::post('categoryposts', [CategoryPostController::class, 'store'])->name('categoryposts.store')->middleware('auth');
Route::get('categoryposts/{model}/edit', [CategoryPostController::class, 'edit'])->name('categoryposts.edit')->middleware('auth');
Route::put('categoryposts/{model}', [CategoryPostController::class, 'update'])->name('categoryposts.update')->middleware('auth');
Route::delete('categoryposts/{model}', [CategoryPostController::class, 'destroy'])->name('categoryposts.destroy')->middleware('auth');
Route::put('categoryposts/{model}/restore', [CategoryPostController::class, 'restore'])->name('categoryposts.restore')->middleware('auth');



//DemoController
Route::get('demo', [DemoController::class, 'create'])->name('demo')->middleware('auth');
Route::get('demo/api', [DemoController::class, 'api'])->name('api.demo')->middleware('auth');
Route::post('demo', [DemoController::class, 'store'])->name('demo.store')->middleware('auth');
