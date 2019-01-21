jsMain = new Main();
$(document).ready(function () {
    $("#create_btn").click(function () {
        location.href = vars['addUrl'];
    });
    var oTable = jQuery(vars['dataTable']).DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        ajax: {
            url: vars['listUrl'],
            data: function (d) {
                d.transaction_id = $.trim($('input[name=payment_id]').val());
                d.no_of_students = $.trim($('input[name=no_of_students]').val());
                d.amount = $.trim($('input[name=amount]').val());
            }
        },
        columns: [
            {data: 'transaction_id', name: 'transaction_id', "sClass": "text-center",width: '100'},
            {data: 'username', name: 'username', "sClass": "text-center",width: '150'},
            {data: 'email', name: 'email', "sClass": "text-center",width: '250'},
            //{data: 'subscription_start_date', name: 'subscription_start_date',"sClass": "text-center", width: '150'},
            //{data: 'subscription_expiry_date', name: 'subscription_expiry_date',"sClass": "text-center", width: '150'},
            {data: 'no_of_students', name: 'no_of_students',"sClass": "text-center", width: '100'},
            {data: 'amount', name: 'amount', "sClass": "text-center","sClass": "text-center", width: '150'},
            {data: 'payment_type', name: 'payment_type',"sClass": "text-center", width: '150'},
            {data: 'status', name: 'status',"sClass": "text-center", width: '150'},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-center", width: '150'},
            {data: 'created_at', name: 'created_at', visible: false, className: 'none'},
        ],
        "order": [[8, "desc"]]
    });

    jQuery('#search-form').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });
    $(vars['dataTable'] + ' tbody').on('click', '.view_row', function (e) {
        e.preventDefault();
        var eleObj = $(this);
        jsMain.showModelIframe(eleObj);
    });
    $(vars['dataTable'] + ' tbody').on('click', '.print_row', function (e) { 
        e.preventDefault();
        var eleObj = $(this);
        jsMain.showModelIframe(eleObj);
    });
});    