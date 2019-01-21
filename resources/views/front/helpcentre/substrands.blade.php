@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.helpcentre.leftbar-helpcentre', ['subject' => $subject]) 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid pt40">
            <h1>
                <a href="{{ $trait['back_link'] }}" class="back">&nbsp;</a>
                <div class="heading root_sign">{{ $trait['trait_1'] }}</div>
            </h1>
            <div id="content-l" class="revision_content">
                <div class="rev_ques">
                    <ul>
                        @foreach($revisions['assigned_revision'] as $revision)
                        <li class="revSelection">
                            <a href="{{ route('helpcentre.detail',[encryptParam($revisions['strand']['strandId']),encryptParam($revision['substrand_id'])]) }}">{{ $revision['substrand']}}</a>
                            <span>&nbsp;</span>
                        </li>
                        @endforeach
                        
                    </ul>
                </div>
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
<!-- END PAGE CONTENT-->
@stop