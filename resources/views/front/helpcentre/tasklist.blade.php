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
                <div class="myTest" id="myTestContent">
                    <ul>
                        @foreach($assignedTask as $task)
                        @if($task['task_type'] == TEST)
                        <li class="mt_list listTest">
                            <div class="accord test_list" data-task='{"id": "{{ $task['id'] }}", "encId" : "{{ encryptParam($task['id']) }}", "subject": "{{ $task['subject'] }}", "student_test_attempt_id": "{{ $task['student_test_attempt_id'] }}", "encstudent_test_attempt_id": "{{ encryptParam($task['student_test_attempt_id']) }}"}'>
                                <div class="test_icon2">&nbsp;</div>
                                <h4>{{ $task['set_name'] }}</h4>
                                <p>8 Jul, 2015</p>
                                <div class="percentScored below70">60%</div>
                                <span>&nbsp;</span>
                            </div>
                            <div class="mtContent"><ul id="paperContent-{{ $task['id'] }}"></ul></div>
                        </li>
                        @else
                        <li class="mt_list listRevision" data-url="{{ route('studenttask.revisiondetail',encryptParam($task['id'])) }}">
                            <div class="task_icon1">&nbsp;</div>
                            <h4>{{ $task['strand'] }}</h4>
                            <p>{{ $task['substrand'] }}</p>
                            <span>&nbsp;</span>
                        </li>
                        @endif
                        @endforeach
                    </ul>
                </div>
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
        $(".listRevision").click(function(){
            location.href = $(this).data('url');
        })
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