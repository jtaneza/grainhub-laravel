@extends('layouts.app')
@section('content')
<h1>Daily Report - {{ $date }}</h1>
<ul>@foreach($sales as $s)<li>{{ $s->date }} #{{ $s->id }} product: {{ $s->product_id }} qty: {{ $s->qty }} price: {{ $s->price }}</li>@endforeach</ul>
@endsection
