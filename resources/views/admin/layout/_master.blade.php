<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>SATS</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!--<link rel="shortcut icon" href="favicon.ico"/>-->
<meta content="{{ csrf_token() }}" name="csrf-token"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
{!! HTML::style('//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all') !!}
{!! HTML::style('assets/global/plugins/font-awesome/css/font-awesome.min.css') !!}
{!! HTML::style('assets/global/plugins/simple-line-icons/simple-line-icons.min.css') !!}
{!! HTML::style('assets/global/plugins/bootstrap/css/bootstrap.min.css') !!}
{!! HTML::style('assets/global/plugins/uniform/css/uniform.default.css') !!}
{!! HTML::style('assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css') !!}
{!! HTML::style('assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css') !!}
{!! HTML::style('assets/global/plugins/bootstrap-daterangepicker/daterangepicker-bs3.css') !!}
{!! HTML::style('assets/global/plugins/select2/select2.css') !!}
{!! HTML::style('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css') !!}
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN THEME STYLES -->
{!! HTML::style('assets/global/css/components.css') !!}
{!! HTML::style('assets/global/css/plugins.css') !!}
{!! HTML::style('assets/global/css/jquery-ui.css') !!}
{!! HTML::style('assets/admin/layout/css/layout.css') !!}
{!! HTML::style('assets/admin/layout/css/themes/darkblue.css') !!}
{!! HTML::style('assets/admin/layout/css/custom.css') !!}
    <!-- END THEME STYLES -->
@yield('head')
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
@yield('body')
@yield('styles')
<!-- BEGIN CORE PLUGINS -->
<!--[if lt IE 9]>
{!! HTML::script('assets/global/plugins/respond.min.js') !!}
{!! HTML::script('assets/global/plugins/respond.min.js') !!}
<![endif]-->
{!! HTML::script('assets/global/plugins/jquery.min.js') !!}
{!! HTML::script('assets/global/plugins/jquery-migrate.min.js') !!}
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
{!! HTML::script('assets/global/plugins/jquery-ui/jquery-ui.min.js') !!}
{!! HTML::script('assets/global/plugins/bootstrap/js/bootstrap.min.js') !!}
{!! HTML::script('assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js') !!}
{!! HTML::script('assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js') !!}
{!! HTML::script('assets/global/plugins/jquery.blockui.min.js') !!}
{!! HTML::script('assets/global/plugins/jquery.cokie.min.js') !!}
{!! HTML::script('assets/global/plugins/uniform/jquery.uniform.min.js') !!}
{!! HTML::script('assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js') !!}
{!! HTML::script('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') !!}
{!! HTML::script('vendor/jsvalidation/js/jsvalidation.js') !!}
{!! HTML::script('assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js') !!}
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
{!! HTML::script('assets/global/plugins/select2/select2.min.js') !!}

{!! HTML::script('assets/global/plugins/bootstrap-daterangepicker/moment.min.js') !!}
{!! HTML::script('assets/global/plugins/bootstrap-daterangepicker/daterangepicker.js') !!}
{!! HTML::script('js/main.js') !!}
<!-- END PAGE LEVEL PLUGINS -->
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
</script>
<style>
.usabilla_live_button_container{
    bottom: 20px !important;
    top: auto !important;
}
</style>
@yield('scripts')
@if(!isset($footer_layout))
<script type="text/javascript">/*{literal}<![CDATA[*/window.lightningjs||function(c){function g(b,d){d&&(d+=(/\?/.test(d)?"&":"?")+"lv=1");c[b]||function(){var i=window,h=document,j=b,g=h.location.protocol,l="load",k=0;(function(){function b(){a.P(l);a.w=1;c[j]("_load")}c[j]=function(){function m(){m.id=e;return c[j].apply(m,arguments)}var b,e=++k;b=this&&this!=i?this.id||0:0;(a.s=a.s||[]).push([e,b,arguments]);m.then=function(b,c,h){var d=a.fh[e]=a.fh[e]||[],j=a.eh[e]=a.eh[e]||[],f=a.ph[e]=a.ph[e]||[];b&&d.push(b);c&&j.push(c);h&&f.push(h);return m};return m};var a=c[j]._={};a.fh={};a.eh={};a.ph={};a.l=d?d.replace(/^\/\//,(g=="https:"?g:"http:")+"//"):d;a.p={0:+new Date};a.P=function(b){a.p[b]=new Date-a.p[0]};a.w&&b();i.addEventListener?i.addEventListener(l,b,!1):i.attachEvent("on"+l,b);var q=function(){function b(){return["<head></head><",c,' onload="var d=',n,";d.getElementsByTagName('head')[0].",d,"(d.",g,"('script')).",i,"='",a.l,"'\"></",c,">"].join("")}var c="body",e=h[c];if(!e)return setTimeout(q,100);a.P(1);var d="appendChild",g="createElement",i="src",k=h[g]("div"),l=k[d](h[g]("div")),f=h[g]("iframe"),n="document",p;k.style.display="none";e.insertBefore(k,e.firstChild).id=o+"-"+j;f.frameBorder="0";f.id=o+"-frame-"+j;/MSIE[ ]+6/.test(navigator.userAgent)&&(f[i]="javascript:false");f.allowTransparency="true";l[d](f);try{f.contentWindow[n].open()}catch(s){a.domain=h.domain,p="javascript:var d="+n+".open();d.domain='"+h.domain+"';",f[i]=p+"void(0);"}try{var r=f.contentWindow[n];r.write(b());r.close()}catch(t){f[i]=p+'d.write("'+b().replace(/"/g,String.fromCharCode(92)+'"')+'");d.close();'}a.P(2)};a.l&&setTimeout(q,0)})()}();c[b].lv="1";return c[b]}var o="lightningjs",k=window[o]=g(o);k.require=g;k.modules=c}({});
window.usabilla_live = lightningjs.require("usabilla_live", "//w.usabilla.com/6262a8593914.js");
/*]]>{/literal}*/</script>
@endif
</body>
<!-- END BODY -->
</html>

