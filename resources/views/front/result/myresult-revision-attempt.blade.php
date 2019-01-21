<div class="threeCols">
    @foreach($viewdata as $value)
    <div class="tCols">
        <div class="tScore">Raw score:
            <span class="tsRed">{{ $value['mark_obtain'] }}/{{ $value['total_marks'] }}</span>
        </div>
        <div class="tScore">Percentage:
            <span class="tsRed">{{ $value['percentage_obtained'] }}%</span>
        </div>
    </div>
    @endforeach
</div>