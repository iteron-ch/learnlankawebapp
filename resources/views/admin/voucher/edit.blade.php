@extends('admin.voucher.voucherform')
@section('form')
    {!! Form::model($voucher, ['route' => ['voucher.update', encryptParam($voucher['id'])], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal','id' =>'voucherfrm']) !!}
@stop