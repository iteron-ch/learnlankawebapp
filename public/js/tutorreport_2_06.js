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
            {data: 'name', name: 'first_name',width:'250'},
            {data: 'email', name: 'email'},
            {data: 'username', name: 'username'},
            {data: 'created_at', name: 'created_at',"sClass" : "text-center"},
            {data: 'county', name: 'county',width:'150'},
            {data: 'status', name: 'status',"sClass" : "text-center"},
            {data: 'howfinds_id', name: 'howfinds_id', width: '100'},
            {data: 'amount', name: 'amount', width: '100'},
            {data: 'no_of_students', name: 'no_of_students',"sClass" : "text-center"},
            {data: 'subscription_expiry_date', name: 'subscription_expiry_date', "sClass": "text-center"},
        ],
        "order": [[4, "desc" ]]
    });
    var tt = new $.fn.dataTable.TableTools( oTable );
    $( tt.fnContainer() ).insertAfter('div.info');
    jQuery('#search-form').on('submit', function(e) {
        oTable.draw();
        e.preventDefault();
    });
});    