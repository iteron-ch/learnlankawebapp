jsMain = new Main();
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
                d.first_name = $.trim($('#first_name').val());
                d.last_name = $.trim($('#last_name').val());
                d.email = $.trim($('#email').val());
                d.user_type = $.trim($('#user_type').val());
                d.how_hear = $.trim($('#how_hear').val());
                d.job_role = $.trim($('#job_role').val());
            }
        },
        columns: [
            {data: 'title', name: 'title'},
            {data: 'first_name', name: 'first_name'},
            {data: 'last_name', name: 'last_name'},
            {data: 'email', name: 'email'},
            {data: 'contact_no', name: 'contact_no'},
            {data: 'user_type', name: 'user_type'},
            {data: 'how_hear', name: 'how_hear'},
            {data: 'job_role', name: 'job_role'},
            {data: 'created_at', name: 'job_role'},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-center"},
            {data: 'updated_at', name: 'updated_at',visible: false, className: 'none'}
            ],
        "order": [[10, "desc"]]
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