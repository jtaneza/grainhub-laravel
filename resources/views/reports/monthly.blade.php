@extends('layouts.app')
@section('title', 'Monthly Sales Report')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <strong>MONTHLY SALES REPORT</strong>
  </div>
 <div class="panel-body">
    @if($sales->count())
      <table class="table table-bordered table-striped table-hover align-middle">
        <thead class="table-dark">
          <tr>
            <th class="text-center">#</th>
            <th class="text-center">Product</th>
            <th class="text-center">Qty</th>
            <th class="text-center">Total</th>
            <th class="text-center">Month</th>
            <th class="text-center">Admin/Cashier</th>
          </tr>
        </thead>

        <tbody>
          @foreach($sales as $i => $sale)
          <tr>
            <td class="text-center">{{ $i + 1 }}</td>
            <td class="text-start">{{ $sale->product_name ?? 'N/A' }}</td>
            <td class="text-center">{{ $sale->qty }}</td>
            <td class="text-center">₱{{ number_format($sale->total_saleing_price, 2) }}</td>
            <td class="text-center">{{ \Carbon\Carbon::parse($sale->month . '-01')->format('F Y') }}</td>
            <td class="text-center">{{ $sale->admin_name ?? ($sale->admins ?? 'N/A') }}</td>
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
