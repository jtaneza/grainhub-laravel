@extends('layouts.app')

@section('content')
<style>
  .report-wrapper { padding: 25px 30px; }
  .report-card {
    background:#fff;border-radius:6px;box-shadow:0 2px 5px rgba(0,0,0,0.05);width:900px;margin-left:0;
  }
  .report-card .card-header {
    font-weight:600;background:#fff;border-bottom:1px solid #e9ecef;color:#333;padding:15px 20px;
  }
  .report-card .card-body { padding:25px 20px; }
  .form-control { border-radius:4px;height:38px; }
  .btn { border-radius:4px;font-weight:500; }
  .btn-primary { background-color:#0d6efd;border:none; }
  .btn-primary:hover { background-color:#0b5ed7; }
  .btn-success { background-color:#198754;border:none; }
  .btn-success:hover { background-color:#157347; }
  .table { margin-top:25px;border-radius:6px;overflow:hidden; }
  .table thead th { background-color:#1b1f23;color:#fff;font-weight:500;text-align:center; }
  .table td { text-align:center; }
  .alert { width:600px;border-radius:6px;margin-top:20px; }
</style>

<div class="report-wrapper">
  <div class="report-card card">
    <div class="card-header">
            <strong><span class="glyphicon glyphicon-th"></span> Generate Sales Report</strong>
        </div>
    <div class="card-body">
      <form method="POST" action="{{ route('reports.byDates.generate') }}">
        @csrf
        <div class="row align-items-end">
          <!-- Left: Date range -->
          <div class="col-md-8">
            <div class="row">
              <div class="col-md-6">
                <label for="start_date" class="form-label mb-1">Start Date</label>
                <input type="date" name="start_date" class="form-control"
                       value="{{ old('start_date', $start ?? date('Y-m-d')) }}" required>
              </div>
              <div class="col-md-6">
                <label for="end_date" class="form-label mb-1">End Date</label>
                <input type="date" name="end_date" class="form-control"
                       value="{{ old('end_date', $end ?? date('Y-m-d')) }}" required>
              </div>
            </div>
          </div>

          <!-- Right: Buttons -->
          <div class="col-md-4 text-end">
            <button type="submit" class="btn btn-primary me-2">
              <i class="bi bi-graph-up"></i> Generate Report
            </button>

            @php
              $dlStart = $start ?? request('start_date');
              $dlEnd   = $end   ?? request('end_date');
            @endphp

            @if(!empty($dlStart) && !empty($dlEnd))
              <a href="{{ route('reports.byDates.download', ['start' => $dlStart, 'end' => $dlEnd]) }}"
                 class="btn btn-success"
                 target="_blank" rel="noopener">
                <i class="bi bi-download"></i> Download Report
              </a>
            @else
              <button type="button" class="btn btn-success" style="visibility:hidden">Download</button>
            @endif
          </div>
        </div>
      </form>
    </div>
  </div>

  {{-- Results table --}}
  @if(isset($sales) && $sales->count())
    <div class="card report-card mt-4">
      <div class="card-header">
        Sales from {{ \Carbon\Carbon::parse($start)->format('M d, Y') }}
        to {{ \Carbon\Carbon::parse($end)->format('M d, Y') }}
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered table-hover align-middle">
            <thead>
              <tr>
                <th>#</th><th>Product</th><th>Quantity</th><th>Total</th><th>Admin</th><th>Date</th>
              </tr>
            </thead>
            <tbody>
              @php $grandTotal = 0; @endphp
              @foreach($sales as $i => $sale)
                @php $grandTotal += $sale->total_saleing_price; @endphp
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>{{ $sale->product_name }}</td>
                  <td>{{ $sale->qty }}</td>
                  <td>₱{{ number_format($sale->total_saleing_price, 2) }}</td>
                  <td>{{ $sale->admin_name }}</td>
                  <td>{{ \Carbon\Carbon::parse($sale->date)->format('M d, Y') }}</td>
                </tr>
              @endforeach
              <tr>
                <td colspan="3" class="text-end fw-bold">TOTAL</td>
                <td class="fw-bold">₱{{ number_format($grandTotal, 2) }}</td>
                <td colspan="2"></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @elseif(isset($start))
    <div class="alert alert-info text-center">
      No sales found for the selected date range.
    </div>
  @endif
</div>
@endsection
