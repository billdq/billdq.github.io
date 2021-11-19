@extends('adminlte::page')

@section('title', 'Workflow')

@section('content_header')
<h1>{{ isset($order) ? 'Order' : 'Create Order' }}</h1>
@stop

@section('content')
<form action="{{ isset($order) ? url('orders').'/'.$order->id : url('orders') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="order_no">Order No</label>
        <input type="text" name="order_no" class="form-control col-md-8" id="order_no" aria-describedby="orderNoHelp"
            placeholder="">
        <small id="orderNoHelp" class="form-text text-muted">請輸入回收單編號 + 第一個電話的最後4位數字</small>
    </div>

    <div id="add_cat_div" class="col-md-8">
        <button id="add_cat" type="button" class="btn btn-info float-right ml-1"><span
                class="fa fa-plus-square" /></button>
        <button id="reset_cat" type="button" class="btn btn-info float-right">Reset</button>
    </div><br>

    <div class="form-group">
        <label for="remarks">Remarks</label>
        <input type="text" name="remarks" class="form-control col-md-8" id="remarks" placeholder="">
    </div>

    <div class="col-md-8">
        <button class="btn btn-primary float-right">Submit</button>
    </div><br>

    <input type="hidden" name="no_of_cat" id="no_of_cat" value="1" />
    <input type="hidden" id="no_of_cat_from_query" value="1" />
</form>
@stop

@section('css')
@stop

@section('js')
<script src="/js/common.js"></script>

<script>
var originalNoOfQr = [];

function validateCategories() {
    var noOfCat = $(".border").length;
    var listOfCat = [];
    for (let i = 1; i <= noOfCat; i++) {
        var cat = $("#category" + i).val();
        var noQr = $("#no_of_qr_code" + i).val();
        if (cat == null || cat == "") {
            alert("Please input Category for Category " + i);
            return false;
        } else if (noQr == null || noQr == "") {
            alert("Please input No. of QR Code for Category " + i);
            return false;
        } else if (isNaN(noQr) || parseInt(noQr) < 1) {
            alert("No. of QR Code is invalid for Category " + i);
            return false;
        } else if (parseInt(noQr) < originalNoOfQr[i-1]) {
            alert("You cannot reduce No. of QR Code for Category " + i);
            return false;
        }
        if (listOfCat.includes(cat)) {
            alert("Duplicated Categories, please double check!");
            return false;
        }
        listOfCat.push(cat);
    }
    return true;
}

function enableCategories() {
    var noOfCat = $(".border").length;
    for (let i = 1; i <= noOfCat; i++) {
        $("#category" + i).removeAttr('disabled');
    }
}

$(document).ready(function() {
    $("#add_cat").click(function() {
        addCat();
    });

    $("#reset_cat").click(function() {
        resetCat();
    });

    $("form").submit(function() {
        try {
            if (!validate("#order_no", "Order No")) {
                return false;
            }
            if (!validateCategories()) {
                return false;
            }
            if (!validate("#remarks", "Remarks")) {
                return false;
            }
            $("#no_of_cat").val($(".border").length);

            enableCategories();
        } catch (err) {
            console.error(err);
            return false;
        }
    });

    @if(isset($order))
    fillOrder();
    @else
    addCat();
    @endif
});

function addCat(catId = null, catVal = null, noOfQr = null) {
    var index = $(".border").length + 1;
    var string = '';
    string += '<div class="border border-light bg-info col-md-8">';
    string += '<div class="form-group">';
    string += '<label for="category' + index + '">Category ' + index + '</label>';
    string += '<select name="category' + index + '" id="category' + index + '" class="form-control">';
    string += $("#cat_options").html();
    string += '</select>';
    string += '</div>';
    string += '<div class="form-group">';
    if (catVal) {
        string += '<button type="button" class="btn btn-warning btn-xs float-right" onclick="viewQrCodes(&quot;' +
            catId + '&quot;)"><span class="fa fa-eye"/></button>';
    }
    string += '<label for="no_of_qr_code' + index + '">No. of QR Code</label>';
    string += '<input type="number" name="no_of_qr_code' + index + '" class="form-control" id="no_of_qr_code' + index +
        '" placeholder="">'
    string += '</div>';
    $("#add_cat_div").before(string);
    if (catVal) $("#category" + index).val(catVal);
    if (catVal) $("#category" + index).attr("disabled", "disabled");
    if (noOfQr) $("#no_of_qr_code" + index).val(noOfQr);
}

function resetCat() {
    var noOfCatQ = $("#no_of_cat_from_query").val();
    var noOfCat = $(".border").length;
    for (let i = 1; i <= noOfCat; i++) {
        if (i > noOfCatQ) {
            $("#category" + i).closest(".border").remove();
        } else {
            $("#no_of_qr_code" + i).val(originalNoOfQr[i-1]);
        }
    }
}

@if(isset($order))

function fillOrder() {
    $("#order_no").val("{{ $order->order_no }}");
    $("#remarks").val("{{ $order->remarks }}");
    @foreach($orderCats as $orderCat)
    addCat("{{$orderCat->id}}", "{{$orderCat->category}}", "{{$orderCat->no_of_qr_code}}");
    originalNoOfQr.push({{ $orderCat->no_of_qr_code }});
    @endforeach
    $("#no_of_cat_from_query").val("{{ count($orderCats) }}");
}
@endif

function viewQrCodes(catId) {
    location.href = '/order_cat/' + catId + '/qr_codes';
}
</script>

<script id="cat_options" type="text/html">
@foreach($cats as $cat)
<option value="{{ $cat->key }}">{{ $cat->value }}</option>
@endforeach
</script>
@stop