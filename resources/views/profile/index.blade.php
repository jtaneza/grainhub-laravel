@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="row">

  {{-- ✅ LEFT: Profile Picture --}}
  <div class="col-md-6">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel panel-default">
      <div class="panel-heading bg-light">
        <strong><i class="glyphicon glyphicon-camera"></i> MY PHOTO</strong>
      </div>

      <div class="panel-body text-center">
        @php
          // ✅ Determine correct image path
          $imagePath = $user->image 
            ? asset('storage/' . $user->image) 
            : asset('uploads/users/default.png'); // fixed default path
        @endphp

<img 
    src="{{ $imagePath }}" 
    alt="Profile Photo"
    class="img-circle"
    style="width:120px; height:120px; object-fit:cover; margin-bottom:10px;"
>


        <br>
        <a href="{{ route('profile.edit') }}" class="btn btn-warning">
          <i class="glyphicon glyphicon-edit"></i> Change Photo
        </a>
      </div>
    </div>
  </div>

  {{-- ✅ RIGHT: Account Information --}}
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading bg-light">
        <strong><i class="glyphicon glyphicon-user"></i> MY ACCOUNT</strong>
      </div>

      <div class="panel-body">
        <div class="form-group">
          <label>Name</label>
          <input type="text" class="form-control" value="{{ $user->name }}" readonly>
        </div>

        <div class="form-group">
          <label>Username</label>
          <input type="text" class="form-control" value="{{ $user->username }}" readonly>
        </div>

        <div class="form-group">
          <label>Role</label>
          <input 
            type="text" 
            class="form-control" 
            value="@if($user->user_level == 1) Administrator 
                    @elseif($user->user_level == 2) Special User 
                    @else User 
                    @endif" 
            readonly
          >
        </div>

        <div class="d-flex justify-content-between">
          <a href="{{ route('profile.edit') }}" class="btn btn-info">
            <i class="glyphicon glyphicon-cog"></i> Edit Account
          </a>
          <a href="{{ route('profile.password.edit') }}" class="btn btn-danger">
            <i class="glyphicon glyphicon-lock"></i> Change Password
          </a>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
