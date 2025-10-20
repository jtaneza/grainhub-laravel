@extends('layouts.app')

@section('title', 'Add Sale')

@section('content')
    <div class="container">
        <h2>Add Sale</h2>

        <div class="mb-3">
            <input type="text" id="product_search" class="form-control" placeholder="Search product...">
            <div id="search_results" class="list-group mt-1"></div>
        </div>

        <table class="table table-bordered" id="sale_table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        $(document).ready(function () {
            // 🔍 Product live search
            $('#product_search').on('keyup', function () {
                let q = $(this).val();
                if (q.length < 1) { $('#search_results').empty(); return; }

                $.get('{{ route("sales.search") }}', { q }, function (data) {
                    let html = '';
                    data.forEach(p => {
                        html += `<a href="#" class="list-group-item list-group-item-action select-product" data-id="${p.id}" data-name="${p.name}" data-price="${p.sale_price}">${p.name} - ₱${p.sale_price}</a>`;
                    });
                    $('#search_results').html(html);
                });
            });

            // 🖱 Select product from search
            $(document).on('click', '.select-product', function (e) {
                e.preventDefault();
                let id = $(this).data('id');
                let name = $(this).data('name');
                let price = $(this).data('price');

                let row = `<tr>
                <td>${name}<input type="hidden" name="product_id" value="${id}"></td>
                <td><input type="number" class="form-control price" value="${price}"></td>
                <td><input type="number" class="form-control qty" value="1"></td>
                <td class="total">${price}</td>
                <td><input type="date" class="form-control date" value="{{ date('Y-m-d') }}"></td>
                <td><button class="btn btn-success btn-add-sale">Add</button></td>
            </tr>`;

                $('#sale_table tbody').append(row);
                $('#search_results').empty();
                $('#product_search').val('');
            });

            // ➕ Add sale via AJAX
            $(document).on('click', '.btn-add-sale', function () {
                let row = $(this).closest('tr');
                let data = {
                    product_id: row.find('input[name="product_id"]').val(),
                    price: row.find('.price').val(),
                    qty: row.find('.qty').val(),
                    date: row.find('.date').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.post('{{ route("sales.store") }}', data, function (res) {
                    alert(`Sale added: ${res.item} (${res.qty} pcs)`);
                    row.remove();
                }).fail(function (err) {
                    alert(err.responseJSON.error);
                });
            });

            // 💲 Recalculate total
            $(document).on('input', '.qty, .price', function () {
                let row = $(this).closest('tr');
                let total = row.find('.price').val() * row.find('.qty').val();
                row.find('.total').text(total);
            });
        });
    </script>
@endsection