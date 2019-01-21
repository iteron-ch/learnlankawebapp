@extends('front.layout._signupdefault')
@section('content')
<div class="main_container signup_page">
    {!! Form::open(['route' => ['enquiry.store'],'files' => true, 'method' => 'post', 'id' =>'addfrm']) !!}
    <div class="signup_top">
        <div class="signup_bottom">
            <div class="signup_mid">
                <div class="row">
                    <div class="row">
                        <div class="col1">
                            <label for="" class="auto">{{ trans('front/front.you_are') }}</label>
                            <input id="teacher" class="css-radio" type="radio" name="user_type" value="Teacher"  checked="checked">
                            <label for="teacher" class="css-label">{{ trans('front/front.teacher') }}</label>
                            <input id="parent" class="css-radio" type="radio" name="user_type" value='Parent' >
                            <label for="parent" class="css-label">{{ trans('front/front.parent') }}</label>

                            <input id="tutor" class="css-radio" type="radio" name="user_type" value="Tutor" >
                            <label for="tutor" class="css-label">{{ trans('front/front.tutor') }}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col3">
                        {!! Form::labelControl('title',trans('front/front.title'))  !!}
                        {!! Form::select('title', $title,null, ['id' => 'title', 'class' => 'e1']) !!}<span class="red">*</span>
                    </div>
                    <div class="col3">
                        {!! Form::labelControl('first_name',trans('front/front.first_name'))  !!}
                        {!! Form::text('first_name',null,['class'=>'input_text']  )  !!}<span class="red">*</span>
                    </div>
                    <div class="col3">
                        {!! Form::labelControl('last_name',trans('front/front.last_name'))  !!}
                        {!! Form::text('last_name',null,['class'=>'input_text']  )  !!}<span class="red">*</span>
                    </div>


                </div>

                <div class="row"> 
                    <div class="col3">
                        {!! Form::labelControl('email',trans('front/front.email'))  !!}
                        {!! Form::text('email',null,['class'=>'input_text']  )  !!}<span class="red">*</span>
                    </div>
                    <div class="col3">
                        {!! Form::labelControl('contact_no',trans('front/front.contact_number'))  !!}
                        {!! Form::text('contact_no',null,['class'=>'input_text','id'=>'contact_no','maxlength' => '20']  )  !!}<span class="red">*</span>
                    </div>
                    <div class="col3" id="job_role_div">
                        {!! Form::labelControl('job_role',trans('front/front.job_role'))  !!}
                        {!! Form::text('job_role',null,['class'=>'input_text','id'=>'job_role']  )  !!}  
                    </div>                    
                    <div class="col3" id="county_div">
                        {!! Form::labelControl('county',trans('front/front.county'))  !!}
                        {!! Form::text('county',null,['class'=>'input_text','id'=>'county','maxlength' => '100']  )  !!}
                    </div>




                </div>

                <div class="row">
                    <div class="col3" id="city_div" style="display:;">
                        {!! Form::labelControl('cities',trans('front/front.cities'))  !!}
                        {!! Form::text('cities',null,['class'=>'input_text','id'=>'cities','maxlength' => '100']  )  !!}   
                    </div>
                    <div class="col3" id="postalcode_div" style="display:none;">
                        {!! Form::labelControl('postal_code',trans('front/front.postal_code'))  !!}
                        {!! Form::text('postal_code',null,['class'=>'input_text','id'=>'postal_code','maxlength' => '8']  )  !!}  
                    </div>
                    <div class="col3" id="school_div" style="display:none;">
                        {!! Form::labelControl('school',trans('front/enquiry.school'))  !!}
                        {!! Form::text('school',null,['class'=>'input_text','id'=>'school','maxlength' => '100']  )  !!}  
                    </div>


                </div>

                <div class="row">
                    <div class="col3">
                        {!! Form::labelControl('how_hear',trans('front/front.how_hear'))  !!}
                        {!! Form::select('how_hear', $how_hear,null, ['id' => 'how_hear', 'class' => 'e1']) !!}<span class="red">*</span>
                    </div>

                    <div class="col3" id="other-how_hear" style="display:none">
                        <label>&nbsp;
                        </label>
                        {!! Form::text('how_hear_other',null,['id' => 'how_hear_other','class'=>'input_text']) !!}
                    </div>

                </div>

                <div class="row button_row">
                    <div class="col3">
                        {!! Form::button('Submit', array('type' => 'submit', 'id'=>'next_step2','class' => 'button')) !!}

                    </div>
                </div>

            </div>
        </div>
    </div>
    {!! Form::close() !!}
</div>

<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
{!! JsValidator::formRequest($JsValidator, '#addfrm'); !!}     
<script>
    jsMain = new Main();
            $(document).ready(function () {
    $(".e1").select2();
            jsMain.toggleOther(['how_hear', 'how_hear_other'], {{ OTHER_VALUE }});
            $("input:radio[name=user_type]").click(function () {
    var value = $(this).val();
            if (value == 'Parent') {
    $("#postalcode_div").hide();
            $("#school_div").hide();
            $("#county_div").show();
            $("#city_div").show();
            $("#postal_code").val('');
            $("#school").val('');
            $("#job_role").val('');
            $("#job_role_div").hide();
    }
    else if (value == 'Teacher') {
    $("#postalcode_div").show();
            $("#school_div").show();
            $("#city_div").show();
            $("#county_div").hide();
            $("#county").val('');
            $("#job_role").val('');
            $("#job_role_div").show();
    }
    else if (value == 'Tutor') {
    $("#postalcode_div").hide();
            $("#school_div").hide();
            $("#city_div").show();
            $("#county_div").show();
            $("#county,#school,#city").val('');
            $("#postal_code").val('');
            $("#job_role").val('');
            $("#job_role_div").hide();
    }
    });
            $("#teacher").click();
    });
</script>
<!-- GA Pixel Code -->
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

</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=981414618607013&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<!-- Twitter SITE VISIT Pixel Code -->
<script src="//platform.twitter.com/oct.js" type="text/javascript"></script>
<script type="text/javascript">twttr.conversion.trackPid('nu7je', { tw_sale_amount: 0, tw_order_quantity: 0 });</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://analytics.twitter.com/i/adsct?txn_id=nu7je&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0" />
<img height="1" width="1" style="display:none;" alt="" src="//t.co/i/adsct?txn_id=nu7je&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0" />
</noscript>
<!-- End Twitter SITE VISIT Pixel Code -->
@stop