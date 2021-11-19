@extends('adminlte::page')

@section('title', 'Workflow')

@section('content_header')
    <h1>Home</h1>
@stop

@section('content')
    <a href="{{ url('create_order') }}" class="btn btn-link" role="button">Create Order</a>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script>
        $(document).ready(function() {
        });
    </script>
@stop