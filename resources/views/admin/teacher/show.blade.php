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
                             <strong> {{ trans('admin/teacher.school_name') }}</strong>
                        </td>
                        <td>
                            {{ $user->school_name }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                             <strong> {{ trans('admin/admin.first_name') }}</strong>
                        </td>
                        <td>
                            {{ $user->first_name }}
                        </td>
                    </tr>                    
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/admin.last_name') }}</strong>
                        </td>
                        <td>
                            {{ $user->last_name }}
                        </td>
                    </tr>                    
                    <tr>
                        <td>
                           <strong>   {{ trans('admin/teacher.gender') }}</strong>
                        </td>
                        <td>
                            {{ $user->gender }}
                        </td>
                    </tr>                      
                    
                    @if(!empty($user->image))
                    <tr>
                        <td>
                           <strong>   {{ trans('admin/admin.profile_photo') }}</strong>
                        </td>
                        <td>
                            {!! HTML::image(route('userimg', ['file' => $user->image, 'size' => 'small'])) !!}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td>
                           <strong>   {{ trans('admin/admin.username') }}</strong>
                        </td>
                        <td>
                            {{ $user->username }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/admin.email') }}</strong>
                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/admin.telephone_no') }}</strong>
                        </td>
                        <td>
                            {{ $user->telephone_no }}
                        </td>
                    </tr>
                   
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/admin.address') }}</strong>
                        </td>
                        <td>
                            {{ $user->address }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/admin.city') }}</strong>
                        </td>
                        <td>
                            {{ $user->city }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/admin.postal_code') }}</strong>
                        </td>
                        <td>
                            {{ $user->postal_code }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/admin.county') }}</strong>
                        </td>
                        <td>
                            {{ $user->county }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/admin.country') }}</strong>
                        </td>
                        <td>
                            {{ $user->country }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                             <strong> {{ trans('admin/admin.status') }}</strong>
                        </td>
                        <td> 
                            {{ $status[$user->status] }}
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