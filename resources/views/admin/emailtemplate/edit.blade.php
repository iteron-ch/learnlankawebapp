@extends('admin.emailtemplate.emailtemplateform')

@section('form')
    {!! Form::model($emailTemplate, ['route' => ['emailtemplate.update', $emailTemplate['id']], 'method' => 'put', 'class' => 'form-horizontal','id' =>'emailtemplatefrm']) !!}
@stop