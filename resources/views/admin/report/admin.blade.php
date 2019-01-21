@extends('admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<!-- BEGIN PAGE HEADER-->
@include('admin.layout._page_header', ['title' => trans('Reports'), 'trait_1' => trans('Reports')])
<!-- END PAGE HEADER-->
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->

<div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box green">

            <div class="portlet-title">
                <div class="caption">
                    <i class="fa"></i>Report Filter
                </div>
                <!--<div class="actions">
                    <a class="btn btn-default btn-sm" href="http://local.londonce.com/school/create">
                        <i class="fa fa-plus"></i> Add School </a>

                </div>-->
            </div>
            <div class="portlet-body form-body">
                <form name="report-frm" id="report-frm" action="/report/classandstudent">
                    <table width="100%" cellspacing="3" cellpadding="3">
                        <tr class="form-group">
                            <td width="20%" style="padding-right: 5px; padding-top: 10px; padding-left: 5px;">
                                <select class="form-control select2me" name="report" id="report">
                                    <option value="">Select Report</option>
                                    <?php if (session()->get('user')['user_type'] == TEACHER) { ?>
                                        <option value="2">Class</option>
                                        <option value="3">Pupil</option>

                                    <?php } elseif (session()->get('user')['user_type'] == TUTOR) { ?>
                                        <option value="3">Pupil</option>
                                    <?php } else { ?>
                                        <option value="1">School</option>
                                        <option value="2">Class</option>
                                        <option value="3">Pupil</option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td width="20%" style="padding-right: 5px; padding-top: 10px; padding-left: 5px;">
                                <select class="form-control select2me" name="reporttype" id="reporttype">
                                    <option value="">Select Report Type</option>
                                </select>
                            </td>
                            <?php
                            if (session()->get('user')['user_type'] != TUTOR) {
                                ?>
                            <td width="20%" style="padding-right: 5px; padding-top: 10px; padding-left: 5px;">
                                <select class="form-control select2me" name="school" id="school">
                                    <option value="">Select School</option>
                                    <?php
                                    
                                        if (!empty($schoolList)) {
                                            foreach ($schoolList as $key => $val) {
                                                ?>  
                                                <option value="<?php echo $val['id']; ?>"><?php echo $val['school_name']; ?></option>        
                                                <?php
                                            }
                                        }
                                    
                                    ?>
                                </select>    
                            </td>
                            <?php  
                            }
                            ?>
                            
                            <td width="20%" style="padding-right: 5px; padding-top: 10px; padding-left: 5px;" id="class_select_id">
                                <select class="form-control select2me" name="class" id="class">
                                    <option value="">Select Class</option>
                                </select>
                            </td>
                            <td width="20%" style="padding-right: 5px; padding-top: 10px; padding-left: 5px;" id="student_select_id">
                                <select class="form-control select2me" name="student" id="student">
                                    <option value="">Select Pupil</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6" style="padding-right: 5px; padding-top: 10px; padding-left: 5px;" align="right">
                                <input type="button" class="btn green" name="generatereport" id="generatereport" value="Generate Report" onclick="loadIframe()">
                                <input type="button" class="btn default" name="reset" id="reset" value="Reset Report" onclick="resetreport()">

                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12"> 
        <iframe name="report-frame" id="report-frame" style="width:100%; border:0px;"  ></iframe> 
    </div>
</div>
</div>

<!-- END EXAMPLE TABLE PORTLET    onload="resizeIframe(this)"  -->
</div>
<!-- END PAGE CONTENT-->
@stop

@section('pagescripts')
<!-- BEGIN PAGE LEVEL PLUGINS -->
<!-- END PAGE LEVEL SCRIPTS -->

<script>
    $(function () {

        var iFrames = $('iframe');

        function iResize() {

            for (var i = 0, j = iFrames.length; i < j; i++) {
                iFrames[i].style.height = (iFrames[i].contentWindow.document.body.offsetHeight + 100) +'px';
            }
        }

        if ($.browser.safari || $.browser.opera) {

            iFrames.load(function () {
                setTimeout(iResize, 0);
            });

            for (var i = 0, j = iFrames.length; i < j; i++) {
                var iSource = iFrames[i].src;
                iFrames[i].src = '';
                iFrames[i].src = iSource;
            }

        } else {
            iFrames.load(function () {
                this.style.height = (this.contentWindow.document.body.offsetHeight + 100)+'px';
            });
        }

    });

    // function resizeIframe(obj) {

    // obj.style.height = (obj.contentWindow.document.body.scrollHeight)+ 'px';
    // }
    function resetreport() {
        $("#class_select_id").show();
        $("#class").show();
        $("#student_select_id").show();
        $("#student").show();
        $("#class").prop("disabled", false);
        $("#student").prop("disabled", false);

        $('#school').prop('selectedIndex', 0);
        $('#report').prop('selectedIndex', 0);
        $('#reporttype').empty();
        $('#reporttype')
                .append($("<option></option>")
                        .attr("value", "")
                        .text("Select Report Type"));
        $('#class').empty();
        $('#class')
                .append($("<option></option>")
                        .attr("value", "")
                        .text("Select Class"));
        $('#student').empty();
        $('#student')
                .append($("<option></option>")
                        .attr("value", "")
                        .text("Select Pupil"));
        $("#report").val('').select2();        
        $("#reporttype").val('').select2();        
        $("#school").val('').select2();        
        $("#class").val('').select2();        
        var $iframe = $('#report-frame');
        if ($iframe.length) {
            $iframe.attr('src', 'about:blank');
            return false;
        }

    }
    function loadIframe() {
        var url = "";
        if ($('#report').val() == 1) {
            var schoolId = $('#school').val();
            if (schoolId == "") {
                alert("Please select school");
                return false;
            }
            url = '/adminreport/schooloverview?id=' + schoolId + '&report=dashboard&tab=math&layout=iframe'
        } else if ($('#report').val() == 2) {
            var schoolId = $('#school').val();
            var classId = $('#class').val();
            if (schoolId == "") {
                alert("Please select school");
                return false;
            }
            if (classId == "") {
                alert("Please select class");
                return false;
            }
            if ($('#reporttype').val() == 1) {
                url = '/adminreport/classreport?schoolId='+schoolId+'&classId=' + classId + '&report=dashboard&tab=math&layout=iframe'
            } else if ($('#reporttype').val() == 2) {
                url = '/adminreport/classgap?schoolId='+schoolId+'&classId=' + classId + '&layout=iframe';
            }
        } else if ($('#report').val() == 3) {
            var schoolId = $('#school').val();
            var studentId = $('#student').val();
<?php if (session()->get('user')['user_type'] != TUTOR) { ?>
                if (schoolId == "") {
                    alert("Please select school");
                    return false;
                }
<?php } ?>
            if (studentId == "") {
                alert("Please select pupil");
                return false;
            }
            url = '/adminreport/studenttest?id=' + studentId + '&report=test&tab=math&layout=iframe'

        }

        var $iframe = $('#report-frame');
        if ($iframe.length) {
            $iframe.attr('src', url);
            return false;
        }
        return true;
    }

    $('#generatereport').click(function () {
        if ($('#report').val() == "") {
            alert('Please select report')
        }
        if ($('#reporttype').val() == "") {
            alert('Please select reporttype')
        }

    })
    $('#reporttype').change(function () {
        $('#class').empty();
        $('#class')
                .append($("<option></option>")
                        .attr("value", "")
                        .text("Select Class")).select2();
        $('#student').empty();
        $('#student')
                .append($("<option></option>")
                        .attr("value", "")
                        .text("Select Pupil")).select2();

        $('#school').prop('selectedIndex', 0).select2();
        $('#class').prop('selectedIndex', 0).select2();
        $('#student').prop('selectedIndex', 0).select2();
    });
    $('#report').change(function () {
        $('#school').prop('selectedIndex', 0).select2();
        $("#class_select_id").show();
        $('#class').prop('selectedIndex', 0);$("#student_select_id").show();
        $('#student').prop('selectedIndex', 0).select2();
        $('#reporttype').empty();
        $("#class").prop("disabled", false);
        $("#student").prop("disabled", false);
        
        $("#class").show();
        
        
        $("#student").show();

        if ($('#report').val() == 1) {
            var optionArray = new Array();
            optionArray[0] = 'Select Report Type';
            optionArray[1] = 'School Overview report';


            $("#class").prop("disabled", true);
            $("#student").prop("disabled", true);
            $("#class").hide();
            $("#class_select_id").hide();
            $("#student_select_id").hide();
            $("#student").hide();

        } else if ($('#report').val() == 2) {
            var optionArray = new Array();
            optionArray[0] = 'Select Report Type';
            optionArray[1] = 'Class Overview report';
            optionArray[2] = 'Class Gap report';
 $("#student_select_id").hide();
            $("#student").prop("disabled", true);
            $("#student").hide();
           

        } else if ($('#report').val() == 3) {
            var optionArray = new Array();
            optionArray[0] = 'Select Report Type';
            optionArray[1] = 'Pupil Overview report';
 $("#class_select_id").hide();
            $("#class").prop("disabled", true);
            $("#class").hide();
           
        }
        $.each(optionArray, function (key, value) {
            (key != 0) ? key : "";
            $('#reporttype')
                    .append($("<option></option>")
                            .attr("value", key)
                            .text(value)).select2();
        });
    })
<?php if (session()->get('user')['user_type'] == TUTOR) { ?>
        $('#reporttype').change(function () {
            if ($('#report').val() == 3) {
                $.ajax({
                    url: $("#report-frm").attr("action"),
                    type: 'POST',
                    data: $("#report-frm").serialize(),
                    dataType: "json",
                    mimeType: "multipart/form-data",
                    async: false,
                    success: function (data) {
                        if (data.status == 'success') {
                            if (data.postDetail.report == 2) {
                                $('#class').empty();
                                $('#class')
                                        .append($("<option></option>")
                                                .attr("value", "")
                                                .text("Select Class"));
                                if (data.dataArray.length > 0) {
                                    $.each(data.dataArray, function (key, value) {
                                        $('#class')
                                                .append($("<option></option>")
                                                        .attr("value", value.id)
                                                        .text(value.class_name));
                                    });
                                }
                            } else {
                                $('#student').empty();
                                $('#student')
                                        .append($("<option></option>")
                                                .attr("value", "")
                                                .text("Select Pupil")).select2();
                                if (data.dataArray.length > 0) {
                                    $.each(data.dataArray, function (key, value) {
                                        $('#student')
                                                .append($("<option></option>")
                                                        .attr("value", value.id)
                                                        .text(value.first_name + ' ' + value.last_name));
                                    });
                                }
                            }
                        }
                    },
                });
            }
        })
<?php } else { ?>
        $('#school').change(function () {
            if ($('#report').val() != 1) {
                $.ajax({
                    url: $("#report-frm").attr("action"),
                    type: 'POST',
                    data: $("#report-frm").serialize(),
                    dataType: "json",
                    mimeType: "multipart/form-data",
                    async: false,
                    success: function (data) {
                        if (data.status == 'success') {
                            if (data.postDetail.report == 2) {
                                $('#class').empty();
                                $('#class')
                                        .append($("<option></option>")
                                                .attr("value", "")
                                                .text("Select Class")).select2();
                                if (data.dataArray.length > 0) {
                                    $.each(data.dataArray, function (key, value) {
                                        $('#class')
                                                .append($("<option></option>")
                                                        .attr("value", value.id)
                                                        .text(value.class_name)).select2();
                                    });
                                }
                            } else {
                                $('#student').empty();
                                $('#student')
                                        .append($("<option></option>")
                                                .attr("value", "")
                                                .text("Select Pupil")).select2();
                                if (data.dataArray.length > 0) {
                                    $.each(data.dataArray, function (key, value) {
                                        $('#student')
                                                .append($("<option></option>")
                                                        .attr("value", value.id)
                                                        .text(value.first_name + ' ' + value.last_name));
                                    });
                                }
                            }
                        }
                    },
                });
            }
        })
<?php } ?>
</script>
@stop