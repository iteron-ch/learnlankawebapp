@extends(($layout == 'iframe') ? 'admin.layout._reports' : 'admin.layout._default')

@section('content')
<!-- BEGIN PAGE HEADER-->
<?php if ($layout != 'iframe') { ?>
    @include('admin.layout._page_header', ['title' => trans('admin/admin.manageaccount'), 'trait_1' => $trait['trait_1'], 'trait_1_link' => $trait['trait_1_link'],'trait_2' => $trait['trait_2']])
<?php } ?>
<style>
    .select2-container{width:200px}
</style>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->

<div class="row">
    <div class="col-md-12 col-sm-12">
        <div class="portlet-body">
            <form name="frm_gap_report" id="frm_gap_report" class="form-horizontal">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <div class="col-md-8">
                                    <select name="subject" class="form-control select2me" id="subject" class="form-control" >
                                        <option value="" selected="selected">Select Subject</option>
                                        <option value="Math">Math</option>
                                        <option value="English">English</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="question_set" class="form-control select2me" id="question_set" class="form-control" disabled="disabled">
                                    <option value="" selected="selected">Select Set</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <select name="paper_id" class="form-control select2me" id="paper_id" class="form-control" disabled="disabled">
                                    <option value="" selected="selected">Select Paper</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <input type="submit" name="generate_gap_report"  data-loading-text="Loading..." id="generate_gap_report" value="Submit" class="btn btn-primary">
                            <input type="reset" name="generate_gap_rest" id="generate_gap_reset" value="Reset" class="btn btn-primary grey">
                        </div>
                        <input type="hidden" name="schoolId" value="<?php echo $schoolId ?>"/>
                        <input type="hidden" name="classId" value="<?php echo $classId ?>"/>
                    </div>
                </div>
            </form>
        </div>
           <!--<a href="/adminreport/classgap?schoolId=<?php echo $schoolId; ?>&layout=<?php echo $layout ?>" style="margin-left:15px;"> <input type="button" name="reset" id="reset" value="Reset Filter" class="btn btn-primary grey"></a>-->
    </div>
</div>
<div class="row">
    <div class="col-md-12" id="container_gap_report"></div>
</div>

<!-- END PAGE CONTENT-->
@stop
@section('pagescripts')
{!! HTML::script('js/jquery.table2excel.js') !!}
<script>
    var question_sets = <?php echo json_encode($question_sets) ?>;
    var papers = <?php echo json_encode($papers) ?>;
    $(document).ready(function() {
        $("#subject").change(function() {
            var subject = $(this).val();
            var ht = '<option value="">Select Set</option>';

            if (subject != '') {
                $("#question_set").attr('disabled', false);
                $.each(question_sets[subject], function(key, lavel) {
                    ht += '<option value="' + key + '">' + lavel + '</option>';
                });
                var htp = '';
                $("#paper_id").attr('disabled', false);
                $.each(papers[subject], function(key, lavel) {
                    htp += '<option value="' + key + '">' + lavel.name + '</option>';
                });
            } else {
                var htp = '<option value="">Select Paper</option>';
                $("#question_set").attr('disabled', true);
                $("#paper_id").attr('disabled', true);
            }
            $("#question_set").html(ht).select2();
            $("#paper_id").html(htp).select2();
        });
        $("#frm_gap_report").submit(function(e) {
            e.preventDefault();
            var $btn = $("#generate_gap_report");
            $btn.button('loading');
            var values = {};
            $.each($(this).serializeArray(), function(i, field) {
                values[field.name] = field.value;
            });
            if (values.subject == '') {
                alert("Please select subject.");
                return false;
            }
            if (values.question_set == '') {
                alert("Please select set.");
                return false;
            }
            var url = "/adminreport/classgap?" + $(this).serialize();
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $("#container_gap_report").html(data);
                    $btn.button('reset');
                }
            });
        });
        $("#generate_gap_reset").click(function() {
            $("#question_set").attr('disabled', true);
            $("#paper_id").attr('disabled', true);
            $("#container_gap_report").html('');
            setTimeout("$('#frm_gap_report select').val('').select2();", "400");
        });
    });
    function exportdata(id, name) {
        $("#" + id).table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: name,
        });
    }
    function selectQuestion(queId) {
        var id = '<?php echo $schoolId; ?>';
        var layout = '<?php echo $layout ?>';
<?php if (!empty($studentId)) { ?>
            var sid = '<?php echo $studentId; ?>';
            window.location.href = "/adminreport/classgap?schoolId=" + id + "&queId=" + queId + "&stuId=" + sid + "&layout=" + layout;
            ;
<?php } else { ?>
            window.location.href = "/adminreport/classgap?schoolId=" + id + "&queId=" + queId + "&layout=" + layout;
            ;
<?php } ?>
    }

    function selectStudent(stuId) {
        var id = '<?php echo $schoolId; ?>';
        var layout = '<?php echo $layout ?>';
<?php if (!empty($questionId)) { ?>
            var qid = '<?php echo $questionId; ?>';
            window.location.href = "/adminreport/classgap?schoolId=" + id + "&stuId=" + stuId + "&queId=" + qid + "&layout=" + layout;
            ;
<?php } else { ?>
            window.location.href = "/adminreport/classgap?schoolId=" + id + "&stuId=" + stuId + "&layout=" + layout;
            ;
<?php } ?>

    }

</script>
@stop