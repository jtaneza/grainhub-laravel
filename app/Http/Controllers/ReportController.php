<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class ReportController extends Controller
{
    /** 🗓 DAILY SALES REPORT (with actual time) */
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
                'sales.qty',
                DB::raw('(sales.qty * products.sale_price) as total_saleing_price'),
                DB::raw('COALESCE(users.name, sales.admin_name) as admin_name'),
                'sales.date'
            )
            ->orderBy('sales.date', 'desc')
            ->get();

        $generatedBy = Auth::user()->name ?? 'System Admin';

        return view('reports.daily', compact('sales', 'year', 'month', 'generatedBy'));
    }

    /** 📥 DOWNLOAD DAILY SALES REPORT */
    public function downloadDaily($month, $year, Request $request)
    {
        $format = $request->query('format', 'excel');
        $sales = $this->getDailySalesData($month, $year);

        if ($format === 'pdf') {
            $pdf = PDF::loadView('reports.exports.daily_pdf', compact('sales', 'month', 'year'));
            return $pdf->download("daily_sales_{$month}_{$year}.pdf");
        }

        return $this->exportCSV(
            $sales,
            "daily_sales_{$month}_{$year}.csv",
            ['#', 'Product', 'Qty', 'Total', 'Admin', 'Date & Time'],
            'daily'
        );
    }

    /** 📅 MONTHLY SALES REPORT (with fallback year) */
    public function monthly($year = null)
    {
        $year = $year ?? Carbon::now()->year;

        $sales = Sale::join('products', 'sales.product_id', '=', 'products.id')
            ->leftJoin('users', 'sales.admin_id', '=', 'users.id')
            ->whereYear('sales.date', $year)
            ->select(
                'products.name as product_name',
                DB::raw('SUM(sales.qty) as qty'),
                DB::raw('SUM(sales.qty * products.sale_price) as total_saleing_price'),
                DB::raw('DATE_FORMAT(sales.date, "%Y-%m") as month'),
                DB::raw('GROUP_CONCAT(DISTINCT COALESCE(users.name, sales.admin_name) SEPARATOR ", ") as admin_name')
            )
            ->groupBy('month', 'products.name')
            ->orderBy('month', 'desc')
            ->get();

        $generatedBy = Auth::user()->name ?? 'System Admin';

        return view('reports.monthly', compact('sales', 'year', 'generatedBy'));
    }

    /** 📥 DOWNLOAD MONTHLY SALES REPORT */
    public function downloadMonthly($year = null, Request $request)
    {
        $year = $year ?? Carbon::now()->year;
        $format = $request->query('format', 'excel');
        $sales = $this->getMonthlySalesData($year);

        if ($format === 'pdf') {
            $pdf = PDF::loadView('reports.exports.monthly_pdf', compact('sales', 'year'));
            return $pdf->download("monthly_sales_{$year}.pdf");
        }

        return $this->exportCSV(
            $sales,
            "monthly_sales_{$year}.csv",
            ['#', 'Product', 'Qty', 'Total', 'Admin', 'Month/Year'],
            'monthly'
        );
    }

    /** 📊 SALES REPORT BY CUSTOM DATE RANGE (FORM) */
    public function byDates()
    {
        return view('reports.byDates', ['sales' => collect()]);
    }

    /** ⚙️ GENERATE REPORT BY CUSTOM DATE RANGE */
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

    /** 📥 DOWNLOAD CUSTOM RANGE SALES REPORT */
    public function downloadByDates($start, $end, Request $request)
    {
        $format = $request->query('format', 'excel');
        $sales = $this->getSalesByRange($start, $end);

        if ($format === 'pdf') {
            $pdf = PDF::loadView('reports.exports.byDates_pdf', compact('sales', 'start', 'end'));
            return $pdf->download("sales_report_{$start}_to_{$end}.pdf");
        }

        return $this->exportCSV(
            $sales,
            "sales_report_{$start}_to_{$end}.csv",
            ['#', 'Product', 'Qty', 'Total', 'Admin', 'Date & Time'],
            'range'
        );
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
                'sales.qty',
                DB::raw('(sales.qty * products.sale_price) as total_saleing_price'),
                DB::raw('COALESCE(users.name, sales.admin_name) as admin_name'),
                'sales.date'
            )
            ->orderBy('sales.date', 'desc')
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
                DB::raw('GROUP_CONCAT(DISTINCT COALESCE(users.name, sales.admin_name) SEPARATOR ", ") as admin_name')
            )
            ->groupBy('month', 'products.name')
            ->orderBy('month', 'desc')
            ->get();
    }

    /** ✅ FIXED: Include full-day timestamps for accurate range filter */
    private function getSalesByRange($start, $end)
    {
        $startDate = Carbon::parse($start)->startOfDay();
        $endDate   = Carbon::parse($end)->endOfDay();

        return Sale::join('products', 'sales.product_id', '=', 'products.id')
            ->leftJoin('users', 'sales.admin_id', '=', 'users.id')
            ->whereBetween('sales.date', [$startDate, $endDate])
            ->select(
                'products.name as product_name',
                'sales.qty',
                DB::raw('(sales.qty * products.sale_price) as total_saleing_price'),
                DB::raw('COALESCE(users.name, sales.admin_name) as admin_name'),
                'sales.date'
            )
            ->orderBy('sales.date', 'desc')
            ->get();
    }

    /** 📤 Reusable CSV Exporter */
    private function exportCSV($sales, $filename, $columns, $type = 'daily')
    {
        $callback = function () use ($sales, $columns, $type) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($sales as $i => $sale) {
                $dateValue = ($type === 'monthly' && isset($sale->month))
                    ? Carbon::createFromFormat('Y-m', $sale->month)->format('F Y')
                    : (isset($sale->date) ? Carbon::parse($sale->date)->format('M d, Y h:i A') : 'N/A');

                fputcsv($file, [
                    $i + 1,
                    $sale->product_name ?? 'N/A',
                    $sale->qty,
                    number_format($sale->total_saleing_price, 2),
                    $sale->admin_name ?? 'N/A',
                    $dateValue,
                ]);
            }

            fclose($file);
        };

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename={$filename}",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0",
        ];

        return Response::stream($callback, 200, $headers);
    }
}

