@extends('layouts.app')
@section('title', 'Edit Account')

@section('content')
<div class="row">

  {{-- LEFT PANEL - Change Photo --}}
  <div class="col-md-6">
    <div class="panel panel-default shadow-sm">
      <div class="panel-heading clearfix" style="border-bottom:3px solid orange;">
        <strong><span class="glyphicon glyphicon-camera"></span> CHANGE MY PHOTO</strong>
      <a href="{{ route('profile.show') }}" class="btn btn-default btn-sm pull-right">
                        <i class="glyphicon glyphicon-arrow-left"></i> Back
                    </a>
      </div>
      <div class="panel-body text-center">
        {{-- ✅ Success Message --}}
        @if (session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- ✅ Separate Form for Photo --}}
        <form action="{{ route('profile.update.photo') }}" method="POST" enctype="multipart/form-data">
          @csrf

          <img 
    id="preview"
    class="img-circle"
    src="{{ $user->image 
            ? asset('storage/' . $user->image) 
            : asset('storage/uploads/users/default.png') }}"
    alt="Profile Picture"
    style="width:120px; height:120px; object-fit:cover; border-radius:50%; margin-bottom:15px;"
>


          <input 
            type="file" 
            name="image" 
            class="form-control" 
            accept="image/*" 
            onchange="previewImage(event)"
            style="max-width:350px; margin:10px auto;"
          >

          <button type="submit" class="btn btn-warning" style="margin-top:10px;">Change</button>
        </form>
      </div>
    </div>
  </div>

  {{-- RIGHT PANEL - Edit Info --}}
  <div class="col-md-6">
    <div class="panel panel-default shadow-sm">
      <div class="panel-heading clearfix" style="border-bottom:3px solid orange;">
        <strong><span class="glyphicon glyphicon-edit"></span> EDIT MY ACCOUNT</strong>
      </div>
      <div class="panel-body">
        {{-- ✅ Validation Errors --}}
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul style="margin:0;">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        {{-- ✅ Separate Form for Info Update --}}
        <form action="{{ route('profile.update.info') }}" method="POST">
          @csrf
          @method('PUT')

          <div class="form-group">
            <label>Name</label>
            <input 
              type="text" 
              name="name" 
              class="form-control" 
              value="{{ old('name', $user->name) }}" 
              required
            >
          </div>

          <div class="form-group">
            <label>Username</label>
            <input 
              type="text" 
              name="username" 
              class="form-control" 
              value="{{ old('username', $user->username) }}" 
              required
            >
          </div>

          <div class="form-group text-right" style="margin-top:20px;">
            <button type="submit" class="btn btn-info">
              <i class="glyphicon glyphicon-ok"></i> Update
            </button>
            <a href="{{ route('profile.password.edit') }}" class="btn btn-danger">
              <i class="glyphicon glyphicon-lock"></i> Change Password
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

{{-- ✅ JS: Live Image Preview --}}
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
