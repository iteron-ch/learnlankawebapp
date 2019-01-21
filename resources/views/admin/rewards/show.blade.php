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
                            <strong> {{ trans('admin/rewards.title') }}</strong>
                        </td>   
                        <td>
                            {{ $studentRewards['title'] }}
                        </td>
                    </tr> 
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/rewards.percentage') }}</strong>
                        </td>   
                        <td>
                            {{ $studentRewards['marks'] }}
                        </td>
                    </tr> 
                    @if(isset($studentRewards['student_id']))
                     <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/rewards.students') }}</strong>
                        </td>   
                        <td>
                            <?php  
                            foreach($studentRewards as $key=>$value){
                                echo $students[] = $value['first_name'].' '.$value['last_name'] ;
                                echo '</br>';
                            }
                            ?>
                        </td>
                    </tr> 
                    @endif
                     <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/rewards.award') }}</strong>
                        </td>   
                        <td>
                             <img src="/uploads/studentawards/<?php echo($studentRewards['image']); ?>"  height="150">
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