@if(isset($studentStrandResultList['good']))
<div class="success_sec green_bg_box">
    <div class="ssTitle">Great job! You've understood these topics</div>
    <ol>
        @foreach($studentStrandResultList['good'] as $strandGood)
        <li class="list_Box darkGreen_bg_box"><span>1.</span> <span>{{ $strandGood}}</span></li>
        @endforeach
    </ol>
</div>
@endif
@if(isset($studentStrandResultList['poor']))
<div class="success_sec red_bg_box">
    <div class="ssTitle">You need to work on the following topics</div>
    <ol>
        @foreach($studentStrandResultList['poor'] as $strandPoor)
        <li class="list_Box darkRed_bg_box"><span>1.</span> <span>{{ $strandPoor}}</span></li>
        @endforeach
    </ol>
</div>
@endif