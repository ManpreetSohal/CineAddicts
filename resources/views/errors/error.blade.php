@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Error</h1>
    <h3>An error occurred while registering. Please try again later.</h3>
    <a href="{{route('movies')}}">Back to home</a>
</div>
@endsection
