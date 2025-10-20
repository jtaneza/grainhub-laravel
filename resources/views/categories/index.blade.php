@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="row">
  {{-- ✅ Flash Messages --}}
  <div class="col-md-12">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif
  </div>
</div>

<div class="row">
  {{-- ✅ ADD NEW CATEGORY --}}
  <div class="col-md-5">
    <div class="panel panel-default">
      <div class="panel-heading" style="border-bottom: 3px solid #f4b400;">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>ADD NEW CATEGORY</span>
        </strong>
      </div>
      <div class="panel-body">
        <form method="POST" action="{{ route('categories.store') }}">
          @csrf
          <div class="form-group">
            <input 
              type="text" 
              name="name" 
              class="form-control" 
              placeholder="Category Name" 
              required>
          </div>
          <button type="submit" class="btn btn-primary">Add Category</button>
        </form>
      </div>
    </div>
  </div>

  {{-- ✅ ALL CATEGORIES --}}
  <div class="col-md-7">
    <div class="panel panel-default">
      <div class="panel-heading" style="border-bottom: 3px solid #f4b400;">
        <strong>
          <span class="glyphicon glyphicon-th"></span>
          <span>ALL CATEGORIES</span>
        </strong>
      </div>

      <div class="panel-body">
        <table class="table table-bordered table-striped table-hover">
          <thead>
            <tr>
              <th class="text-center" style="width: 50px;">#</th>
              <th>Categories</th>
              <th class="text-center" style="width: 100px;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($items as $index => $cat)
              <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ ucfirst($cat->name) }}</td>
                <td class="text-center">
                  <div class="btn-group">
                    {{-- ✅ Edit button triggers modal --}}
                    <button 
                      type="button" 
                      class="btn btn-xs btn-warning" 
                      data-toggle="modal" 
                      data-target="#editModal{{ $cat->id }}">
                      <span class="glyphicon glyphicon-edit"></span>
                    </button>

                    {{-- ✅ Delete form --}}
                    <form method="POST" action="{{ route('categories.destroy', $cat->id) }}" style="display:inline;">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-xs btn-danger" onclick="return confirm('Delete this category?')">
                        <span class="glyphicon glyphicon-trash"></span>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>

              {{-- ✅ Edit Modal --}}
              <div class="modal fade" id="editModal{{ $cat->id }}" tabindex="-1" role="dialog" aria-labelledby="editModalLabel{{ $cat->id }}">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <form method="POST" action="{{ route('categories.update', $cat->id) }}">
                      @csrf
                      @method('PUT')
                      <div class="modal-header">
                        <h4 class="modal-title" id="editModalLabel{{ $cat->id }}">
                          <i class="glyphicon glyphicon-edit"></i> Edit Category
                        </h4>
                      </div>
                      <div class="modal-body">
                        <div class="form-group">
                          <label for="name{{ $cat->id }}">Category Name</label>
                          <input 
                            type="text" 
                            name="name" 
                            id="name{{ $cat->id }}" 
                            class="form-control" 
                            value="{{ old('name', $cat->name) }}" 
                            required>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                          <i class="glyphicon glyphicon-ok"></i> Save Changes
                        </button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
