<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF; // 👈 Add this for PDF export (requires barryvdh/laravel-dompdf)

class ReportController extends Controller
{
    /** 🗓 DAILY SALES REPORT */
    public function daily()
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;

        $sales = Sale::join('products', 'sales.product_id', '=', 'products.id')
            ->leftJoin('users', 'sales.admin_id', '=', 'users.id')
            ->whereYear('sales.date', $year)
            ->whereMonth('sales.date', $month)
            ->select(
                'products.name as product_name',
                DB::raw('SUM(sales.qty) as qty'),
                DB::raw('SUM(sales.qty * products.sale_price) as total_saleing_price'),
                DB::raw('COALESCE(users.name, sales.admin_name) as admin_name'),
                DB::raw('DATE(sales.date) as date')
            )
            ->groupBy('products.name', 'admin_name', 'date')
            ->orderBy('date', 'desc')
            ->get();

        $generatedBy = Auth::user()->name ?? 'System Admin';
        return view('reports.daily', compact('sales', 'year', 'month', 'generatedBy'));
    }

    /** 📥 DOWNLOAD DAILY SALES REPORT */
    public function downloadDaily($month, $year, Request $request)
    {
        $format = $request->query('format', 'excel'); // Default Excel
        $sales = $this->getDailySalesData($month, $year);

        if ($format === 'pdf') {
            $pdf = PDF::loadView('reports.exports.daily_pdf', compact('sales', 'month', 'year'));
            return $pdf->download("daily_sales_{$month}_{$year}.pdf");
        }

        // Default CSV
        return $this->exportCSV($sales, "daily_sales_{$month}_{$year}.csv", ['#','Product','Qty','Total','Admin','Date']);
    }

    /** 📅 MONTHLY SALES REPORT */
    public function monthly()
    {
        $year = Carbon::now()->year;

        $sales = Sale::join('products', 'sales.product_id', '=', 'products.id')
            ->leftJoin('users', 'sales.admin_id', '=', 'users.id')
            ->whereYear('sales.date', $year)
            ->select(
                'products.name as product_name',
                DB::raw('SUM(sales.qty) as qty'),
                DB::raw('SUM(sales.qty * products.sale_price) as total_saleing_price'),
                DB::raw('DATE_FORMAT(sales.date, "%Y-%m") as month'),
                DB::raw('GROUP_CONCAT(DISTINCT COALESCE(users.name, sales.admin_name) SEPARATOR ", ") as admins')
            )
            ->groupBy('products.name', 'month')
            ->orderBy('month', 'desc')
            ->get();

        $generatedBy = Auth::user()->name ?? 'System Admin';
        return view('reports.monthly', compact('sales', 'year', 'generatedBy'));
    }

    /** 📥 DOWNLOAD MONTHLY SALES REPORT */
    public function downloadMonthly($year, Request $request)
    {
        $format = $request->query('format', 'excel');
        $sales = $this->getMonthlySalesData($year);

        if ($format === 'pdf') {
            $pdf = PDF::loadView('reports.exports.monthly_pdf', compact('sales', 'year'));
            return $pdf->download("monthly_sales_{$year}.pdf");
        }

        return $this->exportCSV($sales, "monthly_sales_{$year}.csv", ['#','Product','Qty','Total','Admins','Month']);
    }

    /** 📊 SALES BY CUSTOM DATE RANGE (GET) */
    public function byDates()
    {
        return view('reports.byDates', ['sales' => collect()]);
    }

    /** ⚙️ SALES BY CUSTOM DATE RANGE (POST) */
    public function generateByDates(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date'   => 'required|date|after_or_equal:start_date',
        ]);

        $sales = $this->getSalesByRange($request->start_date, $request->end_date);
        $generatedBy = Auth::user()->name ?? 'System Admin';

        return view('reports.byDates', [
            'sales' => $sales,
            'start' => $request->start_date,
            'end'   => $request->end_date,
            'generatedBy' => $generatedBy,
        ]);
    }

    /** 📥 DOWNLOAD CUSTOM DATE RANGE SALES REPORT */
    public function downloadByDates($start, $end, Request $request)
    {
        $format = $request->query('format', 'excel');
        $sales = $this->getSalesByRange($start, $end);

        if ($format === 'pdf') {
            $pdf = PDF::loadView('reports.exports.byDates_pdf', compact('sales', 'start', 'end'));
            return $pdf->download("sales_report_{$start}_to_{$end}.pdf");
        }

        return $this->exportCSV($sales, "sales_report_{$start}_to_{$end}.csv", ['#','Product','Qty','Total','Admin','Date']);
    }

    /* ==============================
       🔧 HELPER METHODS
    ============================== */

    private function getDailySalesData($month, $year)
    {
        return Sale::join('products', 'sales.product_id', '=', 'products.id')
            ->leftJoin('users', 'sales.admin_id', '=', 'users.id')
            ->whereYear('sales.date', $year)
            ->whereMonth('sales.date', $month)
            ->select(
                'products.name as product_name',
                DB::raw('SUM(sales.qty) as qty'),
                DB::raw('SUM(sales.qty * products.sale_price) as total_saleing_price'),
                DB::raw('COALESCE(users.name, sales.admin_name) as admin_name'),
                DB::raw('DATE(sales.date) as date')
            )
            ->groupBy('products.name', 'admin_name', 'date')
            ->orderBy('date', 'desc')
            ->get();
    }

    private function getMonthlySalesData($year)
    {
        return Sale::join('products', 'sales.product_id', '=', 'products.id')
            ->leftJoin('users', 'sales.admin_id', '=', 'users.id')
            ->whereYear('sales.date', $year)
            ->select(
                'products.name as product_name',
                DB::raw('SUM(sales.qty) as qty'),
                DB::raw('SUM(sales.qty * products.sale_price) as total_saleing_price'),
                DB::raw('DATE_FORMAT(sales.date, "%Y-%m") as month'),
                DB::raw('GROUP_CONCAT(DISTINCT COALESCE(users.name, sales.admin_name) SEPARATOR ", ") as admins')
            )
            ->groupBy('products.name', 'month')
            ->orderBy('month', 'desc')
            ->get();
    }

    private function getSalesByRange($start, $end)
    {
        return Sale::join('products', 'sales.product_id', '=', 'products.id')
            ->leftJoin('users', 'sales.admin_id', '=', 'users.id')
            ->whereBetween(DB::raw('DATE(sales.date)'), [$start, $end])
            ->select(
                'products.name as product_name',
                DB::raw('COALESCE(users.name, sales.admin_name) as admin_name'),
                DB::raw('DATE(sales.date) as date'),
                DB::raw('SUM(sales.qty) as qty'),
                DB::raw('SUM(sales.qty * products.sale_price) as total_saleing_price')
            )
            ->groupBy('products.name', 'admin_name', 'sales.date')
            ->orderBy('sales.date', 'desc')
            ->get();
    }

    /** 📤 Export reusable CSV */
    private function exportCSV($sales, $filename, $columns)
    {
        $callback = function() use ($sales, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($sales as $i => $sale) {
                fputcsv($file, [
                    $i + 1,
                    $sale->product_name,
                    $sale->qty,
                    number_format($sale->total_saleing_price, 2),
                    $sale->admin_name ?? ($sale->admins ?? 'N/A'),
                    isset($sale->date)
                        ? Carbon::parse($sale->date)->format('M d, Y')
                        : Carbon::parse($sale->month.'-01')->format('M Y'),
                ]);
            }

            fclose($file);
        };

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        return Response::stream($callback, 200, $headers);
    }
}
