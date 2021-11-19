@extends('adminlte::page')

@section('title', 'Workflow')

@section('content_header')
<h1>{{ isset($cat) ? 'Category' : 'Create Category' }}</h1>
@stop

@section('content')
<form action="{{ isset($cat) ? url('cats').'/'.$cat->key : url('cats') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="key">Key</label>
        <input type="text" name="key" class="form-control col-md-8" id="key" aria-describedby="keyHelp" placeholder="">
        <small id="keyHelp" class="form-text text-muted">Unique key for a category; If you want to change key, please create a new one</small>
    </div>

    <div class="form-group">
        <label for="value">Desc.</label>
        <input type="text" name="value" class="form-control col-md-8" id="value" placeholder="">
    </div>

    <div class="col-md-8">
        <button class="btn btn-primary float-right">Submit</button>
    </div><br>
</form>
@stop

@section('css')
@stop

@section('js')
<script src="/js/common.js"></script>

<script>
$(document).ready(function() {

    $("form").submit(function() {
        try {
            if (!validate("#key", "Key")) {
                return false;
            }
            if (!validate("#value", "Desc.")) {
                return false;
            }
        } catch (err) {
            console.error(err);
            return false;
        }
    });

    @if(isset($cat))
    fillCat();
    @endif
});

@if(isset($cat))

function fillCat() {
    $("#key").val("{{ $cat->key }}");
    $("#value").val("{{ $cat->value }}");

    $("#key").attr("disabled", "disabled");
}
@endif
</script>
@stop