@extends('admin.notification.notificationform')
@section('form')
    {!! Form::model($notification_list, ['route' => ['notification.update', encryptParam($notification_list['id'])], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal','id' =>'notificationfrm']) !!}
@stop