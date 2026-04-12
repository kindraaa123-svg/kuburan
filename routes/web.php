<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
Route::get('/dashboard/data-blok', [HomeController::class, 'dataBlok'])->name('dashboard.data-blok');
Route::get('/dashboard/data-plot', [HomeController::class, 'dataPlot'])->name('dashboard.data-plot');
Route::get('/dashboard/data-almarhum', [HomeController::class, 'dataAlmarhum'])->name('dashboard.data-almarhum');
Route::post('/dashboard/data-almarhum', [HomeController::class, 'storeDataAlmarhum'])->name('dashboard.data-almarhum.store');
Route::put('/dashboard/data-almarhum/{deceased}', [HomeController::class, 'updateDataAlmarhum'])->name('dashboard.data-almarhum.update');
Route::get('/dashboard/data-user', [HomeController::class, 'dataUser'])->name('dashboard.data-user');
Route::post('/dashboard/data-user', [HomeController::class, 'storeUser'])->name('dashboard.data-user.store');
Route::post('/dashboard/data-user/{user}/reset-password', [HomeController::class, 'resetUserPassword'])->name('dashboard.data-user.reset-password');
Route::delete('/dashboard/data-user/{user}', [HomeController::class, 'destroyUser'])->name('dashboard.data-user.destroy');
Route::post('/dashboard/data-plot', [HomeController::class, 'storeDataPlot'])->name('dashboard.data-plot.store');
Route::put('/dashboard/data-plot/{plot}', [HomeController::class, 'updateDataPlot'])->name('dashboard.data-plot.update');
Route::post('/dashboard/data-blok', [HomeController::class, 'storeDataBlok'])->name('dashboard.data-blok.store');
Route::put('/dashboard/data-blok/{block}', [HomeController::class, 'updateDataBlok'])->name('dashboard.data-blok.update');
Route::delete('/dashboard/data-blok/{block}', [HomeController::class, 'destroyDataBlok'])->name('dashboard.data-blok.destroy');
Route::get('/dashboard/pengaturan', [HomeController::class, 'websiteSettings'])->name('dashboard.settings');
Route::post('/dashboard/pengaturan', [HomeController::class, 'updateWebsiteSettings'])->name('dashboard.settings.update');
Route::get('/dashboard/akun', [HomeController::class, 'accountSettings'])->name('dashboard.account');
Route::post('/dashboard/akun', [HomeController::class, 'updateAccountSettings'])->name('dashboard.account.update');
Route::post('/dashboard/akun/password', [HomeController::class, 'updateAccountPassword'])->name('dashboard.account.password');
Route::get('/deceased/{id}', [HomeController::class, 'deceasedDetail'])->name('deceased.detail');
Route::get('/media/deceased-photo', [HomeController::class, 'serveDeceasedPhoto'])->name('media.deceased-photo');
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
