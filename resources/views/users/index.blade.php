@extends('layouts.app')

@section('title', 'User Management')

@section('content')
    <div class="row">
        <div class="col-md-10">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
        </div>

        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading clearfix" style="border-bottom: 3px solid #FFa500;">
                    <strong>
                        <i class="glyphicon glyphicon-th"></i> USERS
                    </strong>
                    <a href="{{ route('users.create') }}" class="btn btn-info btn-sm pull-right"
                       style="background-color:#51aded; border-color:#51aded;">
                        <i class="glyphicon glyphicon-plus"></i> Add New User
                    </a>
                </div>

                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Name</th>
                                <th>Username</th>
                                <th class="text-center" style="width: 15%;">User Role</th>
                                <th class="text-center" style="width: 10%;">Status</th>
                                <th style="width: 20%;">Last Login</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $index => $user)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ ucfirst($user->name) }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td class="text-center">
                                        {{ $user->group ? ucfirst($user->group->group_name) : 'N/A' }}
                                    </td>
                                    <td class="text-center">
                                        @if ($user->status == 1)
                                            <span class="label label-success">Active</span>
                                        @else
                                            <span class="label label-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->last_login ? \Carbon\Carbon::parse($user->last_login)->format('F d, Y, g:i a') : 'Never' }}</td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning btn-xs"
                                               title="Edit">
                                                <i class="glyphicon glyphicon-pencil"></i>
                                            </a>
                                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                  style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-xs"
                                                        onclick="return confirm('Delete this user?')">
                                                    <i class="glyphicon glyphicon-remove"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
