@extends('adminlte::page')

@section('title', 'Workflow')

@section('content_header')
<h1>Recycle Order</h1>
@stop

@section('content')
<form action="{{ url('recycle_orders').'/'.$order->id  }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="title">Order Title</label>
        <input type="text" name="title" class="form-control col-md-8" id="title" placeholder="">
    </div>

    <div class="form-group">
        <label for="weight">Weight</label>
        <input type="text" name="weight" class="form-control col-md-8" id="weight" placeholder="">
    </div>

    <div class="form-group">
        <div class="col-md-8">
            <button type="button" class="btn btn-warning btn-xs float-right" onclick="viewQrCodes()">
                <span class="fa fa-eye" />
            </button>
        </div>
        <label for="amount">Amount</label>
        <input type="text" name="amount" class="form-control col-md-8" id="amount" placeholder="">
    </div>

    <div class="form-group">
        <label for="date">Date</label>
        <input type="text" name="date" class="form-control col-md-8" id="date" aria-describedby="dateHelp"
            placeholder="">
        <small id="dateHelp" class="form-text text-muted">請輸入YYYY-MM-DD</small>
    </div>

    <div class="form-group">
        <label for="category">Category</label>
        <input type="text" name="category" class="form-control col-md-8" id="category" placeholder="">
    </div>

    <div class="form-group">
        <label for="status">Status</label>
        <input type="text" name="status" class="form-control col-md-8" id="status" placeholder="">
    </div>

    <div class="col-md-8">
        <button id="qr_code_btn" type="button" class="btn btn-success float-right ml-3">QR Code</button>
        <button class="btn btn-primary float-right">Submit</button>
    </div><br>
</form>
@stop

@section('css')
@stop

@section('js')
<script src="/js/common.js"></script>
<script src="/js/qrcode.js"></script>
<script src="/js/moment.js"></script>

<script>
$(document).ready(function() {

    $("form").submit(function() {
        try {
            if (!validate("#title", "Order Title")) {
                return false;
            }
            if (!validate("#weight", "Weight")) {
                return false;
            }
            if (!validate("#date", "Date")) {
                return false;
            }
            if (!validateWeight()) {
                return false;
            }
            if (!validateDate()) {
                return false;
            }
        } catch (err) {
            console.error(err);
            return false;
        }
    });

    $("#qr_code_btn").click(function() {
        printQrCode();
    });

    @if(isset($order))
    fillOrder();
    @endif
    $("#category").attr("disabled", "disabled");
    $("#status").attr("disabled", "disabled");
});

@if(isset($order))
function fillOrder() {
    $("#title").val("{{ $order->title }}");
    $("#weight").val("{{ $order->weight }}");
    $("#amount").val("{{ $order->amount }}");
    $("#date").val("{{ $order->date->format('Y-m-d') }}");
    $("#category").val("{{ $order->catLabel }}");
    $("#status").val("{{ $order->status }}");

    $("#amount").attr("disabled", "disabled");
}
@endif

function viewQrCodes() {
    location.href = "{{ url('recycle_orders', $order->id).'/qr_codes' }}"
}

function validateWeight() {
    let result = !isNaN($("#weight").val());
    if (!result) {
        alert("Invalid Weight, please input again");
    }
    return result;
}

function validateDate() {
    let result = moment($("#date").val(), 'YYYY-MM-DD', true).isValid();
    if (!result) {
        alert("Invalid Date, please input again");
    }
    return result;
}

function printQrCode() {
    window.open("{{$order->id.'/print_qr_code'}}", '_blank');
}
</script>
@stop