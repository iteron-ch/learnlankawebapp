@extends('admin.helpcentre.helpcentreform')

@section('form')
    {!! Form::model($helpcentre, ['route' => ['helpcentre.update', encryptParam($helpcentre['id'])], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal','id' =>'helpcentrefrm']) !!}
@stop