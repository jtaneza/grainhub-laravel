@extends('layouts.app')
@section('title', 'Daily Sales Report')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <strong>Daily Sales Report</strong>
  </div>
  <div class="panel-body">
    @if($sales->count())
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>#</th>
          <th>Product</th>
          <th>Qty</th>
          <th>Total</th>
          <th>Admin</th>
          <th>Date & Time</th>
        </tr>
      </thead>
      <tbody>
        @foreach($sales as $i => $sale)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $sale->product->name ?? $sale->name }}</td>
          <td class="text-center">{{ $sale->qty }}</td>
          <td class="text-center">₱{{ number_format($sale->price ?? $sale->total_saleing_price, 2) }}</td>
          <td class="text-center">{{ $sale->admin_name ?? 'N/A' }}</td>
          <td class="text-center">{{ \Carbon\Carbon::parse($sale->date)->format('M d, Y h:i A') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Generate / Download Report Button -->
    <div class="mt-3">
      <a href="{{ route('reports.daily.download', ['month' => $month, 'year' => $year]) }}" 
         class="btn btn-success">
        <i class="glyphicon glyphicon-download"></i> 📥Download Report
      </a>
    </div>

    @else
    <div class="alert alert-warning text-center">No sales found for this month.</div>
    @endif
  </div>
</div>
@endsection
