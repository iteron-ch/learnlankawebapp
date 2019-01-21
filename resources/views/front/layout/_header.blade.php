@if($errors->any())
<div class="wrapper error_top space_for_message">
    @foreach($errors->all() as $error)
    <div class="main_container">{{ $error }}</div>
    @endforeach
</div>
@endif    
@if(session()->has('ok'))
<div class="wrapper success space_for_message" >
    <div class="main_container">{{ session('ok') }}</div>
</div>
@endif
@if(session()->has('error'))
<div class="wrapper error_top space_for_message" >
    <div class="main_container">{{ session('error') }}</div>
</div>
@endif
<div class="wrapper error_top" id="js_flash_message" style="display:none;">
    <div class="main_container">{{ trans('admin/admin.formvalidation_error') }}</div>
</div>

<div class="wrapper success" id="js_flash_message_success" style="display:none;">
    <div class="main_container">{{ trans('admin/admin.formvalidation_error') }}</div>
</div>
<div class="wrapper header">
    <div class="main_container">
        @if(Request::segment(1)!='send-enquiry' && Request::segment(1)!='enquiryconfirm')
            <a href="{{ SITE_URL }}" class="logo"></a>
        @else
            <a href="{{ WP_URL }}" class="logo"></a>
        @endif    
        <div class="right_col">
            @if(!empty(session()->get('user')['image']))
            <a href="" class="profile_pic">
                <img src="{{ SITE_URL }}/userimg/{{ session()->get('user')['image'] }}?size=small" alt="">
            </a>
            @endif

            @if(auth()->user())
            <div class="text">
                <label for="">{{ trans('front/front.welcome') }} {{ trim(session()->get('user')['first_name'].' '.session()->get('user')['last_name']) }}!</label>
                <a href="{!! url('auth/logout') !!}" class="signin">{{ trans('front/front.log_out') }}</a>
            </div>
            @else
            @if(Request::segment(1)!='send-enquiry' && Request::segment(1)!='enquiryconfirm')
            <div class="text">
                <label for=""></label>
                <a href="/login" class="signin">{{ trans('front/front.log_in') }}</a>
            </div>
            @endif
            @endif
        </div>
    </div>
</div>
