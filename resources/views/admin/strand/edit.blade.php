@extends('admin.strand.strandform')

@section('form')
    {!! Form::model($strandResult, ['route' => ['strand.update', encryptParam($id)], 'files' => true, 'method' => 'put', 'class' => 'form-horizontal panel','id' =>'strandfrm']) !!}
@stop