<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Main dashboard logic
     * Redirect users based on their role (admin or regular user)
     */
    public function index()
    {
        $user = Auth::user();

        // ✅ Admin Dashboard (user_level == 1)
        if ($user->user_level == 1) {
            $usersCount = User::count();
            $categoriesCount = Category::count();
            $productsCount = Product::count();
            $salesCount = Sale::count();

            // ✅ Recent 10 sales
            $recentSales = Sale::with('product')
                ->orderBy('date', 'desc')
                ->take(10)
                ->get();

            // ✅ Low-stock products (<=10)
            $lowStockProducts = Product::where('quantity', '<=', 10)
                ->orderBy('quantity', 'asc')
                ->get(['name', 'quantity']);

            return view('dashboard.index', compact(
                'usersCount',
                'categoriesCount',
                'productsCount',
                'salesCount',
                'recentSales',
                'lowStockProducts'
            ));
        }

        // ✅ Regular User → redirect to user dashboard
        return redirect()->route('user.dashboard');
    }

    /**
     * Limited access user dashboard
     * Shows only sales summary + recent sales
     */
    public function userDashboard()
{
    $recentSales = Sale::with('product')
        ->orderBy('date', 'desc')
        ->take(10)
        ->get();

    $today = Carbon::today();

    $dailySales = Sale::whereDate('date', $today)->sum('price');
    $weeklySales = Sale::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
        ->sum('price');
    $monthlySales = Sale::whereMonth('date', Carbon::now()->month)->sum('price');

    return view('dashboard.user', compact(
        'recentSales',
        'dailySales',
        'weeklySales',
        'monthlySales'
    ));
}

}
