jsMain = new Main();
$(document).ready(function () {
    if(vars['dataTable'] == '#inbox-table'){
        var columns = [
            {data: 'subject', name: 'subject',orderable: false,},
            {data: 'sender_name', name: 'sender_name', "sClass": "text-center",sortable:false},
            {data: 'updated_at', name: 'updated_at', "sClass": "text-center",orderable: false,},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-center"},
            {data: 'updated_onetoone_at', name: 'updated_onetoone_at', visible: false, className: 'none'},
        ];
    }else{
        var columns = [
            {data: 'subject', name: 'subject',orderable: false},
            {data: 'receiver_name', name: 'receiver_name', "sClass": "text-center",sortable:false},
            {data: 'updated_at', name: 'updated_at', "sClass": "text-center",orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-center"},
            {data: 'updated_onetoone_at', name: 'updated_onetoone_at', visible: false, className: 'none'},
        ];
    }

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
        columns: columns,
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