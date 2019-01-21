<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en" class="no-js">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<title>Sats</title>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!--<link rel="shortcut icon" href="favicon.ico"/>-->
<meta content="{{ csrf_token() }}" name="csrf-token"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
{!! HTML::style('//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all') !!}
{!! HTML::style('css/stylesheet.css') !!}
{!! HTML::style('css/jquery.mCustomScrollbar.css') !!}

<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN THEME STYLES -->
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
<!-- IMPORTANT! Load jquery-ui.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip -->
{!! HTML::script('assets/global/plugins/jquery-validation/js/jquery.validate.min.js') !!}
{!! HTML::script('vendor/jsvalidation/js/jsvalidation.js') !!}
<!-- END CORE PLUGINS -->
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- END PAGE LEVEL PLUGINS -->
<script>
    $.ajaxSetup({
        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
    });
</script>
@yield('scripts')
</body>
<!-- END BODY -->
</html>

