@extends('adminlte::page')

@section('title', 'Workflow')

@section('content_header')
<h1>Categories</h1>
@stop

@section('content')
<button id="create_cat" onclick="createCat()" type="button" class="btn btn-outline-primary mb-1">Create Category</button>

<table id="table1" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Key</th>
            <th>Desc.</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($cats as $cat)
        <tr>
            <td>{{ $cat->key }}</td>
            <td>{{ $cat->value }}</td>
            <td>
                <button type="button" class="btn btn-warning btn-xs"
                    onclick="view(&quot;{{ url('cats', $cat->key) }}&quot;)"><span
                        class="fa fa-eye" /></button>
                <button type="button" class="btn btn-warning btn-xs"
                    onclick="del(&quot;{{ url('cats', $cat->key) }}&quot;)"><span
                        class="fas fa-trash" /></button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@stop

@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<link rel="stylesheet" href="/css/jquery.dataTables.min.css">
@stop

@section('js')
<script src="/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    var table = $('#table1').DataTable({
        searching: true,
    });

});

function createCat() {
    location.href = '/create_cat';
}

function view(url) {
    location.href = url;
}

function del(url) {
    $.ajax({
        url: url,
        type: 'DELETE',
        data: {
            _token: '{{csrf_token()}}',
        },
        success: function(result) {
            console.log('Delete response: ' + result);
            alert('Delete success!');
            location.href = '/cats';
        }
    });
}
</script>
@stop