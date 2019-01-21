@extends('admin.layout._iframe')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title tabbable-line">
                <div class="caption caption-md">
                    <i class="icon-globe theme-font hide"></i>
                    <span class="caption-subject font-grey-madison bold">{{ trans('admin/notification.notification_detail') }}</span>
                </div>
            </div>

            <div class="portlet-body">
                <table class="table table-light table-hover">
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/notification.title') }}</strong>
                        </td>   
                        <td>
                            {{ $notification_record[0]['title'] }}
                        </td>
                    </tr> 
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/notification.user_type') }}</strong>
                        </td>   
                        <td>
                            <?php
                            $user_type = $notification_record[0]['user_type'];
                            $user_type_array = (explode(",", $user_type));
                            $userType = array();
                            $userTypeArray = userTypeArray();
                            foreach ($user_type_array as $value) {
                                if ($value == SCHOOL) {
                                    $userType[] = $userTypeArray[SCHOOL];
                                } if ($value == TEACHER) {
                                    $userType[] = $userTypeArray[TEACHER];
                                } if ($value == TUTOR) {
                                    $userType[] = $userTypeArray[TUTOR];
                                } if ($value == ADMIN) {
                                    $userType[] = $userTypeArray[ADMIN];
                                }
                            }
                            echo(implode(', ', $userType));
                            ?>
                        </td>
                    </tr> 
                     <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/notification.description') }}</strong>
                        </td>   
                        <td>
                            {{ $notification_record[0]['description'] }}
                        </td>
                    </tr> 
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/notification.created_at') }}</strong>
                        </td>   
                        <td>
                            {{ outputDateFormat($notification_record[0]['created_at']) }}

                        </td>
                    </tr> 
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/notification.status') }}</strong>
                        </td>   
                        <td>
                            {{ $notification_record[0]['status'] }}
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