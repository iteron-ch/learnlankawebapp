$(window).load(function () {
    $.fn.dataTableExt.afnFiltering = new Array();
    var oControls = $('.dtable_custom_controls:first').find(':input[name]');    

    var oTable = jQuery(vars['dataTable']).DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        stateSave: true,
        fnDrawCallback: function () {
            //Without the CSS call, the table occasionally appears a little too wide
            //$(this).show().css('width', '100%');
            //Don't show the filters until the table is showing
            //$(this).closest('.dtable_custom_controls').show();
        },
        fnStateSaveParams: function (oSettings, sValue) {
            //Save custom filters
            
            oControls.each(function () {
                //console.log($(this).attr('name')+$(this).val());
                if ($(this).attr('name')) {
                        //sValue[ $(this).attr('name') ] = $(this).val().replace('"', '"');
                        sValue[ $(this).attr('name') ] = $(this).val();
                }
            });
            
            return sValue;
        },
        fnStateLoadParams: function (oSettings, oData) {
            //Load custom filters
            var paperId = '';
            var questionSetsId = '';
            var strandsId = '';
            var questionTypeId = '';
            var substrandsId = '';
            oControls.each(function () {
                var oControl = $(this);
                $.each(oData, function (index, value) {
                    if (index == oControl.attr('name')) { 
                        if(oControl.attr('name') == 'paper')
                            paperId = value;
                        if(oControl.attr('name') == 'question_set')
                            questionSetsId = value;
                        if(oControl.attr('name') == 'strands_id')
                            strandsId = value;
                        if(oControl.attr('name') == 'questionType')
                            questionTypeId = value;
                        if(oControl.attr('name') == 'substrands_id')
                            substrandsId = value;
                        
                        if(oControl.is("select"))
                            oControl.val(value).select2();
                        else
                            oControl.val(value);
                    }
                });
            });
            jsMain.getPaperOpt(vars['paperJson'], $("#paper"), $("#subject").val(), paperId);
            jsMain.getQuestionSetOpt(questionSets, questionSetsId);
            jsMain.makeDropDownJsonData(strands, $("#strands_id"), $("#subject").val(), strandsId);
            jsMain.makeDropDownJsonData(questionType, $("#questionType"), $("#subject").val(), questionTypeId);
            jsMain.makeDropDownJsonData(substrands, $("#substrands_id"), $("#strands_id").val(), substrandsId);
            return true;
        },        
        ajax: {
            method: 'GET',
            url: vars['listUrl'],
            data: function (d) {
                d.questionType = jQuery('#questionType').val();
                d.subject = jQuery('#subject').val();
                d.paper = jQuery('#paper').val();
                d.set_group = jQuery('#set_group').val();
                d.strands_id = jQuery('#strands_id').val();
                d.substrands_id = jQuery('#substrands_id').val();
                d.status = jQuery('#status').val();
                d.question_set = jQuery('#question_set').val();
                d.key_stage = jQuery('#key_stage').val();
                d.year_group = jQuery('#year_group').val();
                d.difficulty = jQuery('#difficulty').val();
                d.user_type = jQuery('#user_type').val();
                d.created_by = jQuery('#created_by').val();
                d.question_id = $.trim(jQuery('#question_id').val());
                d.validater1 = jQuery('#validater1').val();
                d.validater2 = jQuery('#validater2').val();
                d.validate_stage = jQuery('#validate_stage').val();
                d.validateReason1 = jQuery('#validateReason1').val();
                d.validateReason2 = jQuery('#validateReason2').val();
            }

        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'subject', name: 'subject'},
            {data: 'key_stage', name: 'key_stage'},
            {data: 'year_group', name: 'year_group'},
            {data: 'set_group', name: 'set_group'},
            {data: 'set_name', name: 'set_name'},
            {data: 'paper_id', name: 'paper_id'},
            {data: 'difficulty', name: 'difficulty'},
            {data: 'strands', name: 'strands'},
            {data: 'substrands', name: 'substrands'},
            {data: 'question_type', name: 'question_type'},
            {data: 'question_id', name: 'question_id'},
            {data: 'status', name: 'questions.status'},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-center"},
            {data: 'created_at', name: 'questions.created_at'},
            {data: 'updated_at', name: 'questions.updated_at'},
            {data: 'published_date', name: 'questions.published_date'},
            {data: 'created_by', name: 'user.created_by'},
            {data: 'updated_at', name: 'updated_at', visible: false, className: 'none'},
        ],
        "order": [[18, "desc"]]
    });
    jQuery('#search-form').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });

    $(vars['dataTable'] + ' tbody').on('click', '.view_row', function (e) {
        e.preventDefault();
        var eleObj = $(this);
        jsMain.showModelIframe(eleObj);
    });

    $(vars['dataTable'] + ' tbody').on('click', '.delete_row', function (e) {
        e.preventDefault();
        var eleObj = $(this);
        jsMain.deleteRecord({
            eleObj: eleObj,
            oTable: oTable,
            url: vars['deleteUrl'],
            confirmMsg: vars['confirmMsg'],
            successMsg: vars['successMsg']
        });
    });
    $(vars['dataTable'] + ' tbody').on('click', '.publish_row', function (e) {
        e.preventDefault();
        var eleObj = $(this);
        var id = eleObj.data('id');
        var status = eleObj.data('status');
        var user_type = eleObj.data('usertype');
        if (user_type == 1) {
            if (status == 'Published')
                var msg = vars['publishConfirmMsg'];
            else if (status == 'Rejected')
                var msg = vars['rejectConfirmMsg'];
            else if (status == 'Unpublished')
                var msg = vars['unpublishConfirmMsg'];
        }
        else if (user_type == 6) {
            if (status == 'In Review')
                var msg = vars['inReviewConfirmMsg'];
        }
        bootbox.confirm({
            message: msg,
            callback: function (result) {
                if (result) {
                    $.ajax({
                        url: vars['changeStatusUrl'],
                        method: 'POST',
                        data: {id: id,status:status},
                        beforeSend: function () {

                        },
                        success: function (returnData) {
                            //delete row and reload table
                            oTable
                                    .row(eleObj.parents('tr'))
                                    .remove()
                                    .draw();
                            //show success message
                            Metronic.alert({
                                type: 'success', // alert's type
                                message: vars['publishSuccessMessage'], // alert's message
                            });
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            //other stuff
                        },
                        complete: function () {

                        }
                    });
                }
            }
        });
    });
});