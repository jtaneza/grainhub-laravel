@extends('layouts.app')

@section('title', 'Manage Groups')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <strong>
                        <i class="glyphicon glyphicon-th"></i>
                        GROUPS
                    </strong>
                    <a href="{{ route('groups.create') }}" class="btn btn-info btn-sm pull-right"
                        style="background-color:#51aded; border-color:#51aded;">
                        <i class="glyphicon glyphicon-plus"></i> ADD NEW GROUP
                    </a>
                </div>

                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                        <thead style="border-bottom: 3px solid #FFa500;">
                            <tr>
                                <th class="text-center" style="width: 40px;">#</th>
                                <th style="width: 300px;">Group Name</th>
                                <th class="text-center" style="width: 150px;">Group Level</th>
                                <th class="text-center" style="width: 150px;">Status</th>
                                <th class="text-center" style="width: 150px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($groups as $index => $group)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ ucfirst($group->group_name) }}</td>
                                    <td class="text-center">{{ $group->group_level }}</td>
                                    <td class="text-center">
                                        @if($group->group_status == 1)
                                            <span class="label label-success">Active</span>
                                        @else
                                            <span class="label label-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('groups.edit', $group->id) }}" class="btn btn-warning btn-xs">
                                            <i class="glyphicon glyphicon-pencil"></i>
                                        </a>
                                        <form action="{{ route('groups.destroy', $group->id) }}" method="POST"
                                            style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-xs"
                                                onclick="return confirm('Delete this group?')">
                                                <i class="glyphicon glyphicon-remove"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">No active groups found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection