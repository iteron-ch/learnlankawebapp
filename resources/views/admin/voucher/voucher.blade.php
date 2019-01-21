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
                        <td>
                            {{ trans('admin/school.school_name') }}
                        </td>
                        <td>
                            {{ $user->school_name }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ trans('admin/school.school_type') }}
                        </td>
                        <td>
                            {{ $user->school_type }}
                        </td>
                    </tr>
                    @if(!empty($user->image))
                    <tr>
                        <td>
                            {{ trans('admin/school.profile_photo') }}
                        </td>
                        <td>
                            {!! HTML::image(route('userimg', ['file' => $user->image, 'size' => 'small'])) !!}
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <td>
                            {{ trans('admin/school.username') }}
                        </td>
                        <td>
                            {{ $user->username }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ trans('admin/school.email') }}
                        </td>
                        <td>
                            {{ $user->email }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ trans('admin/school.telephone_no') }}
                        </td>
                        <td>
                            {{ $user->telephone_no }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ trans('admin/school.whoyous_id') }}
                        </td>
                        <td>
                            {{ $user->who_are_you }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ trans('admin/school.address') }}
                        </td>
                        <td>
                            {{ $user->address }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ trans('admin/school.city') }}
                        </td>
                        <td>
                            {{ $user->city }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ trans('admin/school.postal_code') }}
                        </td>
                        <td>
                            {{ $user->postal_code }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ trans('admin/school.county') }}
                        </td>
                        <td>
                            {{ $user->county }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ trans('admin/school.country') }}
                        </td>
                        <td>
                            {{ $user->country}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            {{ trans('admin/school.status') }}
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