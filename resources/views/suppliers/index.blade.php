@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-md-12">
    @if (session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if (session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
  </div>
</div>

<div class="panel panel-default shadow-sm">
  <div class="panel-heading" style="display: flex; justify-content: space-between; align-items: center;">
    <strong>
      <span class="glyphicon glyphicon-briefcase"></span>
      Supplier Management
    </strong>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary btn-sm pull-right">
      <i class="glyphicon glyphicon-plus"></i> Add Supplier
    </a>
  </div>

  <div class="panel-body">
    <table class="table table-bordered table-striped table-hover align-middle">
      <thead class="bg-light">
        <tr>
          <th class="text-center" style="width: 50px;">#</th>
          <th>Supplier Name</th>
          <th>Contact</th>
          <th>Email</th>
          <th>Address</th>
          <th class="text-center" style="width: 120px;">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($suppliers as $index => $supplier)
          <tr>
            <td class="text-center">{{ $index + 1 }}</td>
            <td>{{ $supplier->name }}</td>
            <td>{{ $supplier->contact }}</td>
            <td>{{ $supplier->email }}</td>
            <td>{{ $supplier->address }}</td>
            <td class="text-center">
              <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning btn-xs" title="Edit">
                <span class="glyphicon glyphicon-pencil"></span>
              </a>
              <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-xs" title="Delete">
                  <span class="glyphicon glyphicon-trash"></span>
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted">No suppliers found.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
