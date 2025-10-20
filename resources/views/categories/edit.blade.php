@extends('layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="row">
  <div class="col-md-8 col-md-offset-2">
    <div class="panel panel-default">
      <div class="panel-heading" style="border-bottom: 3px solid #f4b400;">
        <strong><i class="glyphicon glyphicon-edit"></i> EDIT CATEGORY</strong>
      </div>

      <div class="panel-body">
        {{-- ✅ Flash success message --}}
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- ⚠️ Validation errors --}}
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('categories.update', $category->id) }}">
          @csrf
          @method('PUT')

          <div class="form-group">
            <label for="name">Category Name</label>
            <input 
              type="text" 
              name="name" 
              id="name" 
              class="form-control" 
              value="{{ old('name', $category->name) }}" 
              placeholder="Enter category name" 
              required
            >
          </div>

          <div class="form-group text-center mt-3">
            <button type="submit" class="btn btn-primary">
              <i class="glyphicon glyphicon-ok"></i> Update Category
            </button>
            <a href="{{ route('categories.index') }}" class="btn btn-default">
              Cancel
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
