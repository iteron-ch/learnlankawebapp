@extends('front.layout._signupdefault')
@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container signup_page">
    <div class="signup_top">
        <div class="signup_bottom">
            <div class="signup_mid">

                <div class="signup_success">
                    <p class="thanks_message">
                        Thank you for registering for a free demo account with SATs Companion.<br>
                        We will be in touch shortly with your login details.<br>
                        If you have any queries, please do not hesitate to contact our team at <a href="mailto:ask@satscompanion.com">ask@satscompanion.com</a></br>
                        <br><br>
                        <a href="{{ WP_URL }}" class="thanks_message">Back to SATs Companion Home Page</a>
                    </p>
                    <a href="{{ WP_URL }}" class="logo_2"></a>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- END PAGE CONTENT-->
@stop
@section('scripts')
{!! JsValidator::formRequest('App\Http\Requests\Auth\LoginRequest', '#frmLogin'); !!}   

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
fbq('track', 'Lead');

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