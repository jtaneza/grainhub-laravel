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
    // ✅ Use storage path for uploaded images, fallback to default
    $imagePath = $user->image && file_exists(public_path('storage/' . $user->image))
        ? asset('storage/' . $user->image)
        : asset('storage/uploads/users/default.png'); // make sure default exists here
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

@endsection

