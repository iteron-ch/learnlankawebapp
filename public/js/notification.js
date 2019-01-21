jsMain = new Main("{{ trans('admin/admin.select_option') }}");
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
                d.title = $.trim($('#title').val());
                d.status = $('#status').val();
            }
        },
        columns: [
            {data: 'title', name: 'title', width: '200', "sClass": "text-center"},
            {data: 'user_type', name: 'user_type', width: '300'},
            {data: 'description', name: 'description', width: '400'},
            {data: 'status', name: 'status', width: '200', "sClass": "text-center"},
            {data: 'updated_at', name: 'updated_at', width: '200', "sClass": "text-center"},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-center"},
        ],
        "order": [[4, "desc"]]
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