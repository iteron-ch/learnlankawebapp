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
                d.percentage = $.trim($('#percentage').val());
                d.subject = $('#subject').val();
                d.strand = $('#strand').val();
                d.substrand = $('#substrand').val();
                
            }
        },
        columns: [
            {data: 'percentage', name: 'rewards.percent_min'},
            {data: 'title', name: 'title'},
            {data: 'subject', name: 'subject'},
            {data: 'strand', name: 'strand'},
            {data: 'substrand', name: 'substrand'},
            {data: 'created_at', name: 'created_at', searchable: false},
            {data: 'status', name: 'status', searchable: true},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'updated_at', name: 'updated_at', searchable: false, visible: false},
        ],
        "order": [[8, "desc"]]
    });
    $(vars['dataTable'] + ' tbody').on('click', '.view_row', function (e) {
        e.preventDefault();
        var eleObj = $(this);
        jsMain.showModelIframe(eleObj);
    });
    jQuery('#search-form').on('submit', function (e) {
        oTable.draw();
        e.preventDefault();
    });

    $('#rewards-revision-table tbody').on('click', '.delete_record', function (e) {
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