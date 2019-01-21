jsMain = new Main();
$(document).ready(function () {

    var oTable = $("#school-table").DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        bPaginate: false,
        bInfo: false,
        ajax: {
            url: vars['schoolListUrl'],
            data: function (d) {
                d.isLimited = 'yes';
            }
        },
        columns: [
            {data: 'school_name', name: 'school_name'},
            {data: 'no_of_students', name: 'no_of_students', 'sClass': 'text-center'},
            {data: 'created_at', name: 'created_at', 'sClass': 'text-center'},
            {data: 'no_of_teachers', name: 'no_of_teachers', "sClass": "text-center"},
            {data: 'subscription_expiry_date', name: 'subscription_expiry_date', "sClass": "text-center"},
        ],
        "order": [[2, "desc"]]
    });

    var oTable = $("#deactive-school-table").DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        bPaginate: false,
        bInfo: false,
        ajax: {
            url: vars['schoolListUrl'],
            data: function (d) {
                d.isLimited = 'yes';
                d.status = 'Inactive';
            }
        },
        columns: [
            {data: 'school_name', name: 'school_name'},
            {data: 'no_of_students', name: 'no_of_students', 'sClass': 'text-center'},
            {data: 'updated_at', name: 'updated_at', 'sClass': 'text-center'},
            {data: 'created_at', name: 'created_at', 'sClass': 'text-center'},
            {data: 'no_of_teachers', name: 'no_of_teachers', "sClass": "text-center"},
        ],
        "order": [[2, "desc"]]
    });

    var oTable3 = jQuery("#tutor-table").DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        bPaginate: false,
        bInfo: false,
        ajax: {
            url: vars['tutorListUrl'],
            data: function (d) {
                d.isLimited = 'yes';
            }
        },
        columns: [
            {data: 'name', name: 'first_name'},
            {data: 'created_at', name: 'created_at', "sClass": "text-center"},
            {data: 'no_of_students', name: 'no_of_students', "sClass": "text-center"},
            {data: 'subscription_expiry_date', name: 'subscription_expiry_date', "sClass": "text-center"},
        ],
        "order": [[1, "desc"]]
    });
    var oTable4 = jQuery("#deactive-tutor-table").DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        bPaginate: false,
        bInfo: false,
        ajax: {
            url: vars['tutorListUrl'],
            data: function (d) {
                d.isLimited = 'yes';
                d.status = 'Inactive';
            }
        },
        columns: [
            {data: 'name', name: 'first_name'},
            {data: 'updated_at', name: 'updated_at', 'sClass': 'text-center'},
            {data: 'created_at', name: 'created_at', "sClass": "text-center"},
            {data: 'no_of_students', name: 'no_of_students', "sClass": "text-center"},
        ],
        "order": [[1, "desc"]]
    });

});    