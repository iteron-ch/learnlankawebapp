$(document).ready(function () {
    $("#create_btn").click(function () {
        location.href = vars['addUrl'];
    });
    var oTable = jQuery(vars['dataTable']).DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: vars['listUrl'],
            data: function (d) {
                d.marks = $.trim($('#marks').val());
                d.title = $.trim($('#title').val());
                d.subject = $('#subject').val();
                d.percentage = $('#percentage').val();
                d.questionset = $('#questionset').val();
            }
        },
        columns: [
            {data: 'percentage', name: 'rewards.percent_min'},
            {data: 'title', name: 'title'},
            {data: 'subject', name: 'subject'},
            {data: 'question_set', name: 'question_set'},
            {data: 'created_at', name: 'created_at', searchable: false},
            {data: 'status', name: 'status', searchable: true},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'updated_at', name: 'updated_at', searchable: false, visible: false},
        ],
        "order": [[7, "desc"]]
    });
    $(vars['dataTable'] + ' tbody').on('click', '.view_row', function (e) {
        e.preventDefault();
        var eleObj = $(this);
        jsMain.showModelIframe(eleObj);
    });
    jQuery('#search-form').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });

    $('#test-rewards-table tbody').on('click', '.delete_record', function (e) {
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