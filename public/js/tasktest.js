$(document).ready(function () {

    $("#create_btn").click(function () {
        location.href = vars['addUrl'];
    });

    var oTable = $(vars['dataTable']).DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: vars['listUrl'],
            data: function (d) {
                d.key_stage = $('#key_stage').val();
                d.year_group = $('#year_group').val();
                d.subject = $('#subject').val();
                d.question_set = $('#question_set').val();
                d.status = $('#status').val();
            }
        },
        columns: [
            {data: 'key_stage', name: 'key_stage', "sClass": "text-center"},
            {data: 'year_group', name: 'year_group',  "sClass": "text-center"},
            {data: 'subject', name: 'subject', "sClass": "text-center"},
            {data: 'question_set', name: 'question_set', "sClass": "text-center"},
            {data: 'class', name: 'class', "sClass": "text-center",orderable: false},
            {data: 'group', name: 'group', "sClass": "text-center",orderable: false},
            {data: 'assign_date', name: 'assign_date', "sClass": "text-center"},
            {data: 'completion_date', name: 'completion_date', "sClass": "text-center"},
            {data: 'completed', name: 'completed', "sClass": "text-center"},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-center"},
            {data: 'updated_at', name: 'taskassignments.updated_at',visible: false, className: 'none'},
        ],
        "order": [[9, "desc"]]
    });

    $('#search-form').on('submit', function (e) {
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
});    