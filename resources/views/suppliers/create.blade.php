@extends('layouts.app')
@section('title', 'Add Supplier')
@section('content')
<div class="row">
  <div class="col-md-7 offset-md-2">
    <div class="panel panel-default shadow-sm">
      {{-- 🔹 Panel Heading --}}
      <div class="panel-heading clearfix" style="border-bottom: 3px solid #f4b400;">
        <strong>
          <i class="glyphicon glyphicon-plus"></i> ADD SUPPLIER
        </strong>
        <a href="{{ route('suppliers.index') }}" class="btn btn-default btn-sm pull-right">
          <i class="glyphicon glyphicon-arrow-left"></i> Back
        </a>
      </div>

      {{-- 🔹 Panel Body --}}
      <div class="panel-body">
        {{-- Display validation errors --}}
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('suppliers.store') }}" method="POST">
          @csrf

          {{-- 🏷 Supplier Name --}}
          <div class="form-group mb-3">
            <label for="name" class="fw-bold">Supplier Name:</label>
            <input type="text" name="name" id="name" class="form-control" 
                   placeholder="Enter supplier name" value="{{ old('name') }}" required>
          </div>

          {{-- ☎ Contact --}}
          <div class="form-group mb-3">
            <label for="contact" class="fw-bold">Contact:</label>
            <input type="text" name="contact" id="contact" class="form-control" 
                   placeholder="Enter contact number" value="{{ old('contact') }}">
          </div>

          {{-- 📧 Email --}}
          <div class="form-group mb-3">
            <label for="email" class="fw-bold">Email:</label>
            <input type="email" name="email" id="email" class="form-control" 
                   placeholder="Enter email address" value="{{ old('email') }}">
          </div>

          {{-- 🏠 Address --}}
          <div class="form-group mb-4">
            <label for="address" class="fw-bold">Address:</label>
            <textarea name="address" id="address" rows="3" class="form-control" 
                      placeholder="Enter supplier address">{{ old('address') }}</textarea>
          </div>

          {{-- 💾 Save Button --}}
          <div class="text-end">
            <button type="submit" class="btn btn-primary">
              <i class="glyphicon glyphicon-floppy-disk"></i> Save Supplier
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
