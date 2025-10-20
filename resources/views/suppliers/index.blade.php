@extends('layouts.app')
@section('content')
<h1>Suppliers</h1>
<a href="{{ route('suppliers.create') }}">Create</a>
<ul>@foreach($items as $it)<li>{{ $it->name }} — {{ $it->contact }}</li>@endforeach</ul>
@endsection
