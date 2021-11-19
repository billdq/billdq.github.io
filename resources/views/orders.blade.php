@extends('adminlte::page')

@section('title', 'Workflow')

@section('content_header')
<h1>Orders</h1>
@stop

@section('content')
<table id="table1" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Order No</th>
            <th>Remarks</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td>{{ $order->order_no }}</td>
            <td>{{ $order->remarks }}</td>
            <td>
                <button type="button" class="btn btn-warning btn-xs"
                    onclick="view(&quot;{{ url('orders', $order->id) }}&quot;)"><span class="fa fa-eye" /></button>
                <button type="button" class="btn btn-warning btn-xs"
                    onclick="del(&quot;{{ url('orders', $order->id) }}&quot;)"><span class="fas fa-trash" /></button>
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
            alert(result);
        }
    });
}
</script>
@stop