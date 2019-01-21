@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('front/enquiry.manage_enquiry'), 'trait_1' => trans('front/enquiry.enquiry')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>{!! trans('front/enquiry.enquiry') !!}
                </div>
            </div>
            <div class="portlet-body">
                {!! Form::open(array('url' => 'enquiry','class'=>'form-horizontal','id' =>'search-form' )) !!}
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('first_name',trans('admin/admin.first_name'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('first_name',null,['id' => 'first_name','placeholder'=>trans('admin/admin.first_name'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('last_name',trans('admin/admin.last_name'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('last_name',null,['id' => 'last_name','placeholder'=>trans('admin/admin.last_name'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('email',trans('admin/admin.email'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('email',null,['id' => 'email','placeholder'=>trans('admin/admin.email'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('user_type',trans('admin/admin.user_type'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::select('user_type', $userType, null ,['id' => 'user_type', 'class' => 'form-control']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('job_role',trans('front/front.job_role'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">
                                    {!! Form::text('job_role',null,['id' => 'job_role','placeholder'=>trans('front/front.job_role'),'class'=>'form-control'])  !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                {!! Form::labelControl('how_hear',trans('front/front.how_hear'),['class'=>'control-label col-md-4'], FALSE )  !!}
                                <div class="col-md-8">  
                                    {!! Form::select('how_hear', $how_hear, null ,['id' => 'how_hear', 'class' => 'form-control select2me']) !!} 
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label class="control-label col-md-4">&nbsp;</label>
                                <div class="col-md-8">
                                    {!! Form::button(trans('admin/admin.search'), array('type' => 'submit', 'class' => 'btn btn-primary')) !!} 
                                    {!! Form::button(trans('admin/admin.reset'), array('type' => 'reset', 'id'=>'reset','class' => 'btn btn-primary grey')) !!} 
                                </div>
                            </div>
                        </div>

                    </div>
                    <hr style="border-top: 1px solid #26a69a;" >
                </div>
                {!! Form::close() !!}   
                <table id="enquiry-table" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr class="heading">
                            <th>{!! trans('front/enquiry.title') !!}</th>
                            <th>{!! trans('admin/admin.first_name') !!}</th>
                            <th>{!! trans('admin/admin.last_name') !!}</th>
                            <th>{!! trans('admin/admin.email') !!}</th>
                            <th>{!! trans('front/enquiry.contact_no') !!}</th>
                            <th>{!! trans('front/enquiry.user_type') !!}</th>
                            <th>{!! trans('front/front.how_hear') !!}</th>
                            <th>{!! trans('front/front.job_role') !!}</th>
                            <th>{!! trans('admin/admin.created_at') !!}</th>
                            <th>{!! trans('admin/admin.action') !!}</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- END EXAMPLE TABLE PORTLET-->
    </div>
    <!-- END PAGE CONTENT-->
    @stop

    @section('pagescripts')
    <!-- BEGIN PAGE LEVEL PLUGINS -->
    <script>
        var vars = {
            dataTable: "#enquiry-table",
            listUrl: "{{ route('enquiry.listrecord') }}",
        };
        $(document).ready(function () {
            $("#status").select2();
            $("#user_type").select2();
        });
    </script>
    {!! HTML::script('js/enquiry.js') !!}
    @stop
