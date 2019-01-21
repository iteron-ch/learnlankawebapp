<h3 class="page-title">{{ $title }}</h3>
<div class="page-bar">
        <ul class="page-breadcrumb">
                <li>
                        <i class="fa fa-home"></i>
                        <a href="{!! route('dashboard') !!}">{{ trans('admin/admin.dashboard') }}</a>
                        @if(!empty($trait_1))
                            <i class="fa fa-angle-right"></i>
                        @endif
                </li>
                @if(!empty($trait_1))
                    <li>
                        @if(!empty($trait_1_link))
                            <a href="{!! $trait_1_link !!}">{{ $trait_1 }}</a>
                        @else
                            {{ $trait_1 }}
                        @endif
                    @if(!empty($trait_2))
                        <i class="fa fa-angle-right"></i>
                    @endif
                    </li>
                @endif
                @if(!empty($trait_2))
                    <li>{{ $trait_2 }}</li>
                @endif
        </ul>
</div>
@if(session()->has('ok'))
    @include('front.partials.error', ['type' => 'success', 'message' => session('ok')])
@endif
@if(session()->has('error'))
    @include('front.partials.error', ['type' => 'danger', 'message' => session('error')])
@endif