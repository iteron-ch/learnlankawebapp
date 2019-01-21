@extends('admin.group.groupform')

@section('form')
    {!! Form::model($group, ['route' => ['managegroup.update', encryptParam($group['id'])], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal','id' =>'groupfrm']) !!}
@stop