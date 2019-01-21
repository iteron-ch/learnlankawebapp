@extends('front.layout._signupdefault')
@section('content')
<style>
    .datepicker{
        position:absolute;
    }
</style>
<!-- BEGIN PAGE CONTENT-->

<div class="main_container signup_page">
    {!! Form::model($user,['route' => ['fronttutor.store'],'files' => true, 'method' => 'post', 'id' =>'addfrm']) !!}
    {!! Form::hidden('userId',isset($user['id'])?encryptParam($user['id']):"",['class'=>'input_text'])  !!}
    <span id="frmStep1">

        <div class="signup_top">
            <div class="signup_bottom">
                <div class="signup_mid">
                    <div class="signup_header">
                        <div class="point active">1
                            <div class="done"></div>
                            <label for="">{{ trans('front/front.basic_information') }}<span class="arrow"></span></label>
                        </div>
                        <div class="point yellow">2
                            <div class="done"></div>
                            <label for="">{{ trans('front/front.subscription') }}<span class="arrow"></span></label>
                        </div>
                        <div class="point green">3
                            <div class="done"></div>
                            <label for="">{{ trans('front/front.complete') }}<span class="arrow"></span></label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col1">
                            <label for="" class="auto">{{ trans('front/front.sign_up_as') }}</label>
                            <input id="tutor" class="css-radio" type="radio" name="is_parent" value="1" >
                            <label for="tutor" class="css-label">{{ trans('front/front.tutor') }}</label>
                            <input id="parent" class="css-radio" type="radio" name="is_parent" value="2" checked="true">
                            <label for="parent" class="css-label">{{ trans('front/front.parent') }}</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col3">
                            {!! Form::labelControl('first_name',trans('front/front.first_name'))  !!}
                            {!! Form::text('first_name',null,['class'=>'input_text']  )  !!}<span class="red">*</span>
                        </div>
                        <div class="col3">
                            {!! Form::labelControl('last_name',trans('front/front.last_name'))  !!}
                            {!! Form::text('last_name',null,['class'=>'input_text']  )  !!}<span class="red">*</span>
                        </div>
                        <div class="col3">
                            {!! Form::labelControl('username',trans('front/front.username'))  !!}
                            {!! Form::text('username',null,['class'=>'input_text']  )  !!}<span class="red">*</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col3">
                            {!! Form::labelControl('email',trans('front/front.email'))  !!}
                            {!! Form::text('email',null,['class'=>'input_text']  )  !!}<span class="red">*</span>
                        </div>
                        <div class="col3">

                            {!! Form::labelControl('password',trans('front/front.password'))  !!}
                            {!! Form::password('password',['class'=>'input_text']  )  !!}<span class="red">*</span>
                        </div>
                        <div class="col3">
                            {!! Form::labelControl('confirm_password',trans('front/front.confirm_password'))  !!}
                            {!! Form::password('confirm_password',['class'=>'input_text']  )  !!}<span class="red">*</span> 
                        </div>
                    </div>
                    <div class="row">
                        <div class="col3">
                            {!! Form::labelControl('gender',trans('front/front.gender'))  !!}
                            {!! Form::radio('gender', 'Male', true,['class'=>'css-radio','id'=>'Male']) !!}
                            <label for="Male" class="css-label">{!! trans('front/front.male') !!}</label>
                            {!! Form::radio('gender', 'Female',false, ['class'=>'css-radio','id'=>'Female']) !!}
                            <label for="Female" class="css-label">{!! trans('front/front.female') !!}</label>
                        </div>
                        <div class="col3">
                            {!! Form::labelControl('date_of_birth',trans('front/front.dob_date'))  !!}
                            {!! Form::text('date_of_birth',null,['class'=>'input_text','readonly'=>'readonly']  )  !!}
                            <span class="red">*</span>

                        </div>
                        <div class="col3">
                            {!! Form::labelControl('postal_code',trans('front/front.postal_code'))  !!}
                            {!! Form::text('postal_code',null,['class'=>'input_text','id'=>'postal_code']  )  !!}<span class="red">*</span>
                            <div id="suggesstion-box" style="display:none">

                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col1">
                            {!! Form::labelControl('address',trans('front/front.address'))  !!}
                            {!! Form::textarea('address',null,['class'=>'input_text','cols'=>'','rows'=>'','id'=>'address'])  !!}<span class="red">*</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col3">
                            {!! Form::labelControl('country',trans('front/front.country'))  !!}
                            {!! Form::select('country', $country,UKCOUNTRYCODE, ['id' => 'country', 'class' => 'e1']) !!}  <span class="red">*</span>
                        </div>
                        <div class="col3">
                            {!! Form::labelControl('city',trans('front/front.city'))  !!}
                            {!! Form::text('city',null,['class'=>'input_text','id'=>'city']  )  !!}<span class="red">*</span>
                            <ul class="suggestion" id="city_list_id" style="display:none"></ul>
                        </div>

                        <div class="col3">
                            {!! Form::labelControl('county',trans('front/front.county'))  !!}
                            {!! Form::select('county', $county,null, ['id' => 'county', 'class' => 'e1']) !!}  <span class="red">*</span> 

                        </div>

                    </div>
                    <div class="row">

                        <div class="col3">
                            {!! Form::labelControl('telephone_no',trans('front/front.telephone_no'))  !!}
                            {!! Form::text('telephone_no',null,['class'=>'input_text']  )  !!}<span class="red">*</span>
                        </div>
                        <div class="col3">
                            {!! Form::labelControl('howfinds_id',trans('front/front.how_you_find'))  !!}
                            {!! Form::select('howfinds_id', $howfind, null, ['id' => 'howfinds_id', 'class' => 'e1']) !!} <span class="red">*</span>
                        </div>
                        <div class="col3" id="other-howfinds_id" style="display: {{ Input::old('howfinds_id') == OTHER_VALUE ? 'display' : ( isset($user['howfinds_id']) && $user['howfinds_id'] == OTHER_VALUE ? 'display': 'none')}};">
                            {!! Form::labelControl('school_type',trans('front/front.please_specify'))  !!}
                            {!! Form::text('howfinds_other',null,['id' => 'howfinds_other','class'=>'input_text']) !!}<span class="red">*</span>
                        </div>
                    </div>
                    <div class="head">
                        <div class="col_left">
                            <strong>Additional Number of Children</strong>
                            <i><?php echo TUTOR_NO_OF_STUDENTS ?> child included. {{ trans('front/front.each_option') }} {{ CURRENCY }}{!! $per_student_fee !!}</i>
                        </div>
                        <div class="col_mid">
                            <a href="javascript:void(0);" class="prev" onClick="change_count('minus')"></a>
                            <strong>{!! Form::text('no_of_student',isset($user['no_of_student'])?$user['no_of_student']:'0',['class'=>'input_text','id'=>'no_of_student']  )  !!}</strong>
                            <a href="javascript:void(0);" class="next" onClick="change_count('plus')"></a>
                        </div>
                        <div class="col_right mar_t_-13" ><?php
                            $noOfStudent = isset($user['no_of_student']) ? $user['no_of_student'] : '0';
                            ?>
                            <span>{{ trans('front/front.your_plan') }}</span> <strong>{{ CURRENCY }}{{ $parent_sign_up_fee }}</strong><br>
                            <span>Total Amount</span> <strong>{{ CURRENCY }}<span id="total_amt_id">{{$parent_sign_up_fee+$noOfStudent*$per_student_fee}}</span></strong> 
                        </div>
                    </div>                    

                    <div class="row button_row">
                        <div class="col3">
                            {!! Form::button(trans('front/front.next'), array('type' => 'button','id'=>'next_step1','class' => 'button')) !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </span>
    <span id="frmStep2" style="display:none;">

        <div class="signup_top">
            <div class="signup_bottom">
                <div class="signup_mid">
                    <div class="signup_header">
                        <div class="point selected" id="btn_basicinfo" style="cursor:pointer;">1
                            <div class="done"></div>
                            <label for="">{{ trans('front/front.basic_information') }}<span class="arrow"></span></label>
                        </div>
                        <div class="point yellow active">2
                            <div class="done"></div>
                            <label for="">{{ trans('front/front.subscription') }}<span class="arrow"></span></label>
                        </div>
                        <div class="point green">3
                            <div class="done"></div>
                            <label for="">{{ trans('front/front.complete') }}<span class="arrow"></span></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col1">
                            <label for="" class="auto">{{ trans('front/front.billing_information') }}</label>
                            {!! Form::radio('same_billing', 'same', null, ['class' => 'css-radio','id'=>'same_billing']) !!}
                            <label for="same_billing" class="css-label">Same as basic information</label>
                            {!! Form::radio('same_billing', 'other', 'checked', ['class' => 'css-radio','id'=>'other']) !!}
                            <label for="other" class="css-label">Other</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col3">
                            {!! Form::labelControl('billing_first_name',trans('front/front.first_name'))  !!}
                            {!! Form::text('billing_first_name',null,['class'=>'input_text','id'=>'billing_first_name']  )  !!}
                            <span class="red">*</span>
                        </div>
                        <div class="col3">
                            {!! Form::labelControl('billing_last_name',trans('front/front.last_name'))  !!}
                            {!! Form::text('billing_last_name',null,['class'=>'input_text','id'=>'billing_last_name']  )  !!}<span class="red">*</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col1">
                            {!! Form::labelControl('billing_address',trans('front/front.address'))  !!}
                            {!! Form::textarea('billing_address',null,['class'=>'input_text','id'=>'billing_address','rows'=>'','cols'=>'']  )  !!}</textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col3">
                            {!! Form::labelControl('billing_city',trans('front/front.city'))  !!}
                            {!! Form::text('billing_city',null,['class'=>'input_text','id'=>'billing_city']  )  !!}
                            <ul class="suggestion" id="billing_city_list_id" style="display:none"></ul>
                        </div>
                        <div class="col3">
                            {!! Form::labelControl('billing_postal_code',trans('front/front.postal_code'))  !!}
                            {!! Form::text('billing_postal_code',null,['class'=>'input_text','id'=>'billing_postal_code']  )  !!}
                        </div>
                        <div class="col3">
                            {!! Form::labelControl('billing_county',trans('front/front.county'))  !!}
                            {!! Form::select('billing_county', $county,null, ['id' => 'billing_county', 'class' => 'e1']) !!}   
                        </div>
                    </div>
                    <div class="row">
                        <div class="col3">
                            {!! Form::labelControl('billing_country',trans('front/school.country'))  !!}
                            {!! Form::select('billing_country', $country,'GB', ['id' => 'billing_country', 'class' => 'e1']) !!}  
                        </div>
                        <div class="col3" style="width:45%">
                            <label for="">&nbsp;</label><label for="">&nbsp;</label>
                            <input type="checkbox" id="terms" name="terms" value="1"> I accept the <a href="javascript:void(0)" onclick="opencolorbox1()">Terms & Condition</a> and agree to the <a href="javascript:void(0)" onclick="opencolorbox2()">Privacy Policy</a>.
                            <br><br><span id="termserror" style="display:none;border-radius: 0 0 10px 10px;     color: #fff;     display: block;     font-family: &quot;GothamRounded-Medium&quot;;     margin-top: -10px;     padding: 10px 20px;     position: relative;     text-align: left;     z-index: 2;"></span>
                        </div>
                    </div>

                    <div class="head signup_2">
                        <div class="col_left">
                            {{ trans('front/front.your_plan_details') }}
                        </div>

                        <div class="col_right">
                            <span>{{ trans('front/front.amount') }}</span> 
                            <strong>{{ CURRENCY }}{{$parent_sign_up_fee}}</strong> 
                        </div>
                        <div class="col_right">
                            <span>TOTAL NO OF CHILDREN</span> 
                            <strong><span id="no_of_student_count">{{isset($user['no_of_student'])?(TUTOR_NO_OF_STUDENTS+$user['no_of_student']):'0'}}</span></strong> 
                        </div>
                        <div class="col_right last">
                            <span>{{ trans('front/front.total_amount') }}</span> <?php
                            $noOfStudent = isset($user['no_of_student']) ? $user['no_of_student'] : '0';
                            ?>
                            <input type="hidden" name="total_subscription_amount" value="{{$parent_sign_up_fee+$noOfStudent*$per_student_fee}}" id="total_subscription_amount">
                            <input type="hidden" name="total_discount_amount" value="" id="total_discount_amount">
                            <input type="hidden" name="is_code_applied" value="0" id="is_code_applied">

                            <strong>{{ CURRENCY }}<span id="total_payable_amount">{{$parent_sign_up_fee+$noOfStudent*$per_student_fee}}</span></strong> 
                        </div>
                    </div>  
                    <div class="row button_row">
                        <div class="col3">
                            {!! Form::text('voucher_code',null,['class'=>'input_text','id'=>'voucher_code','placeholder'=>"Voucher Code"] )  !!}
                            <span class="voucher_error" id="vouchercode_status"></span>
                        </div>
                        <div class="col3">
                            {!! Form::button('Apply Code', array('type' => 'button', 'id'=>'apply_code','class' => 'button')) !!}
                        </div>
                        <div class="col3">
                            {!! Form::button(trans('front/front.pay_now'), array('type' => 'submit', 'id'=>'next_step2','class' => 'button')) !!} 
                        </div>
                    </div>
                    <div class="row">
                            <strong>Please Note</strong>: When you proceed to make payment, do not click the back button as you may have to re-enter your details.                        
                    </div>
                </div>
            </div>
        </div>
    </span>
    <span id="frmStepProcessing" style="display:none;">
        <div class="signup_top">
            <div class="signup_bottom">
                <div class="signup_mid">
                    <div class="signup_header">
                        <div class="point selected">1
                            <div class="done"></div>
                            <label for="">{{ trans('front/front.basic_information') }}<span class="arrow"></span></label>
                        </div>
                        <div class="point yellow active">2
                            <div class="done"></div>
                            <label for="">{{ trans('front/front.subscription') }}<span class="arrow"></span></label>
                        </div>
                        <div class="point green">3
                            <div class="done"></div>
                            <label for="">{{ trans('front/front.complete') }}<span class="arrow"></span></label>
                        </div>
                    </div>
                    <div class="row payment_processing">
                        <label>{{ trans('front/front.please_wait') }}</label>
                        <label>{{ trans('front/front.do_not_referesh') }}</label>
                    </div>
                </div>
            </div>
        </div>        
    </span>
    {!! Form::close() !!}
</div>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
{!! JsValidator::formRequest($JsValidator, '#addfrm'); !!}     
<script>
    jsMain = new Main();
    var totalNoOfStudents = <?php echo TUTOR_NO_OF_STUDENTS ?>;
    function change_count(act) {
        if (act == 'plus') {
            $("#no_of_student").val($("#no_of_student").val() * 1 + 1);
            $("#no_of_student_count").html(totalNoOfStudents * 1 + $("#no_of_student").val() * 1);
        }
        else if (act == 'minus') {
            if ($("#no_of_student").val() > 0) {
                $("#no_of_student").val($("#no_of_student").val() * 1 - 1);
                $("#no_of_student_count").html(totalNoOfStudents * 1 + $("#no_of_student").val() * 1);
            }
        }

        var parent_sign_up_fee = '<?php echo $parent_sign_up_fee ?>';
        var per_student_fee = '<?php echo $per_student_fee ?>';
        var subAmt = (parent_sign_up_fee * 1 + $("#no_of_student").val() * per_student_fee * 1).toFixed(2);
        $("#total_subscription_amount").val(subAmt);
        $("#total_payable_amount").html(subAmt)
        $("#total_amt_id").html(subAmt)

    }
    $("#postal_code").autocomplete({
        minLength: 4,
        source: function (request, response) {
            $.ajax({
                dataType: "json",
                type: 'POST',
                url: 'address.php',
                data: {postal_code: $("#postal_code").val()},
                success: function (data) {
                    var html = '<ul class="suggestion">';
                    $.each(data, function (key, value) {
                        console.log()
                        html += '<li onclick="setValues(\'' + value[0] + '\')">' + value[0] + '</li>';
                    });
                    html += '<ul>';
                    $('#suggesstion-box').show();
                    $('#suggesstion-box').html(html);
                },
            });
        }
    });
    function setValues(address) {
        var splitAdd = address.split(",");
        $('#city').val(splitAdd[splitAdd.length - 1]);
        $('#address').val(address);
        $('#suggesstion-box').hide();
        $('#address').focus();
        return false;
    }
    function payment_by(act) {
        if (act == "invoice") {
            $("#credit_card_payemnt").hide();
            $("#payment_type").val('Invoiced');
            $("#invoice_act").addClass('active');
            $("#cc_act").removeClass('active');
            //$("#cc_div").removeClass('min-height');
            $("#next_step2").html('SUBMIT');
        }
        else if (act == "credit-card") {
            $("#credit_card_payemnt").show();
            $("#payment_type").val('Creditcard');
            $("#invoice_act").removeClass('active');
            $("#cc_act").addClass('active');
            // $("#cc_div").addClass('min-height');
            $("#next_step2").html('PAY NOW');
        }
    }
    function set_city(item) {
        $('#city').val(item);
        $('#city_list_id').hide();
        $('#billing_city').val(item);
        $('#billing_city_list_id').hide();
    }
    $(document).ready(function () {
        var parent_sign_up_fee = '<?php echo $parent_sign_up_fee ?>';
        var per_student_fee = '<?php echo $per_student_fee ?>';
        var subAmt = (parent_sign_up_fee * 1 + $("#no_of_student").val() * per_student_fee * 1).toFixed(2);
        $("#total_amt_id").html(subAmt)

        $("#city").keyup(function () {
            var min_length = 3;
            var keyword = $('#city').val();
            if (keyword.length >= min_length) {
                $.ajax({
                    url: "/getcities",
                    type: 'POST',
                    data: {keyword: keyword},
                    success: function (data) {
                        $('#city_list_id').show();
                        $('#city_list_id').html(data);
                    }
                });
            } else {
                $('#city_list_id').hide();
            }
        });
        $("#billing_city").keyup(function () {
            var min_length = 1;
            var keyword = $('#billing_city').val();
            if (keyword.length >= min_length) {
                $.ajax({
                    url: "/getcities",
                    type: 'POST',
                    data: {keyword: keyword},
                    success: function (data) {
                        $('#billing_city_list_id').show();
                        $('#billing_city_list_id').html(data);
                    }
                });
            } else {
                $('#billing_city_list_id').hide();
            }
        });
                jsMain.toggleOther(['howfinds_id', 'howfinds_other'], {{ OTHER_VALUE }});
    $('#date_of_birth').datepicker({
        autoclose: true,
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd/mm/yy',
        maxDate: new Date,
        yearRange: "-100:+0"
    });
    var error_type = '';
    error_type = '<?php echo $error_type ?>';
    if (error_type == 'make_payment') {
        $("#frmStep1").hide();
        $("#frmStep2").show();
    }
    $(".e1").select2();
    $("#next_step1").click(function () {
        var parent_sign_up_fee = '<?php echo $parent_sign_up_fee ?>';
        var per_student_fee = '<?php echo $per_student_fee ?>';
        if ($("#addfrm").valid()) {
            $("#js_flash_message").removeClass('space_for_message');
            $("#js_flash_message").hide();
            $(".space_for_message").hide();
            $("#frmStep1").hide();
            $("#frmStep2").show();
            $("#billing_first_name").focus();
            $("#no_of_student_count").html(totalNoOfStudents * 1 + $("#no_of_student").val() * 1);
            var subAmt = (parent_sign_up_fee * 1 + $("#no_of_student").val() * per_student_fee * 1).toFixed(2)
            $("#total_subscription_amount").val(subAmt);
            $("#total_payable_amount").html(subAmt)
            $("#total_amt_id").html(subAmt)
        }
    });
    $("#next_step2").click(function () {
        if ($("#addfrm").valid()) {
            if ($('#terms').is(':checked') == false) {
                $("#termserror").html('Please accept terms and conditions.');
                $("#termserror").css('background', '#f07b68 none repeat scroll 0 0')
                $("#termserror").show();
                return false;
            }
            else {
                $("#termserror").hide();
            }
            $("#js_flash_message").removeClass('space_for_message');
            $("#js_flash_message").hide();
            $(".space_for_message").hide();
            $("#frmStep2").hide();
            $("#navigation_link").hide();
            $(".alert-danger").hide();
            $("#frmStepProcessing").show();
        }
    });
    $("#btn_basicinfo").click(function (e) {
        e.preventDefault();
        $("#frmStep1").show();
        $("#frmStep2").hide();
    });
    $("#no_of_student").change(function (e) {
        $("#no_of_student_count").html(totalNoOfStudents * 1 + $("#no_of_student").val() * 1);
        var parent_sign_up_fee = '<?php echo $parent_sign_up_fee ?>';
        var per_student_fee = '<?php echo $per_student_fee ?>';
        var subAmt = (parent_sign_up_fee * 1 + $("#no_of_student").val() * per_student_fee * 1).toFixed(2);
        $("#total_subscription_amount").val(subAmt);
        $("#total_payable_amount").html(subAmt)
        $("#total_amt_id").html(subAmt)

    });
    $("input:radio[name=same_billing]").click(function () {
        var value = $(this).val();
        if (value == 'same') {
            $("#billing_first_name").val($("#first_name").val());
            $("#billing_last_name").val($("#last_name").val());
            $("#billing_address").val($("#address").val());
            $("#billing_city").val($("#city").val());
            $("#billing_postal_code").val($("#postal_code").val());
            $("#billing_county").val($("#county").val()).select2();
            $("#billing_country").val($("#country").val()).select2();
        }
        else {
            $("#billing_first_name").val('');
            $("#billing_last_name").val('');
            $("#billing_address").val('');
            $("#billing_city").val('');
            $("#billing_postal_code").val('');
            $("#billing_county").val('').select2();
            $("#billing_country").val('').select2();
        }
    });
    $("#apply_code").click(function () {
        $("#vouchercode_status").html('');
        if ($("#is_code_applied").val() == 0) {
            var voucher_code = $('#voucher_code').val();
            var user_type = <?php echo TUTOR ?>;
            if ($.trim(voucher_code) != '') {
                $.ajax({
                    url: "/applyvoucher",
                    type: 'POST',
                    data: {voucher_code: voucher_code, user_type: user_type},
                    success: function (data) {
                        if (data != '') {
                            var parentStrandJson = jQuery.parseJSON(data);
                            if (parentStrandJson['discount_type'] == 'Percent') {
                                var discount = ($("#total_subscription_amount").val() * parentStrandJson['discount']) / 100;
                                var amountAfterDiscount = ($("#total_subscription_amount").val() * 1 - discount.toFixed(2)).toFixed(2)

                                $("#total_discount_amount").val(discount.toFixed(2));
                                $("#total_payable_amount").html(amountAfterDiscount);
                                $("#vouchercode_status").html('Voucher code applied successfully.');
                            }
                            else if (parentStrandJson['discount_type'] == 'Amount') {
                                var discount = parentStrandJson['discount'] * 1;
                                var amountAfterDiscount = ($("#total_subscription_amount").val() * 1 - discount.toFixed(2)).toFixed(2)
                                $("#total_discount_amount").val(discount.toFixed(2));
                                $("#total_payable_amount").html(amountAfterDiscount);
                                $("#vouchercode_status").html('Voucher code applied successfully.');
                            }
                            $("#is_code_applied").val(1)
                        }
                        else {
                            $("#total_discount_amount").val(0);
                            $("#total_payable_amount").html($("#total_subscription_amount").val());
                            $("#vouchercode_status").html('Invalid voucher code.');
                            $("#voucher_code").val('');
                        }

                    }
                });
            } else {
                $("#vouchercode_status").html('Please enter voucher code.');
            }
        }
        else {
            $("#vouchercode_status").html('Voucher already applied.');
        }
    });
    $("#voucher_code").keyup(function () {
        $("#vouchercode_status").html('');
        if ($("#is_code_applied").val() == 1) {
            $("#is_code_applied").val(0);
            $("#total_discount_amount").val(0);
            $("#total_payable_amount").html($("#total_subscription_amount").val());
            $("#voucher_code").val('');
        }
    });
    });
            $(".selector").autocomplete({
        appendTo: "#someElem"
    });

</script>


<!-- GA Pixel Code -->
<script>
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-72857701-1', 'auto');
ga('send', 'pageview');

</script>
<!-- End GA Pixel Code -->

<!-- Facebook Pixel Code -->
<script>
!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
document,'script','//connect.facebook.net/en_US/fbevents.js');

fbq('init', '981414618607013');
fbq('track', "PageView");
fbq('track', 'InitiateCheckout');

</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=981414618607013&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<!-- Twitter REGISTER Pixel Code -->
<script src="//platform.twitter.com/oct.js" type="text/javascript"></script>
<script type="text/javascript">twttr.conversion.trackPid('nu7jl', { tw_sale_amount: 0, tw_order_quantity: 0 });</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://analytics.twitter.com/i/adsct?txn_id=nu7jl&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0" />
<img height="1" width="1" style="display:none;" alt="" src="//t.co/i/adsct?txn_id=nu7jl&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0" />
</noscript>
<!-- End Twitter REGISTER Pixel Code -->

{!! HTML::style('css/colorbox.css') !!}
<style>
    #cboxOverlay{ background:#666666;z-index:1 }
</style>
<script>
    function opencolorbox1() {
        $.colorbox({fastIframe: false, width: "800px", height: "800px", transition: "fade", scrolling: true, iframe: true, href: "/termsconditions"});
    }
    function opencolorbox2() {
        $.colorbox({fastIframe: false, width: "800px", height: "800px", transition: "fade", scrolling: true, iframe: true, href: "/privacypolicy"});
    }    
</script> 

@stop