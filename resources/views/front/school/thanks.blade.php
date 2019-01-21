@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">

    <div class="portlet-body form">
        <!-- BEGIN FORM-->
        {!! Form::open(['route' => ['frontschool.store'], 'method' => 'post', 'files' => true, 'class' => 'form-horizontal','id' =>'schoolfrm']) !!}
        <div class="form-body">
            @include('front.partials.form_error')

            <div class="form-group">
                <div class="col-md-4">
                    <div class="radio-list" data-error-container="#form_2_membership_error">
                        <label>
                            <a href="javascript:void(0)">Basic Information</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="javascript:void(0)">Subscription</a></label>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <a href="javascript:void(0)">Complete</a></label>
                    </div>
                </div>
            </div>
            <span id="frmStep1">
                <div class="form-group">
                    Thank you for subscribing to SATS.<br> Please use your username and password to login. 
                </div>
                                     
            </span> 
            {!! Form::close() !!}
            <!-- END FORM-->
        </div>
        <!-- END VALIDATION STATES-->
    </div>
    <div class="clear_fix"></div>
</div>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts') 
<!-- BEGIN PAGE LEVEL PLUGINS -->

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
fbq('track', 'CompleteRegistration');

</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=981414618607013&ev=PageView&noscript=1"
/></noscript>
<!-- End Facebook Pixel Code -->

<!-- Twitter COMPLETE REGISTRATION Pixel Code -->
<script src="//platform.twitter.com/oct.js" type="text/javascript"></script>
<script type="text/javascript">twttr.conversion.trackPid('nu7ko', { tw_sale_amount: 0, tw_order_quantity: 0 });</script>
<noscript>
<img height="1" width="1" style="display:none;" alt="" src="https://analytics.twitter.com/i/adsct?txn_id=nu7ko&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0" />
<img height="1" width="1" style="display:none;" alt="" src="//t.co/i/adsct?txn_id=nu7ko&p_id=Twitter&tw_sale_amount=0&tw_order_quantity=0" />
</noscript> 
<!-- End Twitter COMPLETE REGISTRATION Pixel Code -->
@stop