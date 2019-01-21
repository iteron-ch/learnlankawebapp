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
            {data: 'school_sign_up_fee', name: 'school_sign_up_fee'},
            {data: 'parent_sign_up_fee', name: 'parent_sign_up_fee'},
            {data: 'per_student_fee', name: 'per_student_fee'},
            {data: 'per_5_student_fee', name: 'per_5_student_fee'},
            {data: 'updated_by', name: 'updated_by'},
            {data: 'updated_at', name: 'updated_at'},
        ],
        "order": [[5, "desc"]]
    });

    $(vars['dataTable'] + ' tbody').on('click', '.view_row', function (e) {
        e.preventDefault();
        var eleObj = $(this);
        jsMain.showModelIframe(eleObj);
    });
});