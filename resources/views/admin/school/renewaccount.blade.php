@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => 'Renew Subscription', 'trait_1' => 'Users', 'trait_2' => 'Renew Subscription'])
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
    <div class="col-md-12">
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box green">
            <div class="portlet-title">
                <div class="caption">
                    Renew Subscription
                </div>

                <div class="actions">
                    <a href="{{ route('school.index') }}" class="btn btn-default btn-sm">
                        <i class="glyphicon glyphicon-chevron-left"></i> {!! trans('admin/admin.back') !!} </a>
                </div>
            </div>
            <div class="portlet-body form">
                <!-- BEGIN FORM-->

                <div class="form-body">
                    {!! Form::model($user, ['route' => ['user.renew', encryptParam($user['id'])], 'method' => 'put', 'files' => true, 'class' => 'form-horizontal','id' =>'schoolfrm']) !!}
                    @include('admin.partials.form_error')
                    <div class="form-group">
                        <label class="control-label col-md-3">Current Number of Pupil Licenses</label>
                        <div class="col-md-1">
                            <label class="control-label col-md-3">{{ !empty($user['total_number_of_student']) ? $user['total_number_of_student'] : 0 }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Remaining Number of Pupil Licenses</label>
                        <div class="col-md-1">
                            <label class="control-label col-md-3">{{ !empty($user['remaining_no_of_student']) ? $user['remaining_no_of_student'] : 0 }}</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3">Subscription Expiry Date</label>
                        <div class="col-md-2">
                            <label class="control-label col-md-3">{{ (!empty($user['subscription_expiry_date']) && $user['subscription_expiry_date']!='0000-00-00 00:00:00') ? outputDateFormat($user['subscription_expiry_date']) : 'NA' }}</label>
                        </div>
                    </div>                    
                    
                    @if($user['user_type'] == TUTOR)
                    <div class="form-group">
                        <label class="control-label col-md-3">Subscription Renewal Fee</label>
                        <div class="col-md-4"><?php echo CURRENCY ?>
                            <p class="form-control-static"><?php echo $feeRecord['parent_sign_up_fee'] ?></p>  
                        </div>
                    </div>
                   @endif 
                   @if($user['user_type'] == SCHOOL)    
                    <div class="form-group">
                        <label class="control-label col-md-3">Subscription Renewal Fee</label>
                        <div class="col-md-4"><?php echo CURRENCY ?>
                            <p class="form-control-static"><?php echo $feeRecord['school_sign_up_fee'] ?></p>  
                        </div>
                    </div>                    
                    @endif

                    
                    <div class="form-group">
                        <label class="control-label col-md-3">&nbsp;</label>
                        <div class="col-md-4">
                            <div class="radio-list" data-error-container="#form_2_membership_error">
                                <label>
                                    {!! Form::radio('payment_method', 'Creditcard',true, ['id'=>'creditcard']) !!}
                                    Credit/Debit Card </label>
                                @if($user['user_type'] == SCHOOL)
                                <label>
                                    {!! Form::radio('payment_method', 'Invoiced', false,['id'=>'invoiced']) !!}
                                    Invoiced</label>
                                @endif
                            </div>
                        </div>
                    </div> 
                    <div class="form-actions" id="invoice_div" style="display:none;">
                        <div class="row">
                            <div class="col-md-offset-3 col-md-9">
                                {!! Form::button('Upgrade', array('type' => 'submit', 'class' => 'btn green','id'=>'submit_btn')) !!}
                                {!! HTML::link(route('dashboard'), trans('admin/admin.cancel'), array('class' => 'btn default')) !!}
                            </div>
                        </div>
                    </div>
                    </form>
                    <div class="form-body" id="credit_card">
                        <div class="form-group">
                            <h2>Credit/Debit Card Details</h2>
                        </div>
                        <script src="/js/braintree-hosted-fields-beta.17.js"></script>
                        @if($user['user_type'] == SCHOOL)
                            <form name="payment-form" id="payment-form" action="/renew_subscription_make_payment?user_id=<?php echo encryptParam($user['id']) ?>&no_of_students=5" method="post">
                        @else        
                            <form name="payment-form" id="payment-form" action="/renew_subscription_make_payment?user_id=<?php echo encryptParam($user['id']) ?>&no_of_students=1" method="post">
                        @endif            
                            <div class="paywith_Content">
                                <ul class="relative">
                                    <li class="mtTabs" style="list-style-type: none;" >
                                        <div class="mtTabsCont">
                                            <div class="row">
                                                <label>Card Number<span class="red">*</span></label>    
                                                <div class="col1" id="card-number"></div>
                                            </div>
                                            <div class="row">
                                                <label>Cvv No.<span class="red">*</span></label>    
                                                <div class="col1" id="cvv"></div>
                                                <div style="font-size: 12px;">(last 3 digits on the back of your card)</div>
                                            </div>
                                            <div class="row">
                                                <label>Expiration Date<span class="red">*</span></label>    
                                                <div class="col1" id="expiration-date"></div>
                                            </div>
                                            <div class="row">
                                                <span id="error_msg" class="error" style="color:red;"></span><br>
                                                <input type="submit" name="submit" value="Make Payment" class="btn green next_step2">                   
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </form>
                       

                    </div>


                    <!--	</form>-->

                    <!-- END FORM-->
                </div>
                <!-- END VALIDATION STATES-->
            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts') 
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! JsValidator::formRequest($JsValidator, '#payment-form'); !!}   
<!-- END PAGE LEVEL SCRIPTS -->
<style>
    #card-number,
    #cvv,
    #expiration-date{width:550px; margin:0px; line-height: 21px; padding: 0 18px; border:2px solid #c6ecff; box-shadow: 0 0 1px #c0c0c0; height:46px;margin-top:10px; color: #bababa; font-size: 19px; font-family:'GothamRounded-Medium';  border-radius:10px;}
    #card-number.braintree-hosted-fields-invalid,
    #cvv.braintree-hosted-fields-invalid,
    #expiration-date.braintree-hosted-fields-invalid{ border: 2px solid #D0041D;}
</style>
<script>
        var user_type = <?php echo $user['user_type'] ?>;
        var per_5_student_fee = <?php echo $feeRecord['per_5_student_fee'] ?>;
        var per_student_fee = <?php echo $feeRecord['per_student_fee'] ?>;
        braintree.setup('<?php echo $clientToken; ?>', "custom", {
        id: "payment-form",
                hostedFields: {
                number: {
                selector: "#card-number",
                        placeholder: "Card Number"
                },
                        cvv: {
                        selector: "#cvv",
                                placeholder: "Cvv Number"
                        },
                        expirationDate: {
                        selector: "#expiration-date",
                                placeholder: "Expiry Date (mm/yy)"
                        },
                        styles: {
                        "input": {
                        
                                "color": "#3A3A3A"
                        },
                                ".number": {
                                "font-family": "monospace"
                                },
                                ":focus": {
                                "color": "black"
                                },
                                ".valid": {
                                "color": "green"
                                },
                                ".invalid": {
                                "color": "red"
                                },
                        },
                }
        });</script>

<script>
            jsMain = new Main();
            $(document).ready(function() {

            jsMain.toggleOther(['howfinds_id', 'howfinds_other'], {{ OTHER_VALUE }});
            jsMain.toggleOther(['school_type', 'whoyous_id'], {{ OTHER_VALUE }});
            $("#invoiced").click(function (){
            $("#credit_card").hide();
                    $("#invoice_div").show();
            });
            $("#creditcard").click(function (){
            $("#credit_card").show();
                    $("#invoice_div").hide();
            });
            $(".next_step2").click(function () {
                $("#error_msg").html('Please wait while your payment is processing. Please do not refresh the page.');
            });
    });
</script>
@stop