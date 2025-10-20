<div class="modal-header">
    <h4 class="modal-title">Add Sale</h4>
    <button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
    <div class="form-group">
        <input type="text" id="searchProduct" class="form-control" placeholder="Search for product name...">
        <div id="searchResults" class="list-group" style="position:absolute; z-index:1000; width:95%; display:none;">
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <strong><span class="glyphicon glyphicon-th"></span> Sale Edit</strong>
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
                            <th>Date</th>
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
                    html += `<a href="#" class="list-group-item suggestion-item" data-id="${item.id}">
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
            $('#searchResults').hide();
            $.get("{{ route('sales.product') }}", { id }, function (res) {
                $('#productInfo').html(res.html);
            });
        });

        // 💾 Add Sale AJAX
        $(document).on('click', '.add-sale-btn', function () {
            const row = $(this).closest('tr');
            const data = {
                _token: '{{ csrf_token() }}',
                product_id: row.find('input[name="product_id"]').val(),
                quantity: row.find('input[name="quantity"]').val()
            };

            $.ajax({
                url: "{{ route('sales.store') }}",
                method: 'POST',
                data: data,
                success: function (res) {
                    if (res.success) {
                        $('#addSaleModal').modal('hide');
                        alert('✅ Sale added successfully!');
                        location.reload();
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
    });
</script>