@extends('admin.voucher.voucherform')

@section('form')
    {!! Form::open(['route' => ['voucher.store'], 'method' => 'post', 'files' => true, 'class' => 'form-horizontal','id' =>'voucherfrm']) !!}
@stop