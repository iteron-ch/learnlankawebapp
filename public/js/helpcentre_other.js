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
            {data: 'title', name: 'title', width: '150', "sClass": "text-left"},
            {data: 'created_at', name: 'created_at', width: '200', "sClass": "text-left"},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-left"},
            
        ],
        "order": [[1, "desc"]]
    });
    $('#search-form').on('submit', function (e) {
        $("#helpcentre-table").show();
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


