<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailySalesController extends Controller
{
    public function index()
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        $sales = Sale::join('products', 'sales.product_id', '=', 'products.id')
            ->whereYear('sales.date', $year)
            ->whereMonth('sales.date', $month)
            ->select(
                'products.name',
                DB::raw('SUM(sales.qty) as qty'),
                DB::raw('SUM(sales.qty * products.sale_price) as total_saleing_price'),
                DB::raw('DATE(sales.date) as date')
            )
            ->groupBy('products.name', 'date')
            ->orderBy('date', 'desc')
            ->get();

        return view('sales.daily', compact('sales', 'year', 'month'));
    }
}
