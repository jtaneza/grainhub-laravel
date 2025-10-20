@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-md-12">
    <h3>All Products</h3>
    <hr>
  </div>

  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading clearfix">

        {{-- 🔍 Search Form --}}
        <div class="pull-left">
          <form method="GET" action="{{ route('products.index') }}" class="form-inline">
            <input type="text" name="search" class="form-control" placeholder="Search product..."
                   value="{{ request('search') }}">
            <button type="submit" class="btn btn-primary">Search</button>
            @if(request('search'))
              <a href="{{ route('products.index') }}" class="btn btn-default">Clear</a>
            @endif
          </form>
        </div>

        {{-- ➕ Add New Button --}}
        <div class="pull-right">
          <a href="{{ route('products.create') }}" class="btn btn-success">
            <span class="glyphicon glyphicon-plus"></span> Add New
          </a>
        </div>

      </div>

      <div class="panel-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Photo</th>
              <th>Product Title</th>
              <th class="text-center" style="width: 10%;">Category</th>
              <th class="text-center" style="width: 15%;">Supplier</th>
              <th class="text-center" style="width: 10%;">In-Stock</th>
              <th class="text-center" style="width: 10%;">Buying Price</th>
              <th class="text-center" style="width: 10%;">Selling Price</th>
              <th class="text-center" style="width: 15%;">Entry Date</th>
              <th class="text-center" style="width: 10%;">Admin Name</th> {{-- ✅ New column --}}
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @forelse($products as $index => $product)
              <tr>
                <td class="text-center">{{ $index + 1 }}</td>

                {{-- 🖼 Product Image --}}
                <td class="text-center">
                  @if($product->media && $product->media->file_name)
                    <img src="{{ asset('uploads/products/'.$product->media->file_name) }}"
                         class="img-avatar img-circle" width="50" height="50" alt="">
                  @else
                    <img src="{{ asset('uploads/products/no_image.png') }}"
                         class="img-avatar img-circle" width="50" height="50" alt="">
                  @endif
                </td>

                {{-- 📦 Product Name --}}
                <td>{{ $product->name }}</td>

                {{-- 🏷 Category --}}
                <td class="text-center">
                  {{ $product->category->name ?? '—' }}
                </td>

                {{-- 🚚 Supplier --}}
                <td class="text-center">
                  {{ $product->supplier->name ?? '—' }}
                </td>

                {{-- 🔢 Quantity --}}
                <td class="text-center">{{ $product->quantity }}</td>

                {{-- 💰 Prices --}}
                <td class="text-center">₱{{ number_format($product->buy_price, 2) }}</td>
                <td class="text-center">₱{{ number_format($product->sale_price, 2) }}</td>

                {{-- 📅 Date --}}
                <td class="text-center">
                  {{ \Carbon\Carbon::parse($product->date)->format('F d, Y, h:i:s a') }}
                </td>

                {{-- 👤 Admin Name --}}
                <td class="text-center">
                  {{ $product->admin_name ?? 'Unknown' }}
                </td>

                {{-- 🧰 Actions --}}
                <td class="text-center">
                  <div class="btn-group">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-info btn-xs" title="Edit">
                      <span class="glyphicon glyphicon-edit"></span>
                    </a>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-danger btn-xs"
                              onclick="return confirm('Delete this product?')" title="Delete">
                        <span class="glyphicon glyphicon-trash"></span>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="11" class="text-center">No products found.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
