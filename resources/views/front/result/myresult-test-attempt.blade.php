<div class="threeCols">
    @foreach($viewdata as $value)
    <div class="tCols">
        <div class="tpTitle">{{ $value['paper_num'] }}</div>
        <div class="tScore">Raw score:
            <span class="{{ $value['fontCls'] }}">{{ $value['mark_obtain'] }}/{{ $value['total_marks'] }}</span>
        </div>
        <div class="tScore">Percentage:
            <span class="{{ $value['fontCls'] }}">{{ round($value['percentage_obtained']) }}%</span>
        </div>
    </div>
    @endforeach
</div>