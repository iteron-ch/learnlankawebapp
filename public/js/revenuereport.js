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
                var value = '';
                d.username = $.trim($('input[name=username]').val());
                d.amount = $.trim($('input[name=amount]').val());
                d.user_type = $("input:radio[name=user_type]:checked").val();
                //d.email = $.trim($('input[name=email]').val());
            }
        },
        columns: [
            {data: 'username', name: 'username', width: '250'},
            {data: 'upgrade_type', name: 'upgrade_type', width: '200'},
            {data: 'voucher_code', name: 'voucher_code', width: '200'},
            {data: 'payment_created_at', name: 'payment_created_at', width: '200'},
            {data: 'amount', name: 'amount', "sClass": "text-center", width: '200'},
            {data: 'action', name: 'action', orderable: false, searchable: false, "sClass": "text-center", width: '150'},
            
        ],
        "order": [[3, "desc"]]
    });
    var tt = new $.fn.dataTable.TableTools(oTable);
    $(tt.fnContainer()).insertAfter('div.info');
    jQuery('#search-form').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });
    $(vars['dataTable'] + ' tbody').on('click', '.view_row', function (e) {
        e.preventDefault();
        var eleObj = $(this);
        jsMain.showModelIframe(eleObj);
    });
});    