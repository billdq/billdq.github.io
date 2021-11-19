@extends('adminlte::page')

@section('title', 'Workflow')

@section('content_header')
<h1>{{ isset($user) ? 'User' : 'Create User' }}</h1>
@stop

@section('content')
<form action="{{ isset($user) ? url('users').'/'.$user->id : url('users') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="uid">User ID</label>
        <input type="text" name="uid" class="form-control col-md-8" id="uid" aria-describedby="uidHelp" placeholder="">
        <small id="uidHelp" class="form-text text-muted">If left empty, UID is set to be same as email</small>
    </div>

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" name="name" class="form-control col-md-8" id="name" placeholder="">
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="text" name="email" class="form-control col-md-8" id="email" placeholder="">
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="text" name="password" class="form-control col-md-8" id="password" aria-describedby="pwdHelp"
            placeholder="">
        <small id="pwdHelp" class="form-text text-muted">Password is not shown, input a new password if want to
            reset</small>
    </div>

    <div class="form-group">
        <label for="is_admin">Admin?</label>
        <select name="is_admin" class="form-control col-md-8" id="is_admin">
            <option value="N">No</option>
            <option value="Y">Yes</option>
        </select>
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
            if (!validate("#name", "Name")) {
                return false;
            }
            if (!validate("#email", "Email")) {
                return false;
            }
            if (!validatePassword()) {
                return false;
            }
            if (!validateEmail()) {
                return false;
            }
            fillUid();
        } catch (err) {
            console.error(err);
            return false;
        }
    });

    @if(isset($user))
    fillUser();
    @endif
});

@if(isset($user))

function fillUser() {
    $("#uid").val("{{ $user->uid }}");
    $("#name").val("{{ $user->name }}");
    $("#email").val("{{ $user->email }}");
    $("#is_admin").val("{{ $user->is_admin ? 'Y' : 'N' }}").change();

    $("#email").attr("disabled", "disabled");
}
@endif

function validateEmail() {
    const re =
        /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    if (!re.test($("#email").val())) {
        alert("Invalid Email, please input again");
    }
    return re.test($("#email").val());
}

function validatePassword() {
    @if(isset($user))
    return true;
    @else
    return validate($("#password"), "Password");
    @endif
}

function fillUid() {
    let uid = $("#uid").val();
    if (uid == null || uid == "") {
        $("#uid").val($("#email").val());
    }
}
</script>
@stop