@extends('admin.layout._iframe')

@section('content')

<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title tabbable-line">
                <div class="caption caption-md">
                    <i class="icon-globe theme-font hide"></i>
                    <span class="caption-subject font-grey-madison bold">{{ trans('admin/group.group_detail') }}</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-light table-hover">
                    <tr>
                        <td width=30%>
                            <strong>{{ trans('admin/group.group_name') }}</strong>
                        </td>
                        <td>
                            {{ $group->group_name }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/group.no_of_students') }}</strong>
                        </td>
                        <td>
                            {{ $group->studentsNum }}
                        </td>
                    </tr>
                    @if($group->studentsNum)
                    <tr>
                        <td>
                            <strong> {{ trans('admin/group.student_label') }}</strong>
                        </td>
                        <td>
                            @foreach($group->students as $student)
                            {!! trim($student['first_name'].' '.$student['last_name']).'<br>' !!}
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td>
                            <strong> {{ trans('admin/group.status') }}</strong>
                        </td>
                        <td>
                            {{ $group->status }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/group.created_at') }}</strong>
                        </td>
                        <td>

                            {{  outputDateFormat($group->created_at) }}
                        </td>
                    </tr>
                    
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/group.updated_at') }}</strong>
                        </td>
                        <td>

                            {{ outputDateFormat($group->updated_at) }}
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