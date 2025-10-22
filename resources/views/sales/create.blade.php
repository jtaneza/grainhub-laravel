<div class="modal-header">
    <h4 class="modal-title">Add Sale</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
    <div class="form-group">
        <input type="text" id="searchProduct" class="form-control" placeholder="Search for product name...">
        <div id="searchResults" class="list-group" style="position:absolute; z-index:1000; width:95%; display:none;"></div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><span class="glyphicon glyphicon-th"></span> Sale Entry</strong>
        </div>

        <div class="panel-body">
            <form id="addSaleForm">
                @csrf
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                            <th>Admin</th>
                            <th>Date/Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="productInfo"></tbody>
                </table>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    // 🔍 Search products
    $('#searchProduct').on('keyup', function () {
        const query = $(this).val().trim();
        if (!query) return $('#searchResults').hide();

        $.get("{{ route('sales.search') }}", { query }, function (data) {
            let html = '';
            data.forEach(item => {
                html += `<a href="#" class="list-group-item suggestion-item" 
                            data-id="${item.id}" 
                            data-name="${item.name}" 
                            data-price="${item.sale_price}" 
                            data-stock="${item.quantity}">
                            ${item.name} — ₱${item.sale_price} (${item.quantity} in stock)
                        </a>`;
            });
            $('#searchResults').html(html).fadeIn();
        });
    });

// 🖱 Select a product from suggestions
$(document).on('click', '.suggestion-item', function (e) {
    e.preventDefault();
    const id = $(this).data('id');
    const name = $(this).data('name');
    const price = parseFloat($(this).data('price'));
    const stock = parseInt($(this).data('stock'));
    $('#searchResults').hide();

    const admin = '{{ Auth::user()->name }}';
    const currentDateTime = new Date().toLocaleString('en-PH', { timeZone: 'Asia/Manila' });

    const html = `
        <tr>
            <td>
                <strong>${name}</strong>
                <input type="hidden" name="product_id" value="${id}">
            </td>
            <td class="text-center">₱<span class="unit-price">${price.toFixed(2)}</span></td>
            <td class="text-center">
                <input type="number" name="quantity" value="1" min="1" max="${stock}" class="form-control quantity-input text-center" style="width:80px;">
            </td>
            <td class="text-center">₱<span class="total">${price.toFixed(2)}</span></td>
            <td class="text-center">${admin}</td>
            <td class="text-center">${currentDateTime}</td>
            <td class="text-center">
                <button type="button" class="btn btn-success btn-sm btn-add-sale">
                    <i class="glyphicon glyphicon-plus"></i> Add
                </button>
            </td>
        </tr>
    `;

    $('#productInfo').html(html);
});

    // 💾 Add Sale AJAX
    $(document).on('click', '.btn-add-sale', function () {
        const row = $(this).closest('tr');
        const data = {
            _token: '{{ csrf_token() }}',
            product_id: row.find('input[name="product_id"]').val(),
            quantity: row.find('input[name="quantity"]').val(),
            admin_name: '{{ Auth::user()->name }}'
        };

        $.ajax({
            url: "{{ route('sales.store') }}",
            method: 'POST',
            data: data,
            success: function (res) {
                if (res.success) {
                    $('#addSaleModal').modal('hide');
                    alert(`✅ Sale added by ${res.sale.admin} at ${res.sale.date}`);
                    location.reload(); // Refresh table to show correct server date
                } else {
                    alert(res.error || '⚠️ Something went wrong.');
                }
            },
            error: function () {
                alert('⚠️ Error saving sale.');
            }
        });
    });

    // 🧹 Hide suggestions when clicking outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#searchProduct, #searchResults').length) {
            $('#searchResults').fadeOut();
        }
    });

    // 🧮 Update total when quantity changes
    $(document).on('input', '.quantity-input', function () {
        const qty = parseInt($(this).val()) || 0;
        const price = parseFloat($(this).closest('tr').find('.unit-price').text()) || 0;
        $(this).closest('tr').find('.total').text((qty * price).toFixed(2));
    });
});
</script>
