$(document).ready(function () {

    var oTable = $(vars['dataTable']).DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: vars['listUrl'],
            data: function (d) {
                d.name = $("#name").val();
            }
        },
        columns: [
            {data: 'first_name', name: 'first_name', "sClass": "text-center"},
            {data: 'last_name', name: 'last_name',  "sClass": "text-center"},
            {data: 'completed', name: 'completed', "sClass": "text-center"},
            {data: 'created_at', name: 'taskstudents.created_at', visible: false,}
        ],
        "order": [[3, "desc"]]
    });
});    