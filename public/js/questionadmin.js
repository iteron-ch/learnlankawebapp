jsMain = new Main();
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
                d.first_name = $.trim(jQuery('#first_name').val());
                d.email = $.trim(jQuery('#email').val());
                d.username = $.trim(jQuery('#username').val());
            }
        },
        columns: [
            {data: 'first_name', name: 'first_name', width: '250'},
            {data: 'last_name', name: 'email'},
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'},
            {data: 'question_count', name: 'question_count', "sClass": "text-center"},
            {data: 'last_login', name: 'last_login', "sClass": "text-center"},
            {data: 'status', name: 'status', "sClass": "text-center"},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-center"},
            {data: 'updated_at', name: 'updated_at',visible: false, className: 'none'}
        ],
        "order": [[8, "desc"]]
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
        jsMain.deleteRecord({eleObj: eleObj, oTable: oTable,
            url: vars['deleteUrl'],
            confirmMsg: vars['confirmMsg'],
            successMsg: vars['successMsg']
        });
    });
});    