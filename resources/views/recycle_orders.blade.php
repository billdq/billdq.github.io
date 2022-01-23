@extends('adminlte::page')

@section('title', 'Workflow')

@section('content_header')
<h1>Recycle Orders</h1>
@stop

@section('content')
<div class="form-group row col-md-8">
    <div class="form-inline col-md-4">
        <input type="text" name="from_date" class="form-control" id="from_date" aria-describedby="fromDateHelp"
                placeholder="From date">
        <small id="fromDateHelp" class="form-text text-muted">請輸入YYYY-MM-DD</small>
    </div>
    <div class="form-inline col-md-4">
        <input type="text" name="to_date" class="form-control" id="to_date" aria-describedby="toDateHelp"
                placeholder="To date">
        <small id="toDateHelp" class="form-text text-muted">請輸入YYYY-MM-DD</small>
    </div>
    <div class="form-inline col-md-2">
        <button id="search" onclick="search()" type="button" class="btn btn-outline-primary mb-1">Search</button>
    </div>
</div>
<table id="table1" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Title</th>
            <th>Weight</th>
            <th>Amount</th>
            <th>Date</th>
            <th>Category</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
        <tr>
            <td>{{ $order->title }}</td>
            <td>{{ $order->weight }}</td>
            <td>{{ $order->amount }}</td>
            <td>{{ $order->date->format('Y-m-d') }}</td>
            <td>{{ $order->catLabel }}</td>
            <td>{{ $order->status }}</td>
            <td>
                <button type="button" class="btn btn-warning btn-xs"
                    onclick="view(&quot;{{ url('recycle_orders', $order->id) }}&quot;)"><span
                        class="fa fa-eye" /></button>
                <button type="button" class="btn btn-warning btn-xs"
                    onclick="del(&quot;{{ url('recycle_orders', $order->id) }}&quot;)"><span
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
<script src="/js/moment.js"></script>
<script>
$(document).ready(function() {
    table = $('#table1').DataTable({
        searching: true,
    });

    $.fn.dataTable.ext.search.push(
    function(settings, data, dataIndex) {
        var fromStr = $('#from_date').val();
        var toStr = $('#to_date').val();
        var date = new Date(data[3]);
        date = new Date(date.toDateString());
        if (isValid(fromStr) && isValid(toStr)) {
            let from = getDate(fromStr, new Date(0));
            let to = getDate(toStr, new Date());
            return from <= date && date <= to;
        } else {
            alert("From/To date is invalid!");
            return false;
        }
    });
});

function search() {
    table.draw();
}

function getDate(dateStr, defDate) {
    if (dateStr.trim() === "") return defDate;
    return moment(dateStr, 'YYYY-MM-DD').toDate();
}

function isValid(dateStr) {
    if (dateStr.trim() === "") return true;
    return moment(dateStr, 'YYYY-MM-DD', true).isValid();
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
            location.href = '/recycle_orders';
        }
    });
}
</script>
@stop