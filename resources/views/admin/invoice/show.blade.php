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
            <?php // print_r($invoiceDetails); die('jksdhajhsdk'); ?>
            <div class="portlet-body">
                <table class="table table-light table-hover">
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/invoice.invoice_no') }}</strong>
                        </td>
                        <td>
                            {{ $invoiceDetails['transaction_id'] }}
                        </td>
                    </tr>  
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/admin.username') }}</strong>
                        </td>
                        <td>
                            {{ $invoiceDetails['username'] }}
                        </td>
                    </tr>  
                    <tr>
                        <td width=30%> 
                            <strong> {{ trans('admin/admin.email') }}</strong>
                        </td>
                        <td>
                            {{ $invoiceDetails['email'] }}
                        </td>
                    </tr>  
                    <!--<tr>
                        <td>
                            <strong>  {{ trans('admin/invoice.start_date') }}</strong>
                        </td>
                        <td>
                            {{ outputDateFormat($invoiceDetails['subscription_start_date']) }}
                        </td>
                    </tr>                    

                    <tr>
                        <td>
                            <strong> {{ trans('admin/invoice.end_date') }}</strong>
                        </td>
                        <td>
                            {{ outputDateFormat($invoiceDetails['subscription_expiry_date']) }}
                        </td>
                    </tr>-->                      
                    <tr>
                        <td>
                            <strong> {{ trans('admin/invoice.no_of_students') }}</strong>
                        </td>
                        <td>
                            {{ $invoiceDetails['no_of_students'] }}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong> {{ trans('admin/invoice.amount_gbp') }}</strong>
                        </td>
                        <td>
                            {{ '£ '.$invoiceDetails['amount'] }}
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