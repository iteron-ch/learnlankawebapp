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
                            <strong>{{ trans('admin/tutor.tutor_name') }}</strong>
                        </td>
                        <td>
                            {{ $revenue_details['username'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/tutor.description') }}</strong>
                        </td>
                        <td>
                            <?php
                            if ($revenue_details['upgrade_type'] == '1') {
                                $no_of_std = $revenue_details['no_of_students'];
                                echo '+ ' . $no_of_std . ' Student(s)';
                            } else if ($revenue_details['upgrade_type'] == '2') {
                                echo YEARLY_SUBS;
                            }
                            ?> 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/tutor.voucher') }}</strong>
                        </td>
                        <td>
                            {{ $revenue_details['voucher_code'] }}
                            <?php
                            if ($revenue_details['voucher_code'] == '') {
                                echo 'N/A';
                            } else {
                                echo $revenue_details['voucher_code'];
                            }
                            ?> 
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>  {{ trans('admin/tutor.date') }}</strong>
                        </td>
                        <td>
                            {{  outputDateFormat($revenue_details['payment_created_at']) }}
                        </td>
                    </tr>

                    <tr>
                        <td>
                            <strong>  {{ trans('admin/report.amount_gbp') }}</strong>
                        </td>
                        <td>
                            {{$revenue_details['amount'] }}
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