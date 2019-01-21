jsMain = new Main();
$( document ).ready(function() {
    
    $("#create_btn").click(function(){
   location.href = vars['addUrl'];
});
 var oTable = jQuery(vars['dataTable']).DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: vars['listUrl'],
            data: function (d) { 
                d.first_name = $.trim($('input[name=first_name]').val());
                d.last_name = $.trim($('input[name=last_name]').val());
                d.email = $.trim($('input[name=email]').val());
                d.teacher_name = $.trim($('#teacher_name').val());
                d.status = $('#status').val();
            }
        },
        columns: [
            {data: 'first_name', name: 'first_name'},
            {data: 'last_name', name: 'last_name'},
            {data: 'created_at', name: 'created_at'},
            {data: 'status', name: 'status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'updated_at', name: 'updated_at',visible: false, className: 'none'},
        ],
        "order": [[5, "desc" ]]
    });
    
    jQuery('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });
    
    $(vars['dataTable']+' tbody').on( 'click', '.view_row', function (e) {
        e.preventDefault();
        var eleObj = $(this);
        jsMain.showModelIframe(eleObj);
    });
    
   $(vars['dataTable']+' tbody').on( 'click', '.delete_row', function (e) { 
        e.preventDefault();
        var eleObj = $(this);
        jsMain.deleteRecord({ eleObj: eleObj, oTable: oTable,
            url: vars['deleteUrl'], 
            confirmMsg: vars['confirmMsg'],
            successMsg: vars['successMsg']
        });
    });
});    