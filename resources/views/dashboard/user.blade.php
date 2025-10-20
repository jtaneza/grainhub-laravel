@extends('layouts.app')

@section('title', 'User Dashboard')

@section('content')
<div class="row">
    <!-- ✅ Sales Summary -->
    <div class="col-md-12">
        <h3 class="page-header">Sales Overview</h3>
    </div>

    <div class="col-md-4">
        <div class="panel panel-info text-center">
            <div class="panel-heading">
                <strong>Today's Sales</strong>
            </div>
            <div class="panel-body">
                <h2 style="color:#51aded;">₱{{ number_format($dailySales, 2) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-success text-center">
            <div class="panel-heading">
                <strong>This Week</strong>
            </div>
            <div class="panel-body">
                <h2 style="color:#28a745;">₱{{ number_format($weeklySales, 2) }}</h2>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-warning text-center">
            <div class="panel-heading">
                <strong>This Month</strong>
            </div>
            <div class="panel-body">
                <h2 style="color:#f0ad4e;">₱{{ number_format($monthlySales, 2) }}</h2>
            </div>
        </div>
    </div>
</div>

<hr>

<!-- ✅ Recent Sales Table -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading" style="border-bottom: 3px solid #FFa500;">
                <strong><i class="glyphicon glyphicon-list-alt"></i> Recent Sales</strong>
            </div>
            <div class="panel-body">
                @if($recentSales->count() > 0)
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Quantity</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($recentSales as $sale)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($sale->date)->format('F d, Y') }}</td>
                                    <td>{{ $sale->product->name ?? 'N/A' }}</td>
                                    <td>{{ $sale->quantity }}</td>
                                    <td>₱{{ number_format($sale->price * $sale->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="alert alert-info text-center">
                        No recent sales found.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
