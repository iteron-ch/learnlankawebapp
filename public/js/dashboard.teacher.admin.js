jsMain = new Main();
$(document).ready(function () {
    var oTable1 = jQuery("#rewards-table").DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        bPaginate: false,
        bInfo: false,
        ajax: {
            url: vars['rewardListUrl'],
            data: function (d) {
                d.isLimited = 'yes';
            }
        },
        columns: [
            {data: 'rank', name: 'rank'},
            {data: 'username', name: 'username'},
            {data: 'email', name: 'email'}
        ],
        "order": [[1, "desc"]]
    });
    var oTable2 = jQuery("#teacher-table").DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        bPaginate: false,
        bInfo: false,
        ajax: {
            url: vars['studentListUrl'],
            data: function (d) {
                d.isLimited = 'yes';
            }
        },
        columns: [
            {data: 'name', name: 'first_name'},
            {data: 'created_at', name: 'created_at'},
        ],
        "order": [[1, "desc"]]
    });
    var oTable5 = jQuery("#deactive-teacher-table").DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        bPaginate: false,
        bInfo: false,
        ajax: {
            url: vars['studentListUrl'],
            data: function (d) {
                d.isLimited = 'yes';
                d.status = 'Inactive';
            }
        },
        columns: [
            {data: 'name', name: 'first_name'},
            {data: 'created_at', name: 'created_at'},
        ],
        "order": [[1, "desc"]]
    });
    var oTable6 = jQuery("#topic-table").DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        bPaginate: false,
        bInfo: false,
        ajax: {
            url: vars['interventionTopicListUrl'],
            data: function (d) {
                d.isLimited = 'yes';
            }
        },
        columns: [
            {data: 'strand_math', name: 'strand_math'},
            {data: 'strand_english', name: 'strand_english'},
        ],
        "order": [[1, "desc"]]
    });
    
       var oTable1 = jQuery("#math-topic-table").DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        bPaginate: false,
        bInfo: false,
        ajax: {
            url: vars['interventionTopicListUrl'],
            data: function (d) {
                d.subject = 'MATH';
            }
        },
        columns: [
            {data: 'strand_math', name: 'strand_math'},
            {data: 'percent', name: 'percent',width:'100'},
        ],
        "order": [[0, "desc"]]
    });
    var oTable2 = jQuery("#english-topic-table").DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        bPaginate: false,
        bInfo: false,
        ajax: {
            url: vars['interventionTopicListUrl'],
            data: function (d) {
                d.subject = 'ENGLISH';
            }
        },
        columns: [
            {data: 'strand_english', name: 'strand_english'},
            {data: 'percent', name: 'percent',width:'100'},
        ],
        "order": [[0, "desc"]]
    });
     var oTableTestResult = jQuery("#testresult-table").DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        bPaginate: false,
        bInfo: false,
        ajax: {
            url: vars['dashboardtestresultUrl'],
            data: function (d) {
                
            }
        },
        columns: [
            {data: 'class', name: 'class',sortable:false},
            {data: 'test_mame', name: 'test_mame',orderable:false},
            {data: 'marks', name: 'marks',sortable:false},
            {data: 'marks', name: 'marks',visible: false, sortable:false}
            
        ],
        "order": [[3, "desc"]]
    });
});    