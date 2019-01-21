@extends('admin.layout._iframe')

@section('content')


<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title tabbable-line">
                <div class="caption caption-md">
                    <i class="icon-globe theme-font hide"></i>
                    <span class="caption-subject font-grey-madison bold">{{ trans('front/enquiry.enquiry_details') }}</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-light table-hover">
                    <tr>
                        <td width=30%>
                            <strong>{{ trans('front/enquiry.title') }}</strong>
                        </td>
                        <td>
                            {{ $enquiryRepo['title'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.first_name') }}</strong>
                        </td>
                        <td>
                            {{ $enquiryRepo['first_name'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.last_name') }}</strong>
                        </td>
                        <td>
                            {{ $enquiryRepo['last_name'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.created_at') }}</strong>
                        </td>
                        <td>
                            {{ $enquiryRepo['created_at'] ? outputDateFormat($enquiryRepo['created_at']) : '' }}
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.email') }}</strong>
                        </td>
                        <td>
                            {{ $enquiryRepo['email'] }}
                        </td>
                    </tr>
                    @if($enquiryRepo['user_type'] == "Parent")
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.county') }}</strong>
                        </td>
                        <td>
                            {{ $enquiryRepo['county'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.city') }}</strong>
                        </td>
                        <td>
                            {{ $enquiryRepo['city'] }}
                        </td>
                    </tr>
                    @endif

                    @if($enquiryRepo['user_type'] == "Teacher")
                    <tr>
                        <td>
                            <strong> {{ trans('front/enquiry.school') }}</strong>
                        </td>
                        <td>
                            {{ $enquiryRepo['school'] }}
                        </td>
                    </tr>
                    <tr> 
                        <td>
                            <strong> {{ trans('admin/admin.city') }}</strong>
                        </td>
                        <td>
                            {{ $enquiryRepo['city'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.postal_code') }}</strong>
                        </td>
                        <td>
                            {{ $enquiryRepo['postal_code'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('front/front.job_role') }}</strong>
                        </td>
                        <td>
                            {{ $enquiryRepo['job_role'] }}
                        </td>
                    </tr>
                    @endif

                </table>
                
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop 