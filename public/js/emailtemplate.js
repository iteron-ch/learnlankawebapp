jsMain = new Main();
$( document ).ready(function() {
$("#create_btn").click(function(){
   location.href = vars['addUrl'];
});
var oTable = jQuery(vars['dataTable']).DataTable({
        bFilter: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: vars['listUrl']
        },
        columns: [
            {data: 'title', name: 'title'},
            {data: 'subject', name: 'subject'},
            {data: 'status', name: 'status', searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'updated_at', name: 'updated_at',visible: false, className: 'none'}
        ],
        "order": [[4, "desc" ]]
    });
    
    jQuery('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });
    
    $(vars['dataTable']+' tbody').on( 'click', '.delete_row', function (e) {
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