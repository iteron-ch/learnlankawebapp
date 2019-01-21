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
                d.name = $.trim($('input[name=first_name]').val());
                d.email = $.trim($('input[name=email]').val());
                d.username = $.trim($('input[name=username]').val());
                d.status = $('#status').val();
            }
        },
        columns: [
            {data: 'user_type', name: 'user_type'},
            {data: 'name', name: 'first_name'},
            {data: 'email', name: 'email'},
            {data: 'username', name: 'username'},
            {data: 'created_at', name: 'created_at',"sClass" : "text-center"},
            {data: 'payment_status', name: 'payment_status',"sClass" : "text-center"},
            {data: 'action', name: 'action', orderable: false, searchable: false,"sClass" : "text-center"},
            {data: 'updated_at', name: 'updated_at',visible: false, className: 'none'}
        ],
        "order": [[7, "desc" ]]
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