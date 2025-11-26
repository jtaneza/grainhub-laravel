@extends('layouts.app')

@section('title', 'Add Group')

@section('content')
    <div class="row">
        <div class="col-md-11">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin-bottom: 0;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading clearfix">
                    <strong>
                        <i class="glyphicon glyphicon-th"></i>
                        <span>Add New Group</span>
                    </strong>
                    <a href="{{ route('groups.index') }}" class="btn btn-default btn-sm pull-right">
                        <i class="glyphicon glyphicon-arrow-left"></i> Back
                    </a>
                </div>

                <div class="panel-body">
                    <div class="col-md-12">
                        <form method="POST" action="{{ route('groups.store') }}" autocomplete="off">
                            @csrf

                            <div class="form-group">
                                <label for="group_name">Group Name</label>
                                <input type="text" class="form-control" name="group_name" id="group_name"
                                    value="{{ old('group_name') }}" placeholder="Enter group name" required>
                            </div>

                            <div class="form-group">
                                <label for="group_level">Group Level</label>
                                <input type="number" class="form-control" name="group_level" id="group_level"
                                    value="{{ old('group_level', $nextLevel ?? 1) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="group_status">Status</label>
                                <select class="form-control" name="group_status" id="group_status" required>
                                    <option value="1" {{ old('group_status') == '1' ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ old('group_status') == '0' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>

                            <div class="form-group clearfix">
                                <button type="submit" class="btn btn-primary"
                                    style="background-color:#FFa500; border-color:#FFa500;">
                                    Add Group
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection