jsMain = new Main("Select");
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
                d.category = $.trim($('#category').val());
                d.strand = $('#strands_id').val();
                d.substrand = $('#sub_strands_id').val();
                d.status = $('#status').val();
            }
        },
        columns: [
            {data: 'visible_to', name: 'visible_to', width: '150',orderable: false },
            {data: 'title', name: 'title', width: '150', "sClass": "text-center"},
            {data: 'category', name: 'category', width: '300', "sClass": "text-center",orderable: false },
            {data: 'strand', name: 'strand', width: '300', "sClass": "text-center",orderable: false },
            {data: 'substrand', name: 'substrand', width: '300', "sClass": "text-center",orderable: false },
            {data: 'filename', name: 'filename', width: '300', "sClass": "text-center",orderable: false },
            {data: 'status', name: 'status', width: '150', "sClass": "text-center"},
            {data: 'updated_at', name: 'updated_at', width: '200', "sClass": "text-center"},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-center"},
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