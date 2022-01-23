@extends('adminlte::page')

@section('title', 'Workflow')

@section('content_header')
<h1>QR Codes</h1>
@stop

@section('content')
<table id="table1" class="display" style="width:100%">
    <thead>
        <tr>
            <th>Code ID</th>
            <th>Category</th>
            <th>Order No</th>
            <th>Status</th>
            <th>Update By</th>
            <th>Update Time</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($qrCodes as $qr)
        <tr>
            <td>{{ $qr->value }}</td>
            <td>{{ $qr->catLabel }}</td>
            <td>{{ $qr->orderNo }}</td>
            <td>{{ $allStatus[$qr->status] }}</td>
            <td>{{ $qr->update_by }}</td>
            <td>{{ $qr->update_time }}</td>
            <td><button type="button" class="btn btn-warning btn-xs" onclick="view('{{ $qr->value }}')"><span
                        class="fa fa-eye" /></button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
<!-- Modal -->
<div class="modal fade" id="modal1" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Preview</h4>
            </div>
            <div class="modal-body">
                <div id="qrcode"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<link rel="stylesheet" href="/css/jquery.dataTables.min.css">
<!--<link rel="stylesheet" href="/css/buttons.dataTables.min.css">-->
@stop

@section('js')
<script src="/js/common.js"></script>
<script src="/js/jquery.dataTables.min.js"></script>
<!--<script src="/js/dataTables.buttons.min.js"></script>-->
<script src="/js/qrcode.js"></script>
<script>
var qrcode = new QRCode(document.getElementById("qrcode"), "Dummy");
var table = null;

$(document).ready(function() {
    table = $('#table1').DataTable({
        searching: true,
    });

    //$('#table1 tbody').on('click', 'tr', function() {
    //    $(this).toggleClass('selected');
    //});

});

function view(code) {
    qrcode.clear();
    qrcode.makeCode(code);
    $("#modal1").modal();
}
</script>
@stop