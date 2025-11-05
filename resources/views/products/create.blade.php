@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="container-fluid" style="padding: 20px;">

    <div class="panel panel-default">
        <div class="panel-heading" style="background-color: #f8f8f8; border-bottom: 2px solid #f0ad4e;">
            <h3 class="panel-title" style="font-weight: bold;">
                <i class="glyphicon glyphicon-plus"></i> ADD PRODUCT
            </h3>
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

                {{-- 🏷 Product Name --}}
                <div class="form-group">
                    <label for="name" class="control-label">Product Name</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
                        <input type="text"
                               name="name"
                               class="form-control"
                               value="{{ old('name') }}"
                               placeholder="Enter product name"
                               required>
                    </div>
                </div>

                {{-- 📂 Category & Supplier --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Category</label>
                            <select name="category_id" class="form-control" required>
                                <option value="">Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Supplier</label>
                            <select name="supplier_id" class="form-control">
                                <option value="">Select Supplier</option>
                                @foreach ($suppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>
                                        {{ $sup->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- 🖼 Product Image --}}
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label">Product Image</label>
                            <div class="input-group">
                                <input type="text"
                                       class="form-control"
                                       value="No file chosen"
                                       readonly>
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-primary" onclick="document.getElementById('photo').click();">
                                        Browse
                                    </button>
                                </span>
                            </div>
                            <input type="file" id="photo" name="photo" style="display:none;" accept="image/*">
                        </div>
                    </div>
                </div>

                {{-- 🔢 Quantity & Prices --}}
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control"
                                   value="{{ old('quantity') }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Buying Price</label>
                            <input type="number" step="0.01" name="buy_price" class="form-control"
                                   value="{{ old('buy_price') }}" required>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="control-label">Selling Price</label>
                            <input type="number" step="0.01" name="sale_price" class="form-control"
                                   value="{{ old('sale_price') }}" required>
                        </div>
                    </div>
                </div>

                {{-- 👤 Admin Name --}}
                <div class="form-group">
                    <label class="control-label">Admin Name</label>
                    <input type="text" name="admin_name" class="form-control"
                           value="{{ Auth::user()->name ?? 'Unknown' }}" readonly>
                </div>

                {{-- ✅ Save Button (Bottom Left) --}}
                <div class="row">
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="glyphicon glyphicon-save"></i> Save Product
                        </button>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection
