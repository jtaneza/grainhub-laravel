@extends('layouts.app')

@section('title', 'Add New User')

@section('content')
<div class="row">
    <div class="col-md-12">
        @if (session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @elseif ($errors->any())
            <div class="alert alert-danger text-center">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <div class="col-md-8">
        <div class="panel panel-default">
            <div class="panel-heading" style="border-bottom: 3px solid #FFa500;">
                <strong><i class="glyphicon glyphicon-th"></i> ADD NEW USER</strong>
            </div>
            <div class="panel-body">
                <form method="POST" action="{{ route('users.store') }}" autocomplete="off">
                    @csrf

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Full Name"
                            value="{{ old('name') }}">
                    </div>

                    <div class="form-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Username"
                            value="{{ old('username') }}">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Password">
                    </div>

                    <div class="form-group">
                        <label for="user_level">User Role</label>
                        <select name="user_level" class="form-control">
                            @foreach ($groups as $group)
                                <option value="{{ $group->group_level }}">
                                    {{ ucfirst($group->group_name) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group clearfix">
                        <button type="submit" class="btn btn-primary">Add User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
