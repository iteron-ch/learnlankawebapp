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
                        <td width=30% >
                            <strong>{{ trans('admin/school.school_name') }}</strong>
                        </td>
                        <td>
                            {{ $user->school_name }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/school.school_type') }}</strong>
                        </td>
                        <td>
                            {{ !empty($user->school_type) ? $user->school_type : $user->school_type_other }}
                        </td>
                    </tr>
                    @if(!empty($user->image))
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.profile_photo') }}</strong>
                        </td>
                        <td>
                            {!! HTML::image(route('userimg', ['file' => $user->image, 'size' => 'small'])) !!}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.username') }}</strong>
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
                            <strong> {{ trans('admin/school.whoyous_id') }}</strong>
                        </td>
                        <td>
                            {{ !empty($user->who_are_you) ? $user->who_are_you : $user->whoyous_other }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.address') }}</strong>
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
                            <strong> {{ trans('admin/admin.postal_code') }}</strong>
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
                    @if($user->ks1_maths_baseline_value !== '0.00')
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.ks1_maths_baseline') }}</strong>
                        </td>
                        <td> 
                            @if($user->ks1_maths_baseline == '1')
                            Score
                            @else
                            Percentage
                            @endif
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.ks1_maths_baseline_value') }}</strong>
                        </td>
                        <td> 
                            {{ $user->ks1_maths_baseline_value }}
                        </td>
                    </tr> 
                    @endif
                    @if($user->ks2_maths_baseline_value !== '0.00')
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.ks2_maths_baseline') }}</strong>
                        </td>
                        <td> 
                            @if($user->ks2_maths_baseline == '1')
                            Score
                            @else
                            Percentage
                            @endif
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.ks2_maths_baseline_value') }}</strong>
                        </td>
                        <td> 
                            {{ $user->ks2_maths_baseline_value }}
                        </td>
                    </tr> 
                    @endif
                    @if($user->ks1_english_baseline_value !== '0.00')
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.ks1_english_baseline') }}</strong>
                        </td>
                        <td> 
                            @if($user->ks1_english_baseline == '1')
                            Score
                            @else
                            Percentage
                            @endif
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.ks1_english_baseline_value') }}</strong>
                        </td>
                        <td> 
                            {{ $user->ks1_english_baseline_value }}
                        </td>
                    </tr> 
                    @endif
                    @if($user->ks2_english_baseline_value !== '0.00')
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.ks2_english_baseline') }}</strong>
                        </td>
                        <td> 
                            @if($user->ks2_english_baseline == '1')
                            Score
                            @else
                            Percentage
                            @endif
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.ks2_english_baseline_value') }}</strong>
                        </td>
                        <td> 
                            {{ $user->ks2_english_baseline_value }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/tutor.how_you_find') }}</strong>
                        </td>
                        <td>
                            <?php
                            if ($user->howfinds_id == '-1') {
                                echo $user->howfinds_other;
                            } else {
                                echo $user->howfinds_name;
                            }
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td width=30% >
                            <strong>{{ trans('admin/school.dfe_number') }}</strong>
                        </td>
                        <td>
                            {{ $user->dfe_number }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>   {{ trans('admin/admin.status') }}</strong>
                        </td>
                        <td> 
                            {{ $status[$user->status] }}
                        </td>
                    </tr>  
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/admin.send_email_notification') }}</strong>
                        </td>
                        <td>
                            @if($user->do_not_receive_email == 1)
                            {{ 'No' }}
                            @else 
                            {{ 'Yes' }}
                            @endif     
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