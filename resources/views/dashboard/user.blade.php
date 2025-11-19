@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')

<style>
    /* simplify panel */
    .sales-panel {
        border: 1px solid #ddd;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        background: #fff;
    }

    .sales-panel:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.12);
    }

    .sales-panel .panel-heading {
        background: #f8f9fa;
        border-bottom: none;
        font-weight: 600;
        color: #333;
        padding: 12px;
        font-size: 16px;
    }

    .sales-panel .panel-body {
        padding: 20px;
    }

    .sales-panel h2 {
        margin: 0;
        font-weight: 700;
        color: #000;
    }

    h3.page-header {
        border-bottom: none;
        font-weight: 600;
        margin-bottom: 20px;
        color: #222;
    }
</style>

<div class="row"> 
    <!-- ✅ Sales Summary -->
    <div class="col-md-12">
        <h3 class="page-header">Sales Overview</h3>
    </div>

    <!-- Today's Sales -->
    <div class="col-md-4">
        <div class="panel sales-panel text-center">
            <div class="panel-heading">
                <strong>Today's Sales</strong>
            </div>
            <div class="panel-body">
                <h2>₱{{ number_format($dailySales, 2) }}</h2>
            </div>
        </div>
    </div>

    <!-- This Week -->
    <div class="col-md-4">
        <div class="panel sales-panel text-center">
            <div class="panel-heading">
                <strong>This Week</strong>
            </div>
            <div class="panel-body">
                <h2>₱{{ number_format($weeklySales, 2) }}</h2>
            </div>
        </div>
    </div>

    <!-- This Month -->
    <div class="col-md-4">
        <div class="panel sales-panel text-center">
            <div class="panel-heading">
                <strong>This Month</strong>
            </div>
            <div class="panel-body">
                <h2>₱{{ number_format($monthlySales, 2) }}</h2>
            </div>
        </div>
    </div>
</div>



<hr>

{{-- ✅ Recent Sales Table --}}
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong><span class="glyphicon glyphicon-stats"></span> Recent Sales</strong>
            </div>
            <div class="panel-body">
                <table class="table table-striped table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Product Name</th>
                            <th class= "text-center">Qty</th>
                            <th class= "text-center">Total Sale</th>
                            <th class= "text-center">Admin</th>
                            <th class= "text-center">Date & Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentSales as $index => $sale)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $sale->product->name ?? $sale->name ?? 'N/A' }}</td>
                                <td>{{ $sale->quantity ?? 0 }}</td>
                                <td>₱{{ number_format(($sale->price * $sale->quantity), 2) }}</td>
                                <td>{{ $sale->user->name ?? 'N/A' }}</td>
                                <td>
                                    {{ $sale->created_at ? $sale->created_at->format('Y-m-d h:i A') : 'N/A' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No recent sales found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
    
            </div>
        </div>
    </div>
</div>
@endsection
