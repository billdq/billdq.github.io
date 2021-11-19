@extends('adminlte::page')

@section('title', 'Workflow')

@section('content_header')
<h1>Users</h1>
@stop

@section('content')
<button id="create_user" onclick="createUser()" type="button" class="btn btn-outline-primary mb-1">Create User</button>

<table id="table1" class="display" style="width:100%">
    <thead>
        <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Admin?</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
        <tr>
            <td>{{ $user->uid }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->is_admin ? "Yes" : "No" }}</td>
            <td>
                <button type="button" class="btn btn-warning btn-xs"
                    onclick="view(&quot;{{ url('users', $user->id) }}&quot;)"><span
                        class="fa fa-eye" /></button>
                <button type="button" class="btn btn-warning btn-xs"
                    onclick="del(&quot;{{ url('users', $user->id) }}&quot;)"><span
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
        searching: false,
    });

});

function createUser() {
    location.href = '/create_user';
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
            location.href = '/users';
        }
    });
}
</script>
@stop