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
| 🔐 Authentication Routes
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
| 🧭 Protected Routes (Require Login)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | 🏠 Dashboards
    |--------------------------------------------------------------------------
    */
    // Admin Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Cashier Dashboard
    Route::middleware(['auth', 'cashier'])->prefix('cashier')->group(function () {
        Route::get('/dashboard', [CashierController::class, 'index'])->name('cashier.dashboard');
    });

    // Regular User Dashboard
    Route::get('/user-dashboard', [DashboardController::class, 'userDashboard'])->name('user.dashboard');

    /*
    |--------------------------------------------------------------------------
    | 🗂️ Inventory Management
    |--------------------------------------------------------------------------
    */
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('suppliers', SupplierController::class);

    /*
    |--------------------------------------------------------------------------
    | 👥 Group & User Management
    |--------------------------------------------------------------------------
    */
    Route::resource('groups', GroupController::class)->except(['show']);
    Route::resource('users', UserController::class)->except(['show']);
    Route::put('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.updatePassword');

    /*
    |--------------------------------------------------------------------------
    | 📊 Reports & Sales Summaries
    |--------------------------------------------------------------------------
    */
    Route::prefix('reports')->group(function () {
        // Report Views
        Route::get('/daily', [ReportController::class, 'daily'])->name('reports.daily');
        Route::get('/monthly', [ReportController::class, 'monthly'])->name('reports.monthly');
        Route::get('/by-dates', [ReportController::class, 'byDates'])->name('reports.byDates');

        // Generate by Date Range
        Route::post('/by-dates', [ReportController::class, 'generateByDates'])->name('reports.byDates.generate');

        // Sales Report Page
        Route::get('/sales-report', [ReportController::class, 'salesReport'])->name('reports.salesReport');
        Route::post('/sales-report/filter', [ReportController::class, 'filterSalesReport'])->name('reports.salesReport.filter');

        // Download Reports
        Route::get('/daily/download/{month}/{year}', [ReportController::class, 'downloadDaily'])->name('reports.daily.download');
        Route::get('/monthly/download/{year}', [ReportController::class, 'downloadMonthly'])->name('reports.monthly.download');
        Route::get('/by-dates/download/{start}/{end}', [ReportController::class, 'downloadByDates'])->name('reports.byDates.download');

        // Daily & Monthly Sales
        Route::get('/daily-sales', [DailySalesController::class, 'index'])->name('sales.daily');
        Route::get('/monthly-sales', [MonthlySalesController::class, 'index'])->name('sales.monthly');
    });

    /*
    |--------------------------------------------------------------------------
    | 💵 Sales (AJAX + CRUD)
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
    Route::prefix('profile')->group(function () {
        // Profile Info
        Route::get('/', [ProfileController::class, 'show'])->name('profile.show');
        Route::get('/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/', [ProfileController::class, 'update'])->name('profile.update');

        // Separate Actions
        Route::put('/update-info', [ProfileController::class, 'updateInfo'])->name('profile.update.info');
        Route::post('/update-photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');

        // Password
        Route::get('/change-password', [ProfileController::class, 'editPassword'])->name('profile.password.edit');
        Route::put('/change-password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    });

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
    | 📈 Chart Data (for Dashboard)
    |--------------------------------------------------------------------------
    */
    Route::get('/chart-data', [ChartController::class, 'getSalesData'])->name('chart.data');
});
