@extends('auth._default')
@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
   @include('auth/login_form')
</div>
@stop
@section('scripts')
{!! JsValidator::formRequest('App\Http\Requests\Auth\LoginRequest', '#frmLogin'); !!}   
@stop