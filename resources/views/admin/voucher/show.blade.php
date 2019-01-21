@extends('admin.layout._iframe')

@section('content')

<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title tabbable-line">
                <div class="caption caption-md">
                    <i class="icon-globe theme-font hide"></i>
                    <span class="caption-subject font-grey-madison bold">{{ trans('admin/voucher.voucher_details') }}</span>
                </div>
            </div>
            <div class="portlet-body">
                <table class="table table-light table-hover">
                    <tr>
                        <td width=30%>
                            <strong>{{ trans('admin/voucher.voucher_code') }}</strong>
                        </td>
                        <td>
                            {{ $voucherRepo->voucher_code }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <strong> {{ trans('admin/voucher.discount') }}</strong>
                        </td>
                        <td>
                            {{ $voucherRepo->discount }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <strong> {{ trans('admin/voucher.discount_type') }}</strong>
                        </td>
                        <td>
                            {{ $voucherRepo->discount_type }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <strong> {{ trans('admin/voucher.valid_from') }}</strong>
                        </td>
                        <td>
                            {{ $voucherRepo->start_date ? outputDateFormat($voucherRepo->start_date) : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>{{ trans('admin/voucher.validTo') }}</strong>
                        </td>
                        <td>
                             
                            {{ $voucherRepo->end_date ? outputDateFormat($voucherRepo->end_date) : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <strong> {{ trans('admin/voucher.user_type') }}</strong>
                        </td>
                        <td>
                            @if(($voucherRepo->user_type)==2)
                           {{'School'}}
                            @else
                            {{'Parent/Tutor'}} 
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <strong> {{ trans('admin/voucher.status') }}</strong>
                        </td>
                        <td>
                            {{ $voucherRepo->status }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                           <strong> {{ trans('admin/voucher.created_at') }}</strong>
                        </td>
                        <td>
                             {{ $voucherRepo->created_at ? outputDateFormat($voucherRepo->created_at) : '' }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                          <strong>  {{ trans('admin/voucher.updated_at') }}</strong>
                        </td>
                        <td>
                            {{ $voucherRepo->updated_at ? outputDateFormat($voucherRepo->updated_at) : '' }}
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