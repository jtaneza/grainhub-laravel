@extends('layouts.app')
@section('title', 'Edit Sale')

@section('content')
<div class="row">
    <div class="col-md-12">

        {{-- ✅ Success Message --}}
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade in">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">&times;</button>
                <strong><i class="glyphicon glyphicon-ok"></i></strong> {{ session('success') }}
            </div>
        @endif

        <div class="panel panel-default shadow-sm">
            {{-- ✅ Header --}}
            <div class="panel-heading clearfix bg-primary text-white p-2 rounded-top d-flex justify-content-between align-items-center">
                <strong><span class="glyphicon glyphicon-th"></span> Edit Sale</strong>
            </div>

            {{-- ✅ Body --}}
            <div class="panel-body bg-light">
                <form action="{{ route('sales.update', $sale->id) }}" method="POST" id="editSaleForm">
                    @csrf
                    @method('PUT')

                    <table class="table table-bordered table-striped align-middle mb-0">
                        <thead class="bg-primary text-white text-center">
                            <tr>
                                <th>Product title</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Total</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                {{-- Product --}}
                                <td style="width: 25%;">
                                    <select name="product_id" class="form-control input-sm" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}" {{ $sale->product_id == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                {{-- Quantity --}}
                                <td style="width: 10%;">
                                    <input type="number" name="qty" id="qty" class="form-control input-sm text-center"
                                           value="{{ $sale->qty }}" min="1" required>
                                </td>

                                {{-- Price --}}
                                <td style="width: 15%;">
                                    <input type="number" name="price" id="price" class="form-control input-sm text-center"
                                           value="{{ $sale->price }}" step="0.01" required>
                                </td>

                                {{-- Total (auto-calculated) --}}
                                <td style="width: 15%;">
                                    <input type="text" id="total" class="form-control input-sm text-center bg-light"
                                           value="{{ number_format($sale->qty * $sale->price, 2) }}" readonly>
                                </td>

                                {{-- Date --}}
                                <td style="width: 20%;">
                                    <input type="date" name="date" class="form-control input-sm text-center"
                                           value="{{ \Carbon\Carbon::parse($sale->date)->format('Y-m-d') }}" required>
                                </td>

                                {{-- Action --}}
                                <td class="text-center" style="width: 15%;">
                                    <button type="submit" class="btn btn-success btn-sm">
                                        <i class="glyphicon glyphicon-save"></i> Update sale
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- ✅ Admin field --}}
                   <div class="mt-3">
    <label class="fw-bold me-2">Admin:</label>
    <input type="text" 
           class="form-control d-inline-block bg-light text-center" 
           style="width: 200px;" 
           value="{{ Auth::user()->name }}" 
           readonly>
</div>

                </form>
            </div>
        </div>
    </div>
</div>

{{-- ✅ Auto-update total calculation --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    const qty = document.getElementById('qty');
    const price = document.getElementById('price');
    const total = document.getElementById('total');

    function updateTotal() {
        const q = parseFloat(qty.value) || 0;
        const p = parseFloat(price.value) || 0;
        total.value = (q * p).toFixed(2);
    }

    qty.addEventListener('input', updateTotal);
    price.addEventListener('input', updateTotal);
});
</script>
@endsection
