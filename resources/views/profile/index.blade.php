@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="row">

  {{-- LEFT: Profile Picture --}}
  <div class="col-md-6">
    <div class="panel panel-default">
      <div class="panel-heading bg-light">
        <strong><i class="glyphicon glyphicon-camera"></i> MY PHOTO</strong>
      </div>
      <div class="panel-body text-center">
        <img 
          src="{{ $user->image ? asset('storage/'.$user->image) : asset('uploads/users/default.png') }}" 
          alt="Profile Photo"
          class="img-circle"
          style="width:120px; height:120px; object-fit:cover; margin-bottom:10px;"
        >
        <br>
        <a href="{{ route('profile.edit') }}" class="btn btn-warning">Change Photo</a>
      </div>
    </div>
  </div>

  {{-- RIGHT: Account Information --}}
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
          <input type="text" class="form-control" 
                 value="@if($user->user_level == 1) Administrator 
                        @elseif($user->user_level == 2) Special User 
                        @else User 
                        @endif" readonly>
        </div>

        <a href="{{ route('profile.edit') }}" class="btn btn-info">Edit Account</a>
        <a href="{{ route('profile.password.edit') }}" class="btn btn-danger pull-right">Change Password</a>
      </div>
    </div>
  </div>
</div>
@endsection
