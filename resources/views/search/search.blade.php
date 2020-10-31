@extends('layouts.app')

@section('content')
<div class="container">
    <searchresults-component search_str="{{$search_str}}" v-bind:data='@json($data_array)' v-bind:movies_lists_associations='@json($movies_lists_associations)'></searchresults-component>
</div>
@endsection
