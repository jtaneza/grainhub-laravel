@extends('layouts.app')
@section('title', 'Edit Sale')

@section('content')
<div class="modal-dialog modal-lg" style="margin: 50px auto;">
    <div class="modal-content shadow-sm">

        {{-- 🔹 Modal Header (Same as Add Sale) --}}
        <div class="modal-header">
            <h4 class="modal-title">Edit Sale</h4>
            <a href="{{ route('sales.index') }}" class="close" aria-label="Close">&times;</a>
        </div>

        {{-- 🔹 Modal Body --}}
        <div class="modal-body">
            <div class="form-group">
                <input type="text" id="searchProduct" class="form-control" placeholder="Search for product name...">
                <div id="searchResults" class="list-group" style="position:absolute; z-index:1000; width:95%; display:none;"></div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><span class="glyphicon glyphicon-th"></span> Edit Sale Entry</strong>
                </div>

                <div class="panel-body">
                    <form id="editSaleForm" action="{{ route('sales.update', $sale->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr class="text-center">
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                    <th>Admin</th>
                                    <th>Date/Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="productInfo">
                                <tr>
                                    {{-- Product Dropdown --}}
                                    <td>
                                        <select name="product_id" id="product_id" class="form-control" required>
                                            @foreach ($products as $product)
                                                <option value="{{ $product->id }}" 
                                                    {{ $sale->product_id == $product->id ? 'selected' : '' }}>
                                                    {{ $product->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>

                                    {{-- Price --}}
                                    <td class="text-center">
                                        ₱<input type="number" step="0.01" name="price" id="price"
                                            class="form-control text-center d-inline-block"
                                            value="{{ $sale->price }}" required style="width:100px;">
                                    </td>

                                    {{-- Quantity --}}
                                    <td class="text-center">
                                        <input type="number" name="qty" id="qty"
                                            class="form-control text-center"
                                            value="{{ $sale->qty }}" min="1" required style="width:80px;">
                                    </td>

                                    {{-- Total (auto-calculated) --}}
                                    <td class="text-center align-middle">
                                        ₱<span id="computedTotal">{{ number_format($sale->qty * $sale->price, 2) }}</span>
                                    </td>

                                    {{-- Admin --}}
                                    <td class="text-center align-middle">
                                        {{ $sale->admin_name ?? Auth::user()->name }}
                                    </td>

                                    {{-- Date & Time --}}
                                    <td class="text-center">
                                        <input type="datetime-local" name="date" id="date"
                                            class="form-control text-center"
                                            value="{{ \Carbon\Carbon::parse($sale->date)->format('Y-m-d\TH:i') }}"
                                            required>
                                    </td>

                                    {{-- Save --}}
                                    <td class="text-center align-middle">
                                        <button type="submit" class="btn btn-success btn-sm">
                                            <i class="glyphicon glyphicon-floppy-disk"></i> Save
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- 🔹 Auto-update total dynamically (same as Add Sale) --}}
<script>
$(document).ready(function () {
    $('#qty, #price').on('input', function () {
        const qty = parseFloat($('#qty').val()) || 0;
        const price = parseFloat($('#price').val()) || 0;
        $('#computedTotal').text((qty * price).toFixed(2));
    });

    // Optional: live product search like Add Sale
    $('#searchProduct').on('keyup', function () {
        const query = $(this).val().trim();
        if (!query) return $('#searchResults').hide();

        $.get("{{ route('sales.search') }}", { query }, function (data) {
            let html = '';
            data.forEach(item => {
                html += `<a href="#" class="list-group-item suggestion-item"
                    data-id="${item.id}" data-name="${item.name}" data-price="${item.sale_price}">
                    ${item.name} — ₱${item.sale_price}
                </a>`;
            });
            $('#searchResults').html(html).fadeIn();
        });
    });

    $(document).on('click', '.suggestion-item', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        const name = $(this).data('name');
        const price = parseFloat($(this).data('price'));
        $('#searchResults').hide();

        $('#product_id').val(id);
        $('#price').val(price);
        $('#computedTotal').text((parseFloat($('#qty').val()) * price).toFixed(2));
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#searchProduct, #searchResults').length) {
            $('#searchResults').fadeOut();
        }
    });
});
</script>
@endsection
