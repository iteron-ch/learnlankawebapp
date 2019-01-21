@extends('admin.rewards.rewardsform')
@section('form')
    {!! Form::open(['route' => ['rewards.store',strtolower($task_type)], 'method' => 'post', 'files' => true, 'class' => 'form-horizontal','id' =>'rewardsfrm']) !!}
@stop