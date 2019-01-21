@extends('admin.layout._iframe')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <div class="portlet light">
            <div class="portlet-title tabbable-line">
                <div class="caption caption-md">
                    <i class="icon-globe theme-font hide"></i>
                    <span class="caption-subject font-grey-madison bold">Payment Details</span>
                </div>
            </div>
            <div class="portlet-body">@include('admin.partials.form_error')
                @if(count($payment)>0)
                @foreach($payment as $key=>$val)
                {!! Form::open(['route' => ['payment.update',$payment[$key]['id']], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal panel','id' =>'schoolfrm']) !!}
                <input type="hidden" name="upgrade_type" value="<?php echo $payment[$key]['upgrade_type'] ?>">
                <table class="table table-light table-hover">
                    <tr>
                        <td width=10%> 
                            <strong>Order By</strong>
                        </td>
                        <td>
                            @if($payment[$key]['user_type'] == SCHOOL)
                            {{ trim($payment[$key]['school_name']) }}
                            @else    
                            {{ trim($payment[$key]['first_name'].' '.$payment[$key]['last_name']) }}
                            @endif    
                        </td>

                        <td width=10%>
                            <strong>Date</strong>
                        </td>
                        <td>
                            {{ outputDateFormat($payment[$key]['created_at']) }}
                        </td>
                    </tr>
                    <tr>    
                        <td width=10%>
                            <strong>Students</strong>
                        </td>
                        <td>
                            {{ $payment[$key]['no_of_students'] }}
                        </td>
                        <td width=10%>
                            <strong>Amount</strong>
                        </td>
                        <td>
                            {{ $payment[$key]['amount'] }}
                        </td>
                    </tr> 
                    <tr>    
                        <td colspan="2">
                            <?php
                            if ($payment[$key]['upgrade_type'] == 3)
                                echo "<strong>Renew Subscription</strong>";
                            ?>
                        </td>                        
                        <td width=10%>
                            <strong>Status</strong>
                        </td>
                        <td>
                            {{ $payment[$key]['status'] }}
                        </td>
                    </tr> 
                    <tr>    
                        <td width=20%>
                            {!! Form::checkbox('payment_status', 'Success', null, ['class' => 'checkbox-inline chk_to','id' => 'payment_status']) !!} Mark Paid
                        </td>
                        <td  colspan="3">
                            {!! Form::textarea('payment_note',null,['class'=>'form-control']  )  !!} 
                        </td>
                    </tr> 
                    <tr>    
                        <td colspan="4">
                            {!! Form::button(trans('admin/admin.submit'), array('type' => 'submit', 'class' => 'btn green')) !!}
                        </td>
                    </tr> 
                </table>
                {!! Form::close() !!}
                @endforeach
                @else
                <div role="alert" class="alert alert-success alert-dismissible">
                    <button data-dismiss="alert" class="close" type="button">
                        <span aria-hidden="true">x</span>
                        <span class="sr-only">Close</span>
                    </button>
                    Payment status has been successfully updated. Please filter your search again.  
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</div>
<!-- END PAGE CONTENT-->
@stop
<script>
//var cnt = <?php //echo count($payment)  ?>;
//if(cnt == 0)
    //     parent.location.href('/payment');
</script>