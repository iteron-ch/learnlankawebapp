@extends('front.layout._signupdefault')
@section('content')
<style>
    .datepicker{
        position:absolute;
    }
</style>
<!-- BEGIN PAGE CONTENT-->

<div class="main_container signup_page">

    <form method="" action="" id="addfrm">
    <span id="frmStep1">

        <div class="signup_top">
            <div class="signup_bottom" style="margin-bottom: 30px;">
                <div class="signup_mid" style="min-height: 0;">
                    

                    <div class="row">
                        <div class="col1">
                            <label for="" class="auto">
                                    
                                <h3 style="text-align: center">
                                    A verification link has been sent to your email account, {{$user->email}}
                                </h3>

                                <div class="signup_header" style="top: 0">
                                </div>
                                <br>
                                    Please click on the link that has been sent to your email account to verify your
                                    email and activate your account. <br>
                                    Click resend if you haven't received any mail.
                            </label>
                            
                        </div>
                    </div>
                    <div class="row">
                        <div class="col3">
                            
                        </div>
                        
                    </div>

                    <div class="row button_row">
                        <div class="col3">
                            <a href="{{ URL::to('register/resend/' . $user->confirmation_code) }}">
                            <button type="submit" class="button" id="next_step1">
                                Resend
                            </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </span>
    </form>
    
</div>
<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
{!! JsValidator::formRequest($JsValidator, '#addfrm'); !!}     
<script>
    
    
    

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