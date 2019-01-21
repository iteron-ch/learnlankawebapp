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
                d.school_name = $.trim($('input[name=school_name]').val());
                d.email = $.trim($('input[name=email]').val());
                d.status = $('#status').val();
            }
        },
        columns: [
            {data: 'school_name', name: 'school_name', width: '250'},
            {data: 'no_of_students', name: 'no_of_students', width: '100', "sClass": "text-center"},
            {data: 'email', name: 'email'},
            {data: 'created_at', name: 'created_at', "sClass": "text-center"},
            {data: 'status', name: 'status', "sClass": "text-center"},
            {data: 'no_of_teachers', name: 'no_of_teachers', "sClass": "text-center"},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-center"},
            {data: 'updated_at', name: 'updated_at', visible: false, className: 'none'},
        ],
        "order": [[7, "desc"]]
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