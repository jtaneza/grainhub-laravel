<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function getSalesData(Request $request)
    {
        $range = $request->query('range', 'monthly');
        $result = ['labels' => [], 'values' => []];

        switch ($range) {
            case 'daily':
                // ✅ Daily sales for current month
                $data = DB::table('sales')
                    ->select(
                        DB::raw('DATE(date) as date'),
                        DB::raw('SUM(price) as total_sales')
                    )
                    ->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year)
                    ->groupBy('date')
                    ->orderBy('date', 'asc')
                    ->get();

                $result['labels'] = $data->pluck('date');
                $result['values'] = $data->pluck('total_sales');
                break;

            case 'weekly':
                // ✅ Sales for last 4 weeks
                $data = DB::table('sales')
                    ->select(
                        DB::raw('YEARWEEK(date, 1) as week_label'),
                        DB::raw('SUM(price) as total_sales')
                    )
                    ->where('date', '>=', Carbon::now()->subWeeks(4))
                    ->groupBy('week_label')
                    ->orderBy('week_label', 'asc')
                    ->get();

                $result['labels'] = $data->pluck('week_label')->map(function ($w) {
                    return "Week " . substr($w, -2);
                });
                $result['values'] = $data->pluck('total_sales');
                break;

            case 'monthly':
                // ✅ Monthly sales for current year
                $data = DB::table('sales')
                    ->select(
                        DB::raw('MONTH(date) as month'),
                        DB::raw('SUM(price) as total_sales')
                    )
                    ->whereYear('date', Carbon::now()->year)
                    ->groupBy('month')
                    ->orderBy('month', 'asc')
                    ->get();

                $result['labels'] = $data->pluck('month')->map(function ($m) {
                    return Carbon::create()->month($m)->format('F');
                });
                $result['values'] = $data->pluck('total_sales');
                break;

            case 'yearly':
                // ✅ Yearly sales
                $data = DB::table('sales')
                    ->select(
                        DB::raw('YEAR(date) as year'),
                        DB::raw('SUM(price) as total_sales')
                    )
                    ->groupBy('year')
                    ->orderBy('year', 'asc')
                    ->get();

                $result['labels'] = $data->pluck('year');
                $result['values'] = $data->pluck('total_sales');
                break;
        }

        return response()->json($result);
    }
}
