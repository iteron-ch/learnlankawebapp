@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => 'Pending Payments', 'trait_1' => 'Pending Payments'])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>Pending Payments
                </div>

            </div>

            <div class="portlet-body">
                {!! Form::open(array('url' => 'tutor','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">   
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('first_name',trans('admin/tutor.tutor_name'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('first_name',null,['id'=>'first_name','placeholder'=>trans('admin/tutor.search_tutor_name'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('username',trans('admin/tutor.username'),['class'=>'control-label col-md-3'], FALSE )  !!}
                                <div class="col-md-6">
                                    {!! Form::text('username',null,['id'=>'username','placeholder'=>trans('admin/tutor.search_username'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('email',trans('admin/tutor.email'),['class'=>'control-label col-md-5'], FALSE )  !!}
                                <div class="col-md-7">
                                    {!! Form::text('email',null,['id'=>'email','placeholder'=>trans('admin/tutor.search_email'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row"> 
                        <div class="col-md-4"><label class="control-label col-md-3" for="first_name">&nbsp;</label>
                            <div class="form-group">
                                    <div class="col-md-7">
                                        {!! Form::button(trans('admin/admin.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                        {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset','class' => 'btn btn-primary grey')) !!} 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>  
                <hr style="border-top: 1px solid #26a69a;" >
            </div>
            {!! Form::close() !!}
            <table id="users-table" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr class="heading">
                        <th>User Type</th>
                        <th>{!! trans('admin/tutor.tutor_name') !!}</th>
                        <th>{!! trans('admin/tutor.email') !!}</th>
                        <th>{!! trans('admin/tutor.username') !!}</th>
                        <th>{!! trans('admin/tutor.date_of_registration') !!}</th>
                        <th>Payment Status</th>

                        <th>{!! trans('admin/tutor.actions') !!}</th>
                    </tr>
                </thead>
            </table>

        </div>

    </div>
</div>

<!-- END EXAMPLE TABLE PORTLET-->
</div>
<!-- END PAGE CONTENT-->
@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- END PAGE LEVEL SCRIPTS -->
<script>
    var vars = {
        dataTable: "#users-table",
        listUrl: "{{ route('payment.listrecord') }}",
        addUrl: "{{ route('tutor.create') }}",
        deleteUrl: "{{ route('tutor.delete') }}",
        confirmMsg: "{!! trans('admin/admin.delete-confirm') !!}",
        successMsg: "{!! trans('admin/admin.delete-success') !!}"
    };
    $(document).ready(function () {
        $("#status").select2();
    });
</script>
{!! HTML::script('js/userpayment.js') !!}

@stop