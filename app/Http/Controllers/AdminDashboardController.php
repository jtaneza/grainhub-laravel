<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $categoriesCount = Category::count();
        $productsCount = Product::count();
        $salesCount = Sale::count();

        // ✅ Load recent 10 sales
        $recentSales = Sale::with(['product', 'user'])
            ->orderByDesc('date')
            ->take(10)
            ->get();

        $recentProducts = Product::latest()->take(5)->get();

        $lowStockProducts = Product::where('quantity', '<=', 10)
            ->orderBy('quantity', 'asc')
            ->get();

        return view('admin.dashboard', compact(
            'usersCount',
            'categoriesCount',
            'productsCount',
            'salesCount',
            'recentSales',
            'recentProducts',
            'lowStockProducts'
        ));
    }
}
