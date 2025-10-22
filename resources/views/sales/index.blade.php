@extends('layouts.app')
@section('title', 'All Sales')

@section('content')
<div class="row">
    <div class="col-md-6">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div id="message" class="alert" style="display:none;"></div>

        <div class="panel panel-default shadow-sm">
            <div class="panel-heading clearfix bg-primary text-white p-2 rounded-top">
                <strong><span class="glyphicon glyphicon-th"></span> All Sales</strong>
                <div class="pull-right">
                    <button class="btn btn-success btn-sm" id="btnAddSale">
                        <i class="glyphicon glyphicon-plus"></i> Add Sale
                    </button>
                </div>
            </div>

            <div class="panel-body bg-light">
                <table class="table table-bordered table-striped">
                    <thead class="bg-primary text-white">
                        <tr>
                            <th class="text-center" style="width: 50px;">#</th>
                            <th>Product</th>
                            <th class="text-center" style="width: 10%;">Stock In</th>
                            <th class="text-center" style="width: 10%;">Qty</th>
                            <th class="text-center" style="width: 10%;">Total</th>
                            <th class="text-center" style="width: 15%;">Admin</th>
                            <th class="text-center" style="width: 20%;">Date & Time</th>
                            <th class="text-center" style="width: 100px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $sortedSales = $sales->sortByDesc('date');
                            $count = $sortedSales->count();
                        @endphp

                        @foreach($sortedSales as $index => $sale)
                            @php
                                if ($index < $count * 0.33) {
                                    $rowColor = '#d4edda';
                                } elseif ($index < $count * 0.66) {
                                    $rowColor = '#fff3cd';
                                } else {
                                    $rowColor = '#f8d7da';
                                }
                            @endphp

                            <tr style="background-color: {{ $rowColor }}">
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td>{{ $sale->product->name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $sale->product->quantity ?? 0 }}</td>
                                <td class="text-center">{{ $sale->qty }}</td>
                                <td class="text-center">₱{{ number_format($sale->price, 2) }}</td>
                                <td class="text-center">{{ $sale->admin_name ?? 'N/A' }}</td>
                                <td class="text-center">{{ \Carbon\Carbon::parse($sale->date)->format('Y-m-d h:i A') }}</td>

                                <td class="text-center">
                                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning btn-xs" title="Edit Sale">
    <span class="glyphicon glyphicon-edit"></span>
</a>


                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" onsubmit="return confirm('Delete sale?');" style="display:inline;">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-xs" title="Delete">
                                            <span class="glyphicon glyphicon-trash"></span>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Add Sale Modal --}}
<div class="modal fade" id="addSaleModal" tabindex="-1" role="dialog" aria-labelledby="addSaleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="addSaleContent"></div>
    </div>
</div>

{{-- Edit Sale Modal --}}
<div class="modal fade" id="editSaleModal" tabindex="-1" aria-labelledby="editSaleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="editSaleModalLabel">Edit Sale</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form id="editSaleForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="sale_id" id="edit_sale_id">

        <div class="modal-body">
          <div class="mb-2">
            <label class="form-label">Product</label>
            <select name="product_id" id="edit_product_id" class="form-select form-select-sm">
              @foreach($sales->pluck('product')->filter()->unique('id') as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-2">
            <label class="form-label">Quantity</label>
            <input type="number" name="qty" id="edit_qty" class="form-control form-control-sm" min="1" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Total Price</label>
            <input type="number" name="price" id="edit_price" class="form-control form-control-sm" step="0.01" required>
          </div>

          <div class="mb-2">
            <label class="form-label">Admin</label>
            <input type="text" name="admin_name" id="edit_admin" class="form-control form-control-sm bg-light" 
                   value="{{ Auth::user()->name }}" readonly>
          </div>

          <div class="mb-2">
            <label class="form-label">Date</label>
            <input type="datetime-local" name="date" id="edit_date" class="form-control form-control-sm" required>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-primary btn-sm">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    // ➕ Add Sale
    $('#btnAddSale').click(function () {
        $('#addSaleContent').load("{{ route('sales.create') }}", function () {
            $('#addSaleModal').modal('show');
        });
    });

    // 💾 Add Sale Submit
    $(document).on('submit', '#addSaleForm', function (e) {
        e.preventDefault();
        $.ajax({
            url: "{{ route('sales.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    $('#addSaleModal').modal('hide');
                    $('#message').removeClass('alert-danger')
                        .addClass('alert-success')
                        .text('✅ Sale added successfully by ' + res.sale.admin)
                        .fadeIn().delay(3000).fadeOut();
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function () {
                $('#message').removeClass('alert-success')
                    .addClass('alert-danger')
                    .text("⚠️ Error while adding sale.")
                    .fadeIn().delay(4000).fadeOut();
            }
        });
    });

    // ✏️ Edit Sale Button
    $('.btn-edit-sale').click(function () {
        const sale = $(this).data('sale');
        $('#edit_sale_id').val(sale.id);
        $('#edit_product_id').val(sale.product_id);
        $('#edit_qty').val(sale.qty);
        $('#edit_price').val(sale.price);
        $('#edit_date').val(sale.date);
        $('#edit_admin').val('{{ Auth::user()->name }}');
        $('#editSaleModal').modal('show');
    });

    // 💾 Update Sale (AJAX)
    $('#editSaleForm').submit(function (e) {
        e.preventDefault();
        const id = $('#edit_sale_id').val();
        const formData = $(this).serialize();

        $.ajax({
            url: `/sales/${id}`,
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('input[name="_token"]').val()
            },
            success: function (data) {
                if (data.success) {
                    alert(`✅ Sale updated successfully by ${data.sale.admin_name}`);
                    location.reload();
                } else {
                    alert(data.error || 'Something went wrong.');
                }
            },
            error: function () {
                alert('⚠️ Error updating sale.');
            }
        });
    });
});
</script>
@endpush
