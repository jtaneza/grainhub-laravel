<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ChartController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MediaController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout')
    ->middleware('auth');

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // ✅ Admin Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // ✅ Regular User Dashboard
    Route::get('/user-dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');

    // ✅ Categories
    Route::resource('categories', CategoryController::class);

    // ✅ Products
    Route::resource('products', ProductController::class);

    // ✅ Suppliers
    Route::resource('suppliers', SupplierController::class);

    /*
    |--------------------------------------------------------------------------
    | 🧩 Group & User Management
    |--------------------------------------------------------------------------
    */
    Route::resource('groups', GroupController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
    Route::put('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.updatePassword');

    /*
    |--------------------------------------------------------------------------
    | 🧾 Reports
    |--------------------------------------------------------------------------
    */
    Route::get('reports/daily', [ReportController::class, 'daily'])->name('reports.daily');

    /*
    |--------------------------------------------------------------------------
    | 💵 Sales Routes (AJAX + CRUD)
    |--------------------------------------------------------------------------
    */
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('search', [SaleController::class, 'search'])->name('search');
        Route::get('product', [SaleController::class, 'getProduct'])->name('product');
        Route::get('add-modal', [SaleController::class, 'create'])->name('add.modal');
    });

    Route::resource('sales', SaleController::class);

    /*
    |--------------------------------------------------------------------------
    | 🖼️ Media Management
    |--------------------------------------------------------------------------
    */
    Route::get('/media', [MediaController::class, 'index'])->name('media.index');
    Route::post('/media', [MediaController::class, 'store'])->name('media.store');
    Route::delete('/media/{media}', [MediaController::class, 'destroy'])->name('media.destroy');

    /*
    |--------------------------------------------------------------------------
    | 📊 Chart Data for Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/chart-data', [ChartController::class, 'getSalesData'])->name('chart.data');
});
