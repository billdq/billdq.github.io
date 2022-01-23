@extends('adminlte::page')

@section('title', 'Workflow')

@section('content_header')
<h1>QR Codes</h1>
@stop

@section('content')
<button id="print_all" onclick="printAll()" type="button" class="btn btn-outline-primary mb-1">Print All</button>
<button id="add_to_recycle" onclick="addToRecycle()" type="button" class="btn btn-outline-primary mb-1">Add to
    Recycle</button>
<button id="batch_delete" onclick="batchDelete()" type="button" class="btn btn-outline-primary mb-1">Batch Delete</button>
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
            <td>{{ $orderCat->catLabel }}</td>
            <td>{{ $orderCat->orderNo }}</td>
            <td>{{ $allStatus[$qr->status] }}</td>
            <td>{{ $qr->update_by }}</td>
            <td>{{ $qr->update_time }}</td>
            <td><button type="button" class="btn btn-warning btn-xs" onclick="view('{{ $qr->value }}')"><span
                        class="fa fa-eye" /></button>
                <button type="button" class="btn btn-warning btn-xs" onclick="del('{{ $qr->value }}')"><span
                        class="fas fa-trash" /></button>
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
<!-- Modal -->
<div class="modal fade" id="modal2" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Add to Recycle</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="recycle_list">Add to:</label>
                    <select id="recycle_list" class="form-control">
                        <option value="-1">Create New...</option>
                        @foreach ($orders as $order)
                        <option value="{{ $order->id }}">{{ $order->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="recycle_title">Recycle Order Title</label>
                    <input type="text" class="form-control" id="recycle_title" placeholder="">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="add_btn" class="btn btn-primary">Add</button>
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
const RECYCLE_STATUS = "{{ $recycleStatus }}";
var table = null;

$(document).ready(function() {
    table = $('#table1').DataTable({
        searching: true,
    });

    $('#table1 tbody').on('click', 'tr', function() {
        $(this).toggleClass('selected');
    });

    $('#recycle_list').change(function() {
        var recycleId = $("#recycle_list").val();

        if (recycleId == -1) {
            $("#recycle_title").closest('div').show();
        } else {
            $("#recycle_title").closest('div').hide();
        }
    });

    $('#add_btn').click(function() {
        var recycleId = $("#recycle_list").val();
        if (recycleId == -1) {
            if (!validate("#recycle_title", "Recycle Order Title")) {
                return;
            }
        }

        addSelected();
    });
});

function printAll() {
    window.open("qr_codes/print", '_blank');
}

function addToRecycle() {
    if (!validateSelected()) {
        return;
    }

    $("#modal2").modal();
}

function batchDelete() {
    var qrCodes = getSelected();
    if (qrCodes.length > 0) {
        submitDelete(qrCodes);
    } else {
        alert("No QR Code is selected!");
    }
}

function view(code) {
    qrcode.clear();
    qrcode.makeCode(code);
    $("#modal1").modal();
}

function del(code) {
    submitDelete([code]);
}

function submitDelete(qrCodes) {
    $.ajax({
        url: "{{ url('order_cat', $orderCat->id).'/qr_codes' }}",
        type: 'DELETE',
        data: {
            qr_codes: qrCodes,
            _token: '{{csrf_token()}}',
        },
        success: function(result) {
            alert('Delete success!');
            location.href = "{{ url('order_cat', $orderCat->id).'/qr_codes' }}";
        }
    });
}

function validateSelected() {
    var a = table.rows('.selected');

    var selectedRecycle = 0;
    a.every(function() {
        var data = this.data();
        if (data[3] == RECYCLE_STATUS) {
            selectedRecycle++;
        }
    });

    if (selectedRecycle == 0 || selectedRecycle < a.data().length) {
        alert("Please select 可回收的物件 only");
        return false;
    }

    return true;
}

function addSelected() {
    var recycleId = $("#recycle_list").val();
    var title = $("#recycle_title").val();

    if (recycleId > -1) {
        title = $("#recycle_list option:selected").text();
    }

    var qrCodes = getSelected();
    $.post("/recycle_orders", {
            id: recycleId,
            title: title,
            qr_codes: qrCodes,
            _token: '{{csrf_token()}}'
        },
        function(data, status) {
            if (status == "success") {
                var orderId = data['order_id'];
                location.href = '/recycle_orders/' + orderId;
            } else {
                alert("Failed to process, please contact system admin");
            }
        });
}

function getSelected() {
    var a = table.rows('.selected');
    var qrCodes = [];
    a.every(function() {
        qrCodes.push(this.data()[0]);
    });
    return qrCodes;
}
</script>
@stop