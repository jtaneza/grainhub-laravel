<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Main dashboard logic
     * Redirect users based on their role (admin or cashier)
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

        // ✅ Regular User (Cashier)
        return redirect()->route('user.dashboard');
    }

    /**
     * Cashier Dashboard
     * Shows only sales summary + recent sales
     */
    public function userDashboard()
    {
        $recentSales = Sale::with('product')
            ->orderBy('date', 'desc')
            ->take(10)
            ->get();

        $today = Carbon::today();

        // ✅ Use correct column name: qty (not quantity)
        $dailySales = Sale::whereDate('date', $today)
            ->sum(DB::raw('price * qty'));

        $weeklySales = Sale::whereBetween('date', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])
            ->sum(DB::raw('price * qty'));

        $monthlySales = Sale::whereMonth('date', Carbon::now()->month)
            ->sum(DB::raw('price * qty'));

        return view('dashboard.user', compact(
            'recentSales',
            'dailySales',
            'weeklySales',
            'monthlySales'
        ));
    }
}
