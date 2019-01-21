@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.studenttask.leftbar-test') 
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div id="content-l" class="mytask_content">
                @if(count($assignedTest))
                <div class="myTest">
                    <ul>
                        @foreach($assignedTest as $test)
                        <li class="mt_list listTest">
                            <div class="accord test_list" data-task='{"id": "{{ $test['id'] }}", "encId" : "{{ encryptParam($test['id']) }}", "subject": "{{ $test['subject'] }}", "student_test_attempt_id": "{{ $test['student_test_attempt_id'] }}", "encstudent_test_attempt_id": "{{ encryptParam($test['student_test_attempt_id']) }}"}'>
                                <div class="test_icon2">&nbsp;</div>
                                <h4>{{ $test['set_name'] }}</h4>
                                <p>8 Jul, 2015</p>
                                <div class="percentScored below70">60%</div>
                                <span>&nbsp;</span>
                            </div>
                            <div class="mtContent"><ul id="paperContent-{{ $test['id'] }}"></ul></div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @else
                <div>{{ trans('front/front.no_test_assign') }}</div>
                @endif
            </div>
        </div>
        <div class="rbot">&nbsp;</div>
    </div>
    <div class="clear_fix"></div>
</div>
<!-- END PAGE CONTENT-->
@stop
@section('scripts')
{!! HTML::script('js/test.js') !!}	 
<script>
    var subjectPaper = <?php echo json_encode($subjectPaper) ?>;
    var paperAttempts = <?php echo json_encode($paperAttempts) ?>;
    jsTest = new Test(paperAttempts, subjectPaper);
    $(document).ready(function() {
        jsTest.testAccordion();
        jsTest.testTab();
        var vars = {
            english: "{{ ENGLISH }}",
            ajaxUrlPaper: "{{ route('studenttask.testpaper') }}/",
            ajaxUrlAttempt: "{{ route('studenttask.testpaperattempt','paper') }}/",
        };
        jsTest.testPaperData(vars);
    });
</script>
@stop 