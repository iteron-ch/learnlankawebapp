jsMain = new Main();
$( document ).ready(function() {
$("#create_btn").click(function(){
   location.href = vars['addUrl'];
});

var oTable = jQuery(vars['dataTable']).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: vars['listUrl']
        },
        columns: [
            {data: 'title', name: 'title'},
            {data: 'sub_title', name: 'sub_title'},
            {data: 'description', name: 'description'},
			{data: 'meta_title', name: 'meta_title'},
			{data: 'meta_keywords', name: 'meta_keywords'},
			{data: 'meta_description', name: 'meta_description'},
            {data: 'status', name: 'status', searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        "order": [[3, "desc" ]]
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