
$(document).ready(function () {
    var oTable = jQuery(vars['dataTable']).DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        ajax: {
            method: 'GET',
            url: vars['listUrl'],
            data: function (d) {
                d.key_stage = jQuery('#key_stage').val();
                d.year_group = jQuery('#year_group').val();
                d.subject = jQuery('#subject').val();
                d.set_name = $.trim(jQuery('#set_name').val());
                d.set_group = $.trim(jQuery('#set_group').val());
                d.status = jQuery('#status').val();
            }
            
        },
        columns: [
            {data: 'set_name', name: 'set_name'},
            {data: 'tot_questions', name: 'tot_questions'},
            {data: 'tot_ques_published', name: 'tot_ques_published'},
            {data: 'tot_ques_draft', name: 'tot_ques_draft'},
            {data: 'tot_contributors', name: 'tot_contributors'},
            {data: 'created_at', name: 'created_at'},
            {data: 'set_group', name: 'set_group'},
            {data: 'allow_print', name: 'allow_print'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false,"sClass" : "text-center"},
            {data: 'updated_at', name: 'updated_at',visible: false, className: 'none'}
        ],
        "order": [[10, "desc"]]
    });
    jQuery('#search-form').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });

    $(vars['dataTable']+' tbody').on( 'click', '.view_row', function (e) {
        e.preventDefault();
        var eleObj = $(this);
        jsMain.showModelIframe(eleObj);
    });
    
    $(vars['dataTable']+' tbody').on( 'click', '.delete_row', function (e) {
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
    $("#key_stage").change(function(){
        jsMain.makeDropDownJsonData(vars['yearDD'],$("#year_group"),$(this).val(),'');
    });

});