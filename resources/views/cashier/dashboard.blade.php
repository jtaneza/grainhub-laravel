@extends('layouts.app')
@section('title', 'Cashier Dashboard')

@section('content')
<div class="container mt-4">
    <h3>Welcome, {{ $cashierName }}!</h3>

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Today's Sales</div>
                <div class="card-body">
                    @if($sales->count())
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Total</th>
                                <th>Date & Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $i => $sale)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $sale->product_name }}</td>
                                <td>{{ $sale->qty }}</td>
                                <td>₱{{ number_format($sale->total_saleing_price, 2) }}</td>
                                <td>{{ \Carbon\Carbon::parse($sale->date)->format('M d, Y h:i A') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                        <div class="alert alert-info">No sales recorded today.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
