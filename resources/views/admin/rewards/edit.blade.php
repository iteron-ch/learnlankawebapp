@extends('admin.rewards.rewardsform')

@section('form')
    {!! Form::model($rewardsData, ['route' => ['rewards.update', strtolower($task_type).'/'.encryptParam($rewardsData['id'])], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal','id' =>'rewardsfrm']) !!}
@stop