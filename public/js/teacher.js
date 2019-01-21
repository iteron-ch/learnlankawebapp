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
                d.username = $.trim($('input[name=username]').val());
                d.school = $.trim($('#school').val());
                d.status = $('#status').val();
            }
        },
        columns: [
            {data: 'first_name', name: 'first_name'},
            {data: 'last_name', name: 'last_name'},
            {data: 'email', name: 'email'},
            {data: 'username', name: 'username',width:'250'},
            {data: 'created_at', name: 'created_at' ,"sClass" : "text-center",width:'100'},
            {data: 'status', name: 'status' ,"sClass" : "text-center"},
            {data: 'no_of_students', name: 'no_of_students',"sClass" : "text-center"},
            {data: 'action', name: 'action', orderable: false, searchable: false,"sClass" : "text-center"},
            {data: 'updated_at', name: 'updated_at',visible: false, className: 'none'},
        ],
        "order": [[8, "desc" ]]
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