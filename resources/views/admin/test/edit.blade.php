@extends('admin.test.form')

@section('form')
    {!! Form::model($test, ['route' => ['managetest.update', encryptParam($test['id'])], 'files' => true, 'method' => 'put', 'class' => 'form-horizontal panel','id' =>'taskfrm']) !!}
@stop