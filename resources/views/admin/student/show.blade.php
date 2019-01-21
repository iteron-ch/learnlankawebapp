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
                            <strong>  {{ trans('admin/admin.first_name') }}</strong>
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
                    @if(!empty($user->image))
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/admin.profile_photo') }}</strong>
                        </td>
                        <td>
                            {!! HTML::image(route('userimg', ['file' => $user->image, 'size' => 'small'])) !!}
                        </td>
                    </tr>
                    @endif
                    @if(isset($user->studentClass))
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.schoolclasses_id') }}</strong>
                        </td>
                        <td>
                            {{ $user->studentClass }}
                        </td>
                    </tr>
                    @endif
                    @if(isset($user->groups))
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.group') }}</strong>
                        </td>
                        <td>
                            @foreach($user->groups as $group)
                            {!! trim($group['group_name']).'<br>' !!}
                            @endforeach
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
                            <strong> {{ trans('admin/student.date_of_birth') }}</strong>
                        </td>
                        <td>
                            {{ outputDateFormat($user->date_of_birth) }}
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.email') }}</strong>
                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.telephone_no') }}</strong>
                        </td>
                        <td>
                            {{ $user->telephone_no }}
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
                            <strong>  {{ trans('admin/admin.postal_code') }}</strong>
                        </td>
                        <td>
                            {{ $user->postal_code }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ trans('admin/admin.county') }}</strong>
                        </td>
                        <td>
                            {{ $user->county }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/admin.country') }}</strong>
                        </td>
                        <td>
                            {{ $user->country }}
                        </td>
                    </tr>
                    <?php if(isset($sess_user_type)  && ($sess_user_type == 2 || $sess_user_type == 3)){ ?>
                    @if(isset($user->ethnicity_name))
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.ethnicity') }}</strong>
                        </td>
                        <td> 
                            {{ $user->ethnicity_name }}
                        </td>
                    </tr>  
                    @endif
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.sen') }}</strong>
                        </td>
                        <td> 
                            @if($user->sen_provision == '0')
                            No
                            @else
                            Yes
                            @endif
                        </td>
                    </tr>  
                    @if($user->sen_provision == '1')
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.sen_provision_desc') }}</strong>
                        </td>
                        <td> 
                            @if($user->sen_provision_desc == '1')
                            SEN Support
                            @else
                            EHC Plan
                            @endif
                        </td>
                    </tr>  
                    @endif
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.sats_exempt') }}</strong>
                        </td>
                        <td> 
                            @if($user->sats_exempt == '1')
                            Yes
                            @else
                            No
                            @endif
                        </td>
                    </tr>  
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.ks1_average_point_score') }}</strong>
                        </td>
                        <td> 
                            @if($user->ks1_average_point_score == '1')
                            Score
                            @elseif($user->ks1_average_point_score == '2')
                            Percentage
                            @else
                            NA
                            @endif
                        </td>
                    </tr> 
                    @if($user->sen_provision !== '3')
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.ks1_average_point_score_value') }}</strong>
                        </td>
                        <td> 
                            {{ $user->ks1_average_point_score_value }}
                        </td>
                    </tr>  
                    @endif
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
                    @if($user->maths_target_value !== '0.00')
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.maths_target') }}</strong>
                        </td>
                        <td> 
                            @if($user->maths_target == '1')
                            Score
                            @else
                            Percentage
                            @endif
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.maths_target_value') }}</strong>
                        </td>
                        <td> 
                            {{ $user->maths_target_value }}
                        </td>
                    </tr>
                    @endif
                    @if($user->english_target_value !== '0.00')
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.english_target') }}</strong>
                        </td>
                        <td> 
                            @if($user->english_target == '1')
                            Score
                            @else
                            Percentage
                            @endif
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.english_target_value') }}</strong>
                        </td>
                        <td> 
                            {{ $user->english_target_value }}
                        </td>
                    </tr>
                    @endif
                    @if(!empty($user->UPN))
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.upn') }}</strong>
                        </td>
                        <td> 
                            {{ $user->UPN }}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.date_of_entry') }}</strong>
                        </td>
                        <td>
                            {{ outputDateFormat($user->date_of_entry) }}
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.fsm') }}</strong>
                        </td>
                        <td> 
                            @if($user->fsm_eligibility == '1')
                            Yes
                            @else
                            No
                            @endif
                        </td>
                    </tr> 
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.eal') }}</strong>
                        </td>
                        <td> 
                            @if($user->eal == '1')
                            Yes
                            @else
                            No
                            @endif
                        </td>
                    </tr> 
                     @if(!empty($user->term_of_birth))
                    <tr>
                        <td>
                            <strong> {{ trans('admin/student.term_of_birth') }}</strong>
                        </td>
                        <td> 
                            @if($user->term_of_birth == '1')
                            Autumn Term
                            @elseif($user->term_of_birth == '2')
                            Autumn Term
                            @elseif($user->term_of_birth == '3')
                            Summer Term
                            @endif
                        </td>
                    </tr> 
                    @endif
                    <?php } ?>
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