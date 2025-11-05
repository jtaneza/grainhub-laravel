@extends('layouts.app')
@section('title', 'My Profile')

@section('content')
<div class="row">

  {{-- ✅ LEFT PANEL - Profile --}}
  <div class="col-md-4">
    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="panel profile">
      <div class="jumbotron text-center" style="background-color:#122030; color:white; border-radius:0;">

        {{-- ✅ User Image --}}
        @php
          $imagePath = $user->image 
              ? asset('storage/' . $user->image) 
              : asset('uploads/users/default.png'); // ✅ fixed path
        @endphp

        <img 
          class="img-circle img-size-2" 
          src="{{ $imagePath }}" 
          alt="User Photo"
          style="width:120px; height:120px; object-fit:cover; margin-bottom:10px;"
        >

        <h3 style="margin-top:10px;">{{ ucfirst($user->name) }}</h3>
        <p style="opacity:0.8;">{{ '@' . $user->username }}</p>
      </div>

      {{-- ✅ Profile Navigation --}}
      <ul class="nav nav-pills nav-stacked">
        <li>
          <a href="{{ route('profile.edit') }}">
            <i class="glyphicon glyphicon-edit"></i> Edit Profile
          </a>
        </li>
        <li>
          <a href="{{ route('profile.password.edit') }}">
            <i class="glyphicon glyphicon-lock"></i> Change Password
          </a>
        </li>
      </ul>
    </div>
  </div>

  {{-- ✅ RIGHT PANEL - Account Information --}}
  <div class="col-md-8">
    <div class="panel panel-default">
      <div class="panel-heading bg-light">
        <strong><i class="glyphicon glyphicon-user"></i> MY ACCOUNT DETAILS</strong>
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

        <div class="text-right">
          <a href="{{ route('profile.edit') }}" class="btn btn-info">Edit Account</a>
          <a href="{{ route('profile.password.edit') }}" class="btn btn-danger">Change Password</a>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection

