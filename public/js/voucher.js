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
                d.voucher_code = $.trim($('#voucher_code').val());
                d.user_type = $('#user_type').val();
                d.status = $('#status').val();
            }
        },
        columns: [
            {data: 'voucher_code', name: 'voucher_code', width: '250'},
            {data: 'discount', name: 'discount', width: '100', "sClass": "text-center"},
            {data: 'discount_type', name: 'discount_type', width: '100', "sClass": "text-center"},
            {data: 'start_date', name: 'start_date', "sClass": "text-center"},
            {data: 'end_date', name: 'end_date', "sClass": "text-center"},
            {data: 'user_type', name: 'user_type', "sClass": "text-center"},
            {data: 'status', name: 'status', "sClass": "text-center"},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-center"},
            {data: 'updated_at', name: 'updated_at',visible: false, className: 'none'}
        ],
        "order": [[8, "desc"]]
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