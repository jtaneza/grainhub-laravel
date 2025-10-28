@extends('layouts.app')
@section('title', 'Edit Profile')

@section('content')
<div class="row">
  {{-- LEFT: Profile Picture --}}
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading bg-light">
        <strong><i class="glyphicon glyphicon-camera"></i> CHANGE MY PHOTO</strong>
      </div>
      <div class="panel-body text-center">
        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          
          <img 
            id="preview"
            src="{{ $user->image ? asset('storage/'.$user->image) : asset('uploads/users/default.png') }}" 
            alt="Profile Photo"
            class="img-circle"
            style="width:120px; height:120px; object-fit:cover; margin-bottom:10px;"
          >
          
          <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(event)" style="margin-bottom:10px;">
          <button type="submit" class="btn btn-warning">Change</button>
        </form>
      </div>
    </div>
  </div>

  {{-- RIGHT: Edit Info --}}
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading bg-light">
        <strong><i class="glyphicon glyphicon-edit"></i> EDIT MY ACCOUNT</strong>
      </div>
      <div class="panel-body">
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul style="margin:0;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
          @csrf
          @method('PUT')
          <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
          </div>

          <div class="form-group">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
          </div>

          <button type="submit" class="btn btn-info">Update</button>
          <a href="{{ route('profile.show') }}" class="btn btn-default">Cancel</a>
          <a href="#" class="btn btn-danger pull-right">Change Password</a>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- Preview Image Script --}}
@push('scripts')
<script>
  function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
      document.getElementById('preview').src = reader.result;
    }
    reader.readAsDataURL(event.target.files[0]);
  }
</script>
@endpush
@endsection
