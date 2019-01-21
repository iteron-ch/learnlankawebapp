@extends('admin.cmspage.cmspageform')

@section('form')
    {!! Form::model($cmsPage, ['route' => ['cmspage.update', $cmsPage['id']], 'method' => 'put', 'class' => 'form-horizontal','id' =>'cmspagefrm']) !!}
@stop

