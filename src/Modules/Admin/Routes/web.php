<?php




use Modules\Admin\Http\Controllers\Auth\AuthenticatedSessionController;
use Modules\Admin\Http\Controllers\ContactsController;
use Modules\Admin\Http\Controllers\DashboardController;
use Modules\Admin\Http\Controllers\ImagesController;
use Modules\Admin\Http\Controllers\OrganizationsController;
use Modules\Admin\Http\Controllers\PostsController;
use Modules\Admin\Http\Controllers\ReportsController;
use Modules\Admin\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');
Route::post('/upload_image', 'UploadController@upload')->name('upload_image');

Route::middleware('superadmin')->group(function() {
    Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Dashboard

    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard')
        ->middleware('auth');

    // Users

    Route::get('users', [UsersController::class, 'index'])
        ->name('users')
        ->middleware('auth');

    Route::get('users/create', [UsersController::class, 'create'])
        ->name('users.create')
        ->middleware('auth');

    Route::post('users', [UsersController::class, 'store'])
        ->name('users.store')
        ->middleware('auth');

    Route::get('users/{user}/edit', [UsersController::class, 'edit'])
        ->name('users.edit')
        ->middleware('auth');

    Route::put('users/{user}', [UsersController::class, 'update'])
        ->name('users.update')
        ->middleware('auth');

    Route::delete('users/{user}', [UsersController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('auth');

    Route::put('users/{user}/restore', [UsersController::class, 'restore'])
        ->name('users.restore')
        ->middleware('auth');
    if(file_exists(__DIR__ . "/admin.php")) include_once __DIR__ . "/admin.php";
});
Route::post('api/posts', [PostsController::class, 'api'])
    ->name('api.posts');
Route::post('api/posts/upload', [\Modules\Admin\Http\Controllers\UploadController::class, 'upload'])
    ->name('api.posts.upload');


