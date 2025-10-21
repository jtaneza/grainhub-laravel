<tr>
    <td>
        <strong>{{ $product->name }}</strong>
        <input type="hidden" name="product_id" value="{{ $product->id }}">
    </td>

    <td class="text-center">
        ₱<span class="unit-price">{{ number_format($product->sale_price, 2) }}</span>
    </td>

    <td class="text-center">
        <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}"
            class="form-control quantity-input text-center" style="width:80px;">
    </td>

    <td class="text-center">
        ₱<span class="total">{{ number_format($product->sale_price, 2) }}</span>
    </td>

    <td class="text-center">
        {{ Auth::user()->name }}
        <input type="hidden" name="admin_name" value="{{ Auth::user()->name }}">
    </td>

    <td class="text-center">
        {{ now()->format('Y-m-d H:i:s') }}
        <input type="hidden" name="date" value="{{ now()->format('Y-m-d H:i:s') }}">
    </td>

    <td class="text-center">
        <button type="button" class="btn btn-success btn-sm btn-add-sale">
            <i class="glyphicon glyphicon-plus"></i> Add
        </button>
    </td>
</tr>

<script>
$(document).ready(function () {
    const row = $('tr').last();

    // 🧮 Auto-update total when quantity changes
    row.find('.quantity-input').on('input', function () {
        const qty = parseInt($(this).val()) || 0;
        const price = parseFloat(row.find('.unit-price').text().replace(/[₱,]/g, '')) || 0;
        const total = qty * price;
        row.find('.total').text(total.toLocaleString('en-PH', { minimumFractionDigits: 2 }));
    });

    // ✅ Prevent exceeding max stock and minimum 1
    row.find('.quantity-input').on('change', function () {
        const max = parseInt($(this).attr('max')) || 0;
        let val = parseInt($(this).val()) || 0;

        if (val > max) {
            alert(`⚠️ Only ${max} units left in stock.`);
            val = max;
        } else if (val < 1) {
            val = 1;
        }

        $(this).val(val).trigger('input');
    });
});
</script>
