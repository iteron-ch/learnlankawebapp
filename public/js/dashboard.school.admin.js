jsMain = new Main();
$(document).ready(function () {
    /*
     var oTable = $("#school-table").DataTable({
     bFilter: false,
     processing: true,
     serverSide: true,
     bPaginate: false,
     bInfo: false,
     ajax: {
     url: vars['teacherListUrl'],
     data: function (d) {
     d.isLimited = 'yes';
     }
     },
     columns: [
     {data: 'name', name: 'first_name'},
     {data: 'created_at', name: 'created_at' ,"sClass" : "text-center",width:'100'},
     {data: 'no_of_students', name: 'no_of_students',"sClass" : "text-center"},
     ],
     "order": [[1, "desc"]]
     });
     
     var oTable = $("#deactive-school-table").DataTable({
     bFilter: false,
     processing: true,
     serverSide: true,
     bPaginate: false,
     bInfo: false,
     ajax: {
     url: vars['teacherListUrl'],
     data: function (d) {
     d.isLimited = 'yes';
     d.status = 'Inactive';
     }
     },
     columns: [
     {data: 'name', name: 'first_name'},
     {data: 'created_at', name: 'created_at' ,"sClass" : "text-center",width:'100'},
     {data: 'no_of_students', name: 'no_of_students',"sClass" : "text-center"},
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
     var oTable4 = jQuery("#deactive-tutor-table").DataTable({
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
     });*/

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
    var oTable3 = jQuery("#activity-table").DataTable({
        bFilter: false,
        processing: true,
        serverSide: true,
        bPaginate: false,
        bInfo: false,
        ajax: {
            url: vars['activitytopicsUrl'],
            data: function (d) {
                d.source = 'school';
            }
        },
        columns: [
            {data: 'topic', name: 'topic',width:'250'},
            
        ],
        "order": [[0, "desc"]]
    });
    var oTable4 = jQuery("#testresult-table").DataTable({
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