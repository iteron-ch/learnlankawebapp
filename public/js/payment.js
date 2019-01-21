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
            url: vars['listUrl']
        },
        columns: [
            {data: 'transaction_key', name: 'transaction_key'},
            {data: 'transaction_user_id', name: 'transaction_user_id'},
            {data: 'transaction_password', name: 'transaction_password'},
            {data: 'paypal_email', name: 'paypal_email'},
            {data: 'paypal_type', name: 'paypal_method'},
        ],
        "order": [[2, "desc"]]
    });

});