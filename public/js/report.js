jsMain = new Main();
$(document).ready(function () {
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
        columns: [
            {data: 'school_name', name: 'school_name', width: '250'},
            {data: 'school_type', name: 'school_type', width: '250'},
            {data: 'email', name: 'email'},
            {data: 'created_at', name: 'created_at', "sClass": "text-center"},
            {data: 'county', name: 'county', width: '250'},
            {data: 'no_of_students', name: 'no_of_students', width: '100', "sClass": "text-center"},
            {data: 'howfinds_id', name: 'howfinds_id', width: '250'},
            {data: 'status', name: 'status', "sClass": "text-center"},
            {data: 'no_of_teachers', name: 'no_of_teachers', "sClass": "text-center"},
            {data: 'subscription_expiry_date', name: 'subscription_expiry_date', "sClass": "text-center"},
            {data: 'renew_date', name: 'renew_date', "sClass": "text-center"},
            
            
        ],
        "order": [[6, "desc"]]
    });
    var tt = new $.fn.dataTable.TableTools( oTable );
 
    $( tt.fnContainer() ).insertAfter('div.info');
      
   

    $('#search-form').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });
});    