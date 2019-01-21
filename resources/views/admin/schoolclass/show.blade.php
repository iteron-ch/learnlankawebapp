@extends('admin.layout._iframe')

@section('content')

<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title tabbable-line">
                <div class="caption caption-md">
                    <i class="icon-globe theme-font hide"></i>
                    <span class="caption-subject font-grey-madison bold">{{ trans('admin/schoolclass.class_detail') }}</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-light table-hover">
                    <tr>
                        <td width=30%>
                            <strong>{{ trans('admin/schoolclass.class_label') }}</strong>
                        </td>
                        <td>
                            {{ $schoolClass->class_name }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/schoolclass.year') }}</strong>
                        </td>
                        <td> <?php
                            $year = $schoolClass->year;
                            switch ($year) {
                                case "1":
                                    echo "YG 1";
                                    break;
                                case "2":
                                    echo "YG 2";
                                    break;
                                case "3":
                                    echo "YG 3";
                                    break;
                                case "4":
                                    echo "YG 4";
                                    break;
                                case "5":
                                    echo "YG 5";
                                    break;
                                case "6":
                                    echo "YG 6";
                                    break;
                                case "7":
                                    echo "YG 7";
                                    break;
                                case "8":
                                    echo "YG 8";
                                    break;
                                case "9":
                                    echo "YG 9";
                                    break;
                                case "10":
                                    echo "YG 10";
                                    break;
                                case "11":
                                    echo "YG 11";
                                    break;
                            }
                            ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/schoolclass.no_of_student') }}</strong>
                        </td>
                        <td>
                            {{ $schoolClass->studentsNum }}
                        </td>
                    </tr>
                    @if($schoolClass->studentsNum)
                    <tr>
                        <td>
                            <strong> {{ trans('admin/schoolclass.student_label') }}</strong>
                        </td>
                        <td>
                            @foreach($schoolClass->students as $student)
                            {!! trim($student['first_name'].' '.$student['last_name']).'<br>' !!}
                            @endforeach
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td>
                            <strong> {{ trans('admin/schoolclass.status') }}</strong>
                        </td>
                        <td>
                            {{ $schoolClass->status }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/schoolclass.created_at') }}</strong>
                        </td>
                        <td>

                            {{  outputDateFormat($schoolClass->created_at) }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>  {{ trans('admin/schoolclass.updated_at') }}</strong>
                        </td>
                        <td>

                            {{ outputDateFormat($schoolClass->updated_at) }}
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