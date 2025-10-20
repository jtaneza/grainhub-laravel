@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Photos</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="file" name="file_upload[]" multiple required class="form-control">
            <button type="submit" class="btn btn-primary">Upload</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Photo</th>
                <th>File Name</th>
                <th>Type</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($media_files as $key => $media)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>
                        <img src="{{ asset('uploads/products/' . $media->file_name) }}" width="80" class="img-thumbnail">
                    </td>
                    <td>{{ $media->file_name }}</td>
                    <td>{{ $media->file_type }}</td>
                    <td>
                        <form action="{{ route('media.destroy', $media->id) }}" method="POST" onsubmit="return confirm('Delete this photo?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
