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

            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <strong><span class="glyphicon glyphicon-th"></span> All Sales</strong>
                    <div class="pull-right">
                        <button class="btn btn-primary" id="btnAddSale">Add sale</button>
                    </div>
                </div>

                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Product name</th>
                                <th class="text-center" style="width: 15%;">Stock In</th>
                                <th class="text-center" style="width: 15%;">Quantity</th>
                                <th class="text-center" style="width: 15%;">Total</th>
                                <th class="text-center" style="width: 15%;">Date</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sales as $index => $sale)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $sale->product->name ?? 'N/A' }}</td>
                                    <td class="text-center">{{ $sale->product->quantity ?? 0 }}</td>
                                    <td class="text-center">{{ $sale->qty }}</td>
                                    <td class="text-center">₱{{ number_format($sale->price, 2) }}</td>
                                    <td class="text-center">{{ $sale->date }}</td>
                                    <td class="text-center">
                                        <form action="{{ route('sales.destroy', $sale->id) }}" method="POST"
                                            onsubmit="return confirm('Delete sale?');">
                                            @csrf @method('DELETE')
                                            <a href="#" class="btn btn-warning btn-xs" title="Edit"><span
                                                    class="glyphicon glyphicon-edit"></span></a>
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

    {{-- Modal --}}
    <div class="modal fade" id="addSaleModal" tabindex="-1" role="dialog" aria-labelledby="addSaleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" id="addSaleContent"></div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#btnAddSale').click(function () {
                $('#addSaleContent').load("{{ route('sales.create') }}", function () {
                    $('#addSaleModal').modal('show');
                });
            });

            // Handle add sale form
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
                                .text(res.message)
                                .fadeIn().delay(4000).fadeOut();
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
        });
    </script>
@endsection