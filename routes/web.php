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
use App\Http\Controllers\DailySalesController;
use App\Http\Controllers\MonthlySalesController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ProfileController;

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

    // ✅ Cashier Dashboard
    Route::middleware(['auth', 'cashier'])->prefix('cashier')->group(function () {
        Route::get('/dashboard', [CashierController::class, 'index'])->name('cashier.dashboard');
    });

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
        Route::prefix('reports')->group(function () {
        // View Reports
        Route::get('/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
        Route::get('/by-dates', [ReportController::class, 'byDates'])->name('reports.byDates');
        
        // Handle report generation by date range
        Route::post('/by-dates', [ReportController::class, 'generateByDates'])->name('reports.byDates.generate');

        // 🆕 Sales Report Page
        Route::get('/sales-report', [ReportController::class, 'salesReport'])->name('reports.salesReport');
        Route::post('/sales-report/filter', [ReportController::class, 'filterSalesReport'])->name('reports.salesReport.filter');
        
        // Download Reports
        Route::get('daily/download/{month}/{year}', [ReportController::class, 'downloadDaily'])->name('reports.daily.download');
        Route::get('monthly/download/{year}', [ReportController::class, 'downloadMonthly'])->name('reports.monthly.download');

        Route::get('/daily-sales', [DailySalesController::class, 'index'])->name('sales.daily');
        Route::get('/monthly-sales', [MonthlySalesController::class, 'index'])->name('sales.monthly');

        Route::get('/by-dates/download/{start}/{end}', [ReportController::class, 'downloadByDates'])->name('reports.byDates.download');
    });

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
    | 🧍 Profile Routes
    |--------------------------------------------------------------------------
    */
    // PROFILE
Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

// SEPARATE ACTIONS
Route::put('/profile/update-info', [ProfileController::class, 'updateInfo'])->name('profile.update.info');
Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');

// PASSWORD
Route::get('/profile/change-password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
Route::put('/profile/change-password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');

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
}); // ✅ this is correct and closes the middleware group properly
