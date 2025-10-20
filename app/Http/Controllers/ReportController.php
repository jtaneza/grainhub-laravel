<?php
namespace App\Http\Controllers;
use App\Models\Sale;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function daily(Request $r){
        $date = $r->get('date', date('Y-m-d'));
        $sales = Sale::where('date', $date)->with('product')->get();
        return view('reports.daily', compact('sales','date'));
    }
}
