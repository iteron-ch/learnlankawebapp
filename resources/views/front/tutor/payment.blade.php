@extends('front.layout._signupdefault')
@section('content')
<style>
    .datepicker{
        position:absolute;
    }
</style>
<!-- BEGIN PAGE CONTENT-->

<div class="main_container signup_page">
    <div id="frmStep2">
        <div class="signup_top">
            <div class="signup_bottom">
                <div class="signup_mid">
                    <div class="signup_header">
                        <div class="point ">1
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


                    <div class="head signup_2">
                        <div class="col_left">
                            {{ trans('front/front.your_plan_details') }}
                        </div>
                        
                        <div class="col_right">
                            <span>TOTAL NO OF CHILDREN</span> 
                            <strong><span id="no_of_student_count"><?php echo $paymentArray['no_of_students'] ?></span></strong> 
                        </div>
                        <div class="col_right last">
                            <span>
                                <span>TOTAL AMOUNT</span> 
                                <strong>{{ CURRENCY }}<span id="total_payable_amount"><?php echo $paymentArray['amount'] ?></span></strong> 
                        </div>
                    </div> 
                    <script src="js/braintree-hosted-fields-beta.17.js"></script>
                    <form name="payment-form" id="payment-form" action="/make_payment?user_id=<?php echo $user_id ?>&payment_id=<?php echo $id ?>" method="post">
                        <div class="paywith_Content">
                            <ul class="relative">
                                <li class="mtTabs" >
                                    <div class="mtTabsCont">
                                        <div class="row">
                                            <label>Card Number</label>    
                                            <div class="col1" id="card-number"></div>
                                        </div>
                                        <div class="row">
                                            <label>Cvv No.</label>    
                                            <div class="col1" id="cvv"></div>
                                            <div style="font-size: 12px;">(last 3 digits on the back of your card)</div>
                                        </div>
                                        <div class="row">
                                            <label>Expiration Date</label>    
                                            <div class="col1" id="expiration-date"></div>
                                        </div>
                                        <div class="row">
                                            <span id="error_msg" class="error" style="color:red; margin-bottom: 30px; float: left;width: 100%;"></span><br>
                                            <input type="submit" name="submit" value="Make Payment" class="button next_step2">                   
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </form>
                </div>
            </div> 
        </div>
    </div>
    <span id="frmStepProcessing" style=display:none;">
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
</div>
</div>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
{!! JsValidator::formRequest($JsValidator, '#payment-form'); !!}     
<style>
#card-number,
#cvv,
#expiration-date{ margin:0px; line-height: 21px; padding: 0 18px; border:2px solid #c6ecff; box-shadow: 0 0 1px #c0c0c0; height:56px;margin-top:10px; color: #bababa; font-size: 19px; font-family:'GothamRounded-Medium';  border-radius:10px;}

#card-number.braintree-hosted-fields-invalid,
#cvv.braintree-hosted-fields-invalid,
#expiration-date.braintree-hosted-fields-invalid{ border: 2px solid #D0041D;}
    </style>
<script>

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
              "font-size": "16pt",
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
});

// braintree.setup("<?php //echo $clientToken;    ?>", "<integration>", options);
//var detail =    braintree.setup("<?php //echo $clientToken;    ?>", "custom", {id: "checkout"});
//console.log(detail);

</script>




<script>
    jsMain = new Main();
    var totalNoOfStudents = <?php echo TUTOR_NO_OF_STUDENTS ?>;
    function change_count(act) {

        var parent_sign_up_fee = '';
        var per_student_fee = '';
        var subAmt = '';
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
        var cssLink = document.createElement("link") 
        cssLink.href = "style.css"; 
        cssLink.rel = "stylesheet"; 
        cssLink.type = "text/css"; 
        /*frames['frame1'].document.body.appendChild(cssLink);
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
        });*/
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

    $(".e1").select2();
    $("#next_step1").click(function () {
        var parent_sign_up_fee = '';
        var per_student_fee = '';
        if ($("#addfrm").valid()) {
            $("#js_flash_message").removeClass('space_for_message');
            $("#js_flash_message").hide();
            $(".space_for_message").hide();
            $("#frmStep1").hide();
            $("#frmStep2").show();
            $("#no_of_student_count").html(totalNoOfStudents * 1 + $("#no_of_student").val() * 1);
            var subAmt = (parent_sign_up_fee * 1 + $("#no_of_student").val() * per_student_fee * 1).toFixed(2)
            $("#total_subscription_amount").val(subAmt);
            $("#total_payable_amount").html(subAmt)
            $("#total_amt_id").html(subAmt)
        }
    });
    $(".next_step2").click(function () { 
        $("#error_msg").html('Please wait while your payment is processing. Please do not refresh the page.');
        /*if ($("#payment-form").valid()) {
        $("#js_flash_message").removeClass('space_for_message');
        $("#js_flash_message").hide();
        $(".space_for_message").hide();
        $("#frmStep2").hide();
        $("#navigation_link").hide();
        $(".alert-danger").hide();
        $("#frmStepProcessing").show();*/
         //}
    });
    $("#btn_basicinfo").click(function (e) {
        e.preventDefault();
        $("#frmStep1").show();
        $("#frmStep2").hide();
    });
    $("#no_of_student").change(function (e) {
        $("#no_of_student_count").html(totalNoOfStudents * 1 + $("#no_of_student").val() * 1);
        var parent_sign_up_fee = '';
        var per_student_fee = '';
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
fbq('track', 'AddPaymentInfo');

</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=981414618607013&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<!-- Twitter LEAD Pixel Code -->
<script src="//platform.twitter.com/oct.js" type="text/javascript"></script>
<script type="text/javascript">twttr.conversion.trackPid('nu7jm', { tw_sale_amount: 0, tw_order_quantity: 0 });</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://analytics.twitter.com/i/adsct?txn_id=nu7jm&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0" />
<img height="1" width="1" style="display:none;" alt="" src="//t.co/i/adsct?txn_id=nu7jm&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0" />
</noscript>
<!-- End Twitter LEAD Pixel Code -->

@stop