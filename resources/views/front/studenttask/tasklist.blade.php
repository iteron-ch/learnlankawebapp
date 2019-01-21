@extends('front.layout._default')

@section('content')
<!-- BEGIN PAGE CONTENT-->
<div class="main_container">
    @include('front.studenttask.leftbar-task')
    <div class="right_section">
        <div class="rtop">&nbsp;</div>
        <div class="rmid">
            <div id="content-l" class="mytask_content">
                @if(count($assignedTask))
                <div class="myTest mytask_listing" id="myTestContent">
                    <ul>
                        @foreach($assignedTask as $task)
                        @if($task['task_type'] == TEST)
                        <li class="mt_list listTest">
                            <div class="accord test_list" data-task='{"id": "{{ $task['id'].'-'.$task['student_test_attempt_id'] }}", "encId" : "{{ encryptParam($task['id']) }}", "subject": "{{ $task['subject'] }}", "student_test_attempt_id": "{{ $task['student_test_attempt_id'] }}", "encstudent_test_attempt_id": "{{ encryptParam($task['student_test_attempt_id']) }}"}'>
                                <div class="test_icon2">&nbsp;</div>
                                <h4>{{ $task['set_name'] }}&nbsp;</h4>
                                <p>{{ $task['student_test_attempt_status'] == COMPLETED ? outputDateFormat($task['student_test_attempt_last_assessment_date']) : ''}}</p>
                                <!--<div class="percentScored below70">60%</div>-->
                                <div class="percentScored below70">&nbsp;</div>
                                <span>&nbsp;</span>
                            </div>
                            <div class="mtContent"><ul id="paperContent-{{ $task['id'].'-'.$task['student_test_attempt_id'] }}"></ul></div>
                        </li>
                        @else

                        <li class="mt_list listRevision" data-url="{{ $status == 'completed' ? route('revision.result',encryptParam($task['studentrevision_id'])) : route('studenttask.revisiondetail',encryptParam($task['id'])) }}">

                            <div class="task_icon1">&nbsp;</div>
                            <h4>{{ $task['strand'] }}</h4>
                            <p>{{ $task['substrand'] }}</p>
                            <p>{{ $task['studentrevision_status'] == COMPLETED ? outputDateFormat($task['studentrevision_completed_at']) : ''}}</p>
                        </li>
							
                        @endif
                        @endforeach
                    </ul>
                </div>
                @else
                <div class="not_completed">{{ $noRecordMsg }}</div>
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
{!! HTML::script('bower_components/nicescrollbar/jquery.nicescroll.min.js') !!}
<script>
    var subjectPaper = <?php echo json_encode($subjectPaper) ?>;
    var paperAttempts = <?php echo json_encode($paperAttempts) ?>;
    jsTest = new Test(paperAttempts, subjectPaper);
    $(document).ready(function() {
        $(".listRevision").click(function(){
            var url = $(this).data('url');
            if(url != ''){
                location.href = $(this).data('url');
            }else{
                return false;
            }
            
        })
        jsTest.testAccordion();
        jsTest.testTab();
        var vars = {
            english: "{{ ENGLISH }}",
            ajaxUrlPaper: "{{ route('studenttask.testpaper') }}/",
            urlAttemptAttempt: "{{ route('studenttask.testpaperattempt','attempt') }}/",
            urlAttemptPaper: "{{ route('studenttask.testpaperattempt','paper') }}/",
            urlAttemptResult: "{{ route('test.result') }}/",
        };
        jsTest.testPaperData(vars);
        $("#content-l").niceScroll({cursorwidth: "10px"});
    });
</script>
@stop 