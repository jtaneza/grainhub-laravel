<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class MonthlySalesController extends Controller
{
    public function index()
    {
        $year = Carbon::now()->year;

        $sales = Sale::join('products', 'sales.product_id', '=', 'products.id')
            ->whereYear('sales.date', $year)
            ->select(
                'products.name',
                DB::raw('SUM(sales.qty) as qty'),
                DB::raw('SUM(sales.qty * products.sale_price) as total_saleing_price'),
                DB::raw('MONTH(sales.date) as month')
            )
            ->groupBy('products.name', 'month')
            ->orderBy('month', 'desc')
            ->get();

        return view('sales.monthly', compact('sales', 'year'));
    }
}
