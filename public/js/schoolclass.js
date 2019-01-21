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
                d.class_name = $.trim($('#class_name').val());
                d.year = $('#year').val();
                d.status = $('#status').val();
            }
        },
        columns: [
            {data: 'class_name', name: 'class_name'},
            {data: 'cnt_student', name: 'cnt_student', "sClass": "text-center"},
            {data: 'year', name: 'year', searchable: false},
            {data: 'status', name: 'status', searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'updated_at', name: 'updated_at', visible: false, className: 'none'},
        ],
        "order": [[5, "desc"]]
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
});    