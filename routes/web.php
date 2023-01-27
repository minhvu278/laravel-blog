<?php

use App\Http\Controllers\Home\AboutController;
use App\Http\Controllers\Home\HomeSlideController;
use App\Http\Controllers\AdminController;
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

Route::get('/', function () {
    return view('frontend.index');
});

Route::get('/dashboard', function () {
    return view('admin.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::controller(AdminController::class)->group(function () {
    Route::get('/admin/logout', 'destroy')->name('admin.logout');
    Route::get('/admin/profile', 'profile')->name('admin.profile');
    Route::get('/edit/profile', 'editProfile')->name('edit.profile');
    Route::post('/store/profile', 'storeProfile')->name('store.profile');

    Route::get('/change/password', 'changePassword')->name('change.password');
    Route::post('/update/password', 'updatePassword')->name('update.password');
});

Route::controller(HomeSlideController::class)->group(function () {
    Route::get('/home/slide', 'homeSlide')->name('home.slide');
    Route::post('/update/slide', 'updateSlide')->name('update.slide');
});

Route::controller(AboutController::class)->group(function () {
    Route::get('/about/page', 'index')->name('about.page');
    Route::post('/update/about', 'update')->name('update.about');
});

require __DIR__.'/auth.php';
