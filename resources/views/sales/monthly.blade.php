@extends('layouts.app')
@section('title', 'Monthly Sales')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>Monthly Sales ({{ $year }})</span>
        </strong>
      </div>
      <div class="panel-body">
        @if($sales->count() > 0)
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Product Name</th>
              <th class="text-center" style="width: 15%;">Quantity Sold</th>
              <th class="text-center" style="width: 15%;">Total</th>
              <th class="text-center" style="width: 15%;">Month</th>
              
            </tr>
          </thead>
          <tbody>
            @foreach($sales as $index => $sale)
            <tr>
              <td class="text-center">{{ $index + 1 }}</td>
              <td>{{ $sale->name }}</td>
              <td class="text-center">{{ $sale->qty }}</td>
              <td class="text-center">₱{{ number_format($sale->total_saleing_price, 2) }}</td>
              <td class="text-center">{{ \Carbon\Carbon::createFromFormat('Y-m', $sale->date)->format('F Y') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
        @else
        <div class="alert alert-warning text-center">
          <strong>No monthly sales found for {{ $year }}.</strong>
        </div>
        @endif
      </div>
    </div>
  </div>
</div>
@endsection
