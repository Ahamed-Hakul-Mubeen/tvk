<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AnnouncementController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('admin.auth.login');
});

Route::get('/sample', function() {
    return view('sample');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/announcement/all', [AnnouncementController::class, 'getAnnouncement'])->name('announcement.all');
Route::get('/login', [AdminAuthController::class, 'index'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login');


Route::group(['middleware' => ['auth']], function () {
    // Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        
    // employee Management
    Route::get('/announcement', [AnnouncementController::class, 'index'])->name('announcement');
    Route::post('/announcement/store', [AnnouncementController::class, 'store'])->name('announcement.store');
    Route::post('/announcement/list', [AnnouncementController::class, 'list'])->name('announcement.list');
    Route::post('/announcement/get', [AnnouncementController::class, 'get'])->name('announcement.get');
    Route::post('/announcement/delete', [AnnouncementController::class, 'delete'])->name('announcement.delete');

});
Route::get('/login', function () {
    return view('admin.auth.login');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
