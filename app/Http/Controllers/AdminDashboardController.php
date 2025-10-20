<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $usersCount = User::count();
        $categoriesCount = Category::count();
        $productsCount = Product::count();
        $salesCount = Sale::count();

        $recentSales = Sale::with('product')->latest('date')->limit(5)->get();
        $recentProducts = Product::latest()->limit(5)->get();

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
