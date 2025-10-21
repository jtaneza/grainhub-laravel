<div class="modal fade" id="editSaleModal" tabindex="-1" role="dialog" aria-labelledby="editSaleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <!-- 🔹 Header -->
      <div class="modal-header">
        <h4 class="modal-title">Edit Sale</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- 🔹 Body -->
      <div class="modal-body">
        <div class="form-group">
          <input type="text" id="editSearchProduct" class="form-control" placeholder="Search for product name...">
          <div id="editSearchResults" class="list-group" style="position:absolute; z-index:1000; width:95%; display:none;"></div>
        </div>

        <div class="panel panel-default">
          <div class="panel-heading">
            <strong><span class="glyphicon glyphicon-th"></span> Edit Sale Entry</strong>
          </div>

          <div class="panel-body">
            <form id="editSaleForm">
              @csrf
              @method('PUT')
              <input type="hidden" name="sale_id" id="edit_sale_id">

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
                <tbody id="editProductInfo">
                  <!-- Existing sale will be preloaded here -->
                </tbody>
              </table>
            </form>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
$(document).ready(function () {

    // 🔍 Search product (for replacement)
    $('#editSearchProduct').on('keyup', function () {
        const query = $(this).val().trim();
        if (!query) return $('#editSearchResults').hide();

        $.get("{{ route('sales.search') }}", { query }, function (data) {
            let html = '';
            data.forEach(item => {
                html += `<a href="#" class="list-group-item edit-suggestion-item" 
                            data-id="${item.id}" 
                            data-name="${item.name}" 
                            data-price="${item.sale_price}" 
                            data-stock="${item.quantity}">
                            ${item.name} — ₱${item.sale_price} (${item.quantity} in stock)
                        </a>`;
            });
            $('#editSearchResults').html(html).fadeIn();
        });
    });

    // 🖱 Select replacement product
    $(document).on('click', '.edit-suggestion-item', function (e) {
        e.preventDefault();
        const id = $(this).data('id');
        const name = $(this).data('name');
        const price = parseFloat($(this).data('price'));
        const stock = parseInt($(this).data('stock'));
        $('#editSearchResults').hide();

        const admin = '{{ Auth::user()->name }}';

        const html = `
            <tr>
                <td>
                    <strong>${name}</strong>
                    <input type="hidden" name="product_id" value="${id}">
                </td>
                <td class="text-center">₱<span class="unit-price">${price.toFixed(2)}</span></td>
                <td class="text-center">
                    <input type="number" name="qty" value="1" min="1" max="${stock}" class="form-control text-center quantity-input" style="width:80px;">
                </td>
                <td class="text-center">₱<span class="total">${price.toFixed(2)}</span></td>
                <td class="text-center">${admin}</td>
                <td class="text-center"><input type="datetime-local" name="date" class="form-control text-center" required></td>
                <td class="text-center">
                    <button type="button" class="btn btn-primary btn-sm btn-update-sale">
                        <i class="glyphicon glyphicon-floppy-disk"></i> Update
                    </button>
                </td>
            </tr>
        `;

        $('#editProductInfo').html(html);
    });

    // 💾 Update Sale via AJAX
    $(document).on('click', '.btn-update-sale', function () {
        const row = $(this).closest('tr');
        const data = {
            _token: '{{ csrf_token() }}',
            _method: 'PUT',
            sale_id: $('#edit_sale_id').val(),
            product_id: row.find('input[name="product_id"]').val(),
            qty: row.find('input[name="qty"]').val(),
            price: row.find('.unit-price').text(),
            date: row.find('input[name="date"]').val(),
            admin_name: '{{ Auth::user()->name }}'
        };

        $.ajax({
            url: "{{ url('sales/update') }}/" + data.sale_id,
            method: 'POST',
            data: data,
            success: function (res) {
                if (res.success) {
                    $('#editSaleModal').modal('hide');
                    alert(`✅ Sale updated by ${res.sale.admin} on ${res.sale.date}`);
                    location.reload();
                } else {
                    alert(res.error || '⚠️ Something went wrong.');
                }
            },
            error: function () {
                alert('⚠️ Error updating sale.');
            }
        });
    });

    // 🧮 Update total dynamically
    $(document).on('input', '.quantity-input', function () {
        const qty = parseInt($(this).val()) || 0;
        const price = parseFloat($(this).closest('tr').find('.unit-price').text()) || 0;
        $(this).closest('tr').find('.total').text((qty * price).toFixed(2));
    });

    // 🧹 Hide search results when clicking outside
    $(document).on('click', function (e) {
        if (!$(e.target).closest('#editSearchProduct, #editSearchResults').length) {
            $('#editSearchResults').fadeOut();
        }
    });
});
</script>
