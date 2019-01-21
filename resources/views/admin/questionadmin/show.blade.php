@extends('admin.layout._iframe')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title tabbable-line">
                <div class="caption caption-md">
                    <i class="icon-globe theme-font hide"></i>
                    <span class="caption-subject font-grey-madison bold">{{ trans('admin/admin.profile_detail') }}</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-light table-hover">
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/questionadmin.first_name') }}</strong>
                        </td>
                        <td>
                            {{ $questionAdminRepo->first_name }}
                        </td>
                    </tr>                    
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/questionadmin.last_name') }}</strong>
                        </td>
                        <td>
                            {{ $questionAdminRepo->last_name }}
                        </td>
                    </tr>                    


                    @if(!empty($questionAdminRepo->image))
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.profile_photo') }}</strong>
                        </td>
                        <td>
                            {!! HTML::image(route('userimg', ['file' => $questionAdminRepo->image, 'size' => 'small'])) !!}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td>
                            <strong> {{ trans('admin/questionadmin.username') }}</strong>
                        </td>
                        <td>
                            {{ $questionAdminRepo->username }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/questionadmin.email') }}</strong>
                        </td>
                        <td>
                            {{ $questionAdminRepo->email }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.status') }}</strong>
                        </td>
                        <td>
                            {{ $questionAdminRepo->status }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/questionadmin.created_at') }}</strong>
                        </td>
                        <td>
                            {{ outputDateFormat($questionAdminRepo->created_at) }}
                        </td>
                    </tr> 

                </table>
                <!--end profile-settings-->
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop