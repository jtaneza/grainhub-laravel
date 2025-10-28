@extends('layouts.app')
@section('title', 'Change Password')

@section('content')
<div class="row">
  <div class="col-md-6">
    <div class="panel panel-default shadow-sm">
      <div class="panel-heading bg-primary text-white">
        <strong><i class="glyphicon glyphicon-lock"></i> Change Password</strong>
      </div>

      <div class="panel-body">
        {{-- ✅ Flash Message --}}
        @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
        @endif

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

        {{-- ✅ Form --}}
        <form action="{{ route('profile.password.update') }}" method="POST">
          @csrf
          @method('PUT')

          <div class="form-group">
            <label for="current_password">Current Password</label>
            <input 
              type="password" 
              class="form-control" 
              id="current_password" 
              name="current_password" 
              required
              placeholder="Enter your current password">
          </div>

          <div class="form-group">
            <label for="new_password">New Password</label>
            <input 
              type="password" 
              class="form-control" 
              id="new_password" 
              name="new_password" 
              required
              placeholder="Enter new password (min. 8 characters)">
          </div>

          <div class="form-group">
            <label for="new_password_confirmation">Confirm New Password</label>
            <input 
              type="password" 
              class="form-control" 
              id="new_password_confirmation" 
              name="new_password_confirmation" 
              required
              placeholder="Re-enter new password">
          </div>

          <div class="text-center">
            <button type="submit" class="btn btn-success">
              <i class="glyphicon glyphicon-ok"></i> Update Password
            </button>
            <a href="{{ route('profile.edit') }}" class="btn btn-default">
              <i class="glyphicon glyphicon-arrow-left"></i> Back
            </a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
