<?php
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
