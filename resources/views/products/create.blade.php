@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading clearfix" style="border-bottom: 3px solid #f4b400;">
    <strong><i class="glyphicon glyphicon-plus"></i> ADD PRODUCT</strong>
    <a href="{{ route('products.index') }}" class="btn btn-default btn-sm pull-right">
      <i class="glyphicon glyphicon-arrow-left"></i> Back
    </a>
  </div>

  <div class="panel-body">
    @if($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="form-group">
        <label>Product Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
      </div>

      <div class="form-group">
        <label>Category</label>
        <select name="category_id" class="form-control" required>
          <option value="">Select category</option>
          @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ old('category_id') == $c->id ? 'selected' : '' }}>
              {{ $c->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Supplier</label>
        <select name="supplier_id" class="form-control">
          <option value="">Select supplier</option>
          @foreach($suppliers as $s)
            <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
              {{ $s->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="form-group">
        <label>Quantity</label>
        <input type="number" name="quantity" class="form-control" value="{{ old('quantity') }}" required>
      </div>

      <div class="form-group">
        <label>Buy Price</label>
        <input type="number" step="0.01" name="buy_price" class="form-control" value="{{ old('buy_price') }}" required>
      </div>

      <div class="form-group">
        <label>Sale Price</label>
        <input type="number" step="0.01" name="sale_price" class="form-control" value="{{ old('sale_price') }}" required>
      </div>

      <div class="form-group">
        <label>Product Photo</label>
        <input type="file" name="photo" class="form-control">
      </div>

      <button type="submit" class="btn btn-primary">
        <i class="glyphicon glyphicon-save"></i> Save Product
      </button>
    </form>
  </div>
</div>
@endsection
