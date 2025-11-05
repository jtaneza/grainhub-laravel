@extends('layouts.app')
@section('title', 'Daily Sales Report')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <strong>DAILY SALES REPORT</strong>
  </div>

  <div class="panel-body">
    @if($sales->count())
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th class="text-center">#</th>
            <th class="text-center">Product</th>
            <th class="text-center">Qty</th>
            <th class="text-center">Total</th>
            <th class="text-center">Admin</th>
            <th class="text-center">Date & Time</th>

          </tr>
        </thead>

        <tbody>
          @foreach($sales as $i => $sale)
          <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            {{-- ✅ Fixed: use product_name instead of product->name --}}
            <td>{{ $sale->product_name }}</td>
            <td class="text-center">{{ $sale->qty }}</td>
            <td class="text-center">₱{{ number_format($sale->total_saleing_price, 2) }}</td>
            <td class="text-center">{{ $sale->admin_name ?? 'N/A' }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($sale->date)->format('M d, Y h:i A') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>

      <!-- 📥 Download Report Button -->
      <div class="mt-3">
        <a href="{{ route('reports.daily.download', ['month' => $month, 'year' => $year]) }}" 
           class="btn btn-success">
          <i class="glyphicon glyphicon-download"></i> Download Report
        </a>
      </div>

    @else
      <div class="alert alert-warning text-center">
        No sales found for this month.
      </div>
    @endif
  </div>
</div>
@endsection
