@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('admin/admin.dashboard') ])
<!-- END PAGE HEADER-->
<!-- BEGIN DASHBOARD STATS -->
<div class="row">

    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">

                    <a href='{{ route('messages.inbox') }}' style="color:white">{{ Auth::user()->newMessagesCount() }}</a>
                </div>
                <div class="desc">
                    {!! trans('admin/admin.new_messages') !!}
                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <a href='{{ route('managerevision.index') }}' style="color:white">{{ $overdueTask['duerevision'] }}</a>
                </div>
                <div class="desc">
                    Overdue Revision Tasks
                </div>
            </div>

        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">
                    <a href='{{ route('managetest.index') }}' style="color:white">{{ $overdueTask['duetest'] }}</a>
                </div>
                <div class="desc">
                    Overdue Test Tasks
                </div>
            </div>

        </div>
    </div>
</div>
    @if(isset($notificationArray[0]))
    <div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="dashboard-stat blue-madison">
            <div class="visual">
                <i class="fa fa-comments"></i>
            </div>
            <div class="details">
                <div class="number">Notifications</div>
                <div class="desc">
                    @if(isset($notificationArray[0]))
                    {{ $notificationArray[0]['description'] }}
                    @else
                    {{ 'Not Avaliable' }}
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
    @endif
<!-- END DASHBOARD STATS -->

<div class="clearfix"></div>

<div class="clearfix"></div>

<!-- styles -->
<div class="row">
    <div class="col-xs-12"> 
        <div class="col-xs-6"> 
            <div id='calendar'></div>
        </div>
        <div class="col-xs-6"> </div>
    </div> 
</div>
<!-- switcher -->

<!--Model popup: Start here-->
<!--For Update calendar event-->
<div class="modal fade myModal" id="myModalEdit" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title"><b>Edit Event: </b></h2>
            </div>
            <div class="modal-body" >
                <label><b>Title: <label style="color:red">*</h5></label></b></label>
                <input type="text" autofocus id="titlebox" style="height:40px;width: 100%"><br><br>
                <label style="height:40px"><b>Start Time: <label style="color:red">*</h5></label></b></label>

                <input type="time" name="usr_time" id="editStartTime" style="height:40px;width: 28%"> 
                <label style="height:40px; padding-left: 10%"><b>End Time: <label style="color:red">*</label></b></label>
                <input type="time" name="usr_time2" id="editEndTime" style="height:40px;width: 28%">
            </div>
            <div class="modal-footer">
                <button type="button" id="updateEvent" class="btn btn-success" data-dismiss="modal">Update</button>
                <button type="button" id="deleteEvent" class="btn btn-danger" data-dismiss="modal">Delete</button>
            </div>
        </div>

    </div>
</div>
<div class="modal fade myModal" id="myModalAdd" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h2 class="modal-title"><b>Add Event:</b></h2>
            </div>
            <div class="modal-body">
                <label><b>Title: <label style="color:red">*</h5></label></b></label>
                <div >
                    <input type="text" id="addTitle" autofocus required style="height:40px;width: 100%"><br><br>
                    <label style="height:40px"><b>Start Time: <label style="color:red">*</h5></label></b></label>
                    <input type="time" name="usr_time" id="addStartTime" style="height:40px;width: 28%"> 
                    <label style="height:40px; padding-left: 10%"><b>End Time: <label style="color:red">*</h5></label></b></label>
                    <input type="time" name="usr_time2" id="addEndTime" style="height:40px;width: 28%">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="addEvent"  class="btn btn-primary" data-dismiss="modal" value="Submit">Add event</button>
            </div>
        </div>

    </div>
</div>
<!--Model popup: End here-->


<!-- call calendar plugin -->


<!-- javascript
<!-- END CONTENT -->
@stop
@section('pagecss')
@stop
@section('pagescripts')
<!-- END PAGE LEVEL PLUGINS -->
<!-- BEGIN PAGE LEVEL SCRIPTS -->
{!! HTML::script('assets/admin/pages/scripts/gcal.js') !!}
<?php
?>
<script>
    var vars = {
        rewardListUrl: "{{ route('teacher.studentrewards') }}",
        studentListUrl: "{{ route('student.listrecord') }}",
        interventionTopicListUrl: "{{ route('task.interventiontopics') }}",
        dashboardtestresultUrl: "{{ route('task.dashboardtestresult') }}",
    };

    var startDate = '';
    var calendarId = '';
    var messages = [];
    $(document).ready(function () {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultDate: moment().format("YYYY-MM-DD"),
            selectable: true,
            selectHelper: true,
            select: function (start, end, startTime, endTime) {
                startDate = start;
                $("#addTitle").val('');
                $("#addStartTime").val('13:00:00')
                $("#addEndTime").val('13:30:00')
                $("#myModalAdd").modal();
                $('#myModal').on('show.bs.modal', function (e) {
                    var title = $("#addTitle").val();
                    var startTime = $("#editStartTime").val();
                    var endTime = $("#addEndTime").val();
                    var eventData = {};
                    if (title) {
                        eventData = {
                            title: title,
                            start: start,
                            end: end,
                            startTime: startTime,
                            endTime: endTime,
                        };
                        //$('#calendar').fullCalendar('renderEvent', eventData, true); // stick
                    }
                    $('#calendar').fullCalendar('unselect');
                });
            },
            editable: true,
            eventClick: function (calEvent, jsEvent, view) {
                var eventState = false;
                $("#myModalEdit").modal();
                $("#titlebox").val(calEvent.title);
                $("#editStartTime").val(calEvent.startTime);
                $("#editEndTime").val(calEvent.endTime);

                $('.myModal').on('hide.bs.modal', function (e) {
                    if (!eventState) {
                        window.location.reload();
                    }
                });
                $('#deleteEvent').on('click', function () {
                    eventState = true;
                    $('#calendar').fullCalendar('removeEvents', calEvent.autoid);
                    if (confirm('Are you sure you want to delete this event?')) {
                        $.ajax({
                            url: "/deleteEvent",
                            type: 'POST',
                            data: {id: calEvent.autoid},
                            success: function (data) {
                                eventState = false;
                                window.location.reload();
                            }
                        });
                    } else {
                        //window.location.reload();
                    }
                });

                $('#updateEvent').on('click', function () {
                    eventState = true;
                    $('#updateEvent').show();
                    if ($("#titlebox").val() === '' || $("#editStartTime").val() === '' || $("#editEndTime").val() === '') {
                        alert('Please fill all of the details.')
                    } else {
                        var start_time = $("#editStartTime").val();
                        var end_time = $("#editEndTime").val();
                        if (start_time >= end_time) {
                            alert('Please enter valid start time and end time.');
                            return false;
                        }
                        else {
                            $.ajax({
                                url: "/updateEvent",
                                type: 'POST',
                                data: {id: calEvent.autoid, title: $("#titlebox").val(), startTime: $("#editStartTime").val(), endTime: $("#editEndTime").val()},
                                success: function (data) {
                                    eventState = false;
                                    window.location.reload();
                                }
                            })
                        }
                    }
                });
            },
            eventLimit: false, // allow "more" link when too many events
            events: <?php echo($activeEvents); ?>
        });
        $("#addEvent").click(function () {
            if ($("#addTitle").val() == '' || $("#addStartTime").val() == '' || $("#addEndTime").val() == '') {
                alert('Please complete all mandatory fields.');
                return false;
            }
            else {
                var start_time = $("#addStartTime").val();
                var end_time = $("#addEndTime").val();
                if (start_time >= end_time) {
                    alert('Please enter valid start time and end time.');
                    return false;
                }
                else {
                    $.ajax({
                        url: "/addEvent",
                        type: 'POST',
                        data: {title: $("#addTitle").val(), date: startDate.format("YYYY-MM-DD"), startTime: $("#addStartTime").val(), endTime: $("#addEndTime").val()},
                        success: function (data) {
                            window.location.reload();
                        }
                    })
                }

            }
        });
        setTimeout(function () {
            $('.fc-button-group').find('.fc-month-button').css('visibility', 'hidden');
            $('.fc-button-group').find('.fc-agendaWeek-button').css('visibility', 'hidden');
            $('.fc-button-group').find('.fc-agendaDay-button').css('visibility', 'hidden');
        })

    });

</script>
<script type="text/javascript">

</script>
{!! HTML::script('js/dashboard.teacher.admin.js') !!}
@stop