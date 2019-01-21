<div class="top-menu">
    <ul class="nav navbar-nav pull-right">
        <li class="dropdown dropdown-user">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                @if(!empty(@auth()->user()->image))
                <img src="{{ SITE_URL }}/userimg/{{ @auth()->user()->image }}?size=small" alt="" class="img-circle">
                @endif
                
                @if(!empty(@auth()->user()->username))
                <span class="username username-hide-on-mobile">
                    {{ @auth()->user()->username }} </span>
                @endif
                <i class="fa fa-angle-down"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-default">
                <li>
                    <a href="{!! url('auth/logout') !!}">
                        <i class="icon-key"></i> Log Out </a>
                </li>
            </ul>
        </li>
    </ul>
</div>