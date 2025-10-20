@extends('layouts.app')
@section('title', 'Add Sale')

@section('content')
<div class="row mb-3">
  <div class="col-md-12">
    <div class="d-flex justify-content-between align-items-center">
      <h3 class="mb-0"><i class="glyphicon glyphicon-plus"></i> Add Sale</h3>
      <a href="{{ route('sales.index') }}" class="btn btn-secondary">
        <i class="glyphicon glyphicon-arrow-left"></i> Back
      </a>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <div id="message" class="alert d-none"></div>

    <form id="search-form">
      <div class="form-group position-relative">
        <label for="sug_input" class="fw-bold">Search Product</label>
        <div class="input-group">
          <span class="input-group-addon"><i class="glyphicon glyphicon-search"></i></span>
          <input type="text" id="sug_input" class="form-control" placeholder="Type product name...">
        </div>
        <div id="result"
             class="list-group position-absolute w-100 mt-1 shadow-sm"
             style="z-index: 1000;"></div>
      </div>
    </form>
  </div>
</div>

<div class="row mt-4">
  <div class="col-md-12">
    <div class="panel panel-default shadow">
      <div class="panel-heading clearfix bg-primary text-white p-2 rounded-top">
        <strong><i class="glyphicon glyphicon-th"></i> Sale Entry</strong>
      </div>

      <div class="panel-body p-3 bg-light">
        <form id="add-sale-form">
          <table class="table table-bordered table-striped">
            <thead class="table-primary">
              <tr>
                <th>Item</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total</th>
                <th>Date</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody id="product_info"></tbody>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- ✅ JS for AJAX functionality --}}
<script>
$(document).ready(function() {
  // 🔍 Live search
  $("#sug_input").on("keyup", function() {
    const query = $(this).val().trim();
    if (query.length < 1) {
      $("#result").fadeOut();
      return;
    }

    $.ajax({
      url: "{{ route('sales.search') }}",
      type: "GET",
      data: { query },
      success: function(data) {
        let html = "";
        data.forEach(item => {
          html += `<a href="#" class="list-group-item list-group-item-action suggestion-item" 
                      data-id="${item.id}" data-name="${item.name}">
                      <i class='glyphicon glyphicon-tag'></i> ${item.name}
                   </a>`;
        });
        $("#result").html(html).fadeIn();
      }
    });
  });

  // 🖱 Select suggestion
  $(document).on("click", ".suggestion-item", function(e) {
    e.preventDefault();
    const id = $(this).data("id");
    $("#result").fadeOut();

    $.ajax({
      url: "{{ route('sales.getProduct') }}",
      type: "POST",
      data: { id, _token: '{{ csrf_token() }}' },
      success: function(res) {
        $("#product_info").html(res.html);
      }
    });
  });

  // 💾 Add sale
  $(document).on("click", "#btn-add-sale", function(e) {
    e.preventDefault();

    const row = $(this).closest("tr");
    const data = {
      product_id: row.find("input[name='product_id']").val(),
      quantity: row.find("input[name='quantity']").val(),
      _token: '{{ csrf_token() }}'
    };

    $.ajax({
      url: "{{ route('sales.store') }}",
      type: "POST",
      data,
      success: function(res) {
        if (res.success) {
          alert("✅ Sale added by " + res.sale.admin);
          $("#product_info").empty();
          $("#sug_input").val("");
        } else {
          alert("⚠️ " + res.error);
        }
      }
    });
  });

  // 🧹 Hide suggestion box when clicking outside
  $(document).on("click", function(e) {
    if (!$(e.target).closest("#sug_input, #result").length) {
      $("#result").fadeOut();
    }
  });
});
</script>
@endsection
