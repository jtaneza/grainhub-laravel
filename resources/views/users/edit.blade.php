@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
    <div class="row">
        <div class="col-md-12">
            {{-- ✅ Display success or error messages --}}
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>

        <!-- ✅ Update Account Section -->
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading" style="border-bottom: 3px solid #FFa500;">
                    <strong>
                        <i class="glyphicon glyphicon-th"></i> UPDATE ACCOUNT
                    </strong>
                </div>

                <div class="panel-body">
                    <form method="POST" action="{{ route('users.update', $user->id) }}" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="name" class="control-label">Name</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                placeholder="Enter full name" required>
                        </div>

                        <div class="form-group">
                            <label for="username" class="control-label">Username</label>
                            <input type="text" name="username" class="form-control"
                                value="{{ old('username', $user->username) }}" placeholder="Enter username" required>
                        </div>

                        <div class="form-group">
                            <label for="user_level">User Role</label>
                            <select name="user_level" class="form-control" required>
                                @foreach ($groups as $group)
                                    <option value="{{ $group->group_level }}" {{ $group->group_level == $user->user_level ? 'selected' : '' }}>
                                        {{ ucfirst($group->group_name) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="status" class="control-label">Status</label>
                            <select name="status" class="form-control">
                                <option value="1" {{ $user->status == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ $user->status == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div class="form-group clearfix">
                            <button type="submit" class="btn btn-info"
                                style="background-color:#51aded; border-color:#51aded;">
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- ✅ Change Password Section -->
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading" style="border-bottom: 3px solid #FFa500;">
                    <strong>
                        <i class="glyphicon glyphicon-lock"></i> CHANGE PASSWORD
                    </strong>
                </div>

                <div class="panel-body">
                    <form method="POST" action="{{ route('users.updatePassword', $user->id) }}" autocomplete="off">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="password" class="control-label">Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Type new password"
                                required>
                        </div>

                        <div class="form-group clearfix">
                            <button type="submit" class="btn btn-danger pull-right">Change</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection