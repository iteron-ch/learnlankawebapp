@extends('admin.strand.index')

@section('form')
    {!! Form::open(array('url' => 'student','files' => true,'class'=>'form-horizontal','id' =>'strandfrm' )) !!}
@stop