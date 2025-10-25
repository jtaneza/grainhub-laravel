@extends('layouts.app')
@section('title', 'Monthly Sales Report')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <strong><span class="glyphicon glyphicon-th"></span> Monthly Sales Report</strong>
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
          <th>Month</th>
          <th>Admin</th>
        </tr>
      </thead>
      <tbody>
        @foreach($sales as $i => $sale)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $sale->name }}</td>
          <td class="text-center">{{ $sale->qty }}</td>
          <td class="text-center">₱{{ number_format($sale->total_saleing_price, 2) }}</td>
          <td class="text-center">{{ \Carbon\Carbon::parse($sale->month . '-01')->format('M Y') }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Download Report Button -->
    <div class="mt-3">
      <a href="{{ route('reports.monthly.download', ['year' => $year]) }}" 
         class="btn btn-success">
        📥 Download Report
      </a>
    </div>

    @else
    <div class="alert alert-warning text-center">No sales found for this year.</div>
    @endif
  </div>
</div>
@endsection
