<?php

// Home
Route::get('/', [
    'uses' => 'HomeController@index',
    'as' => 'index'
]);
Route::get('/decit/{id}/{en?}', [
    'uses' => 'HomeController@deEnc',
    'as' => 'decit'
]);
Route::get('/login', [
    'uses' => 'Auth\AuthController@getLogin',
    'as' => 'login'
]);
Route::get('/forgotusername', [
    'uses' => 'Auth\PasswordController@getUsername',
    'as' => 'forgotusername'
]);
Route::get('/forgotpassword', [
    'uses' => 'Auth\PasswordController@getEmail',
    'as' => 'forgotpassword'
]);
Route::Post('/forgotpasswordpost', [
    'uses' => 'Auth\PasswordController@sendUsername',
    'as' => 'forgotpassword.update'
]);
// Dashboard
Route::get('/dashboard', [
    'uses' => 'HomeController@dashboard',
    'as' => 'dashboard'
]);
Route::post('getcities', [
    'uses' => 'SchoolController@getcities',
    'as' => 'getcities'
]);
Route::get('comingsoon', function() {
    return View::make('admin.home.comingsoon');
});
//End Dashboard
// Dashboard
Route::get('/dashboardSchool', [
    'uses' => 'HomeController@dashboardSchool',
    'as' => 'dashboardSchool'
]);
//End Dashboard

Route::post('applyvoucher', [
    'uses' => 'VoucherController@applyVoucher',
    'as' => 'applyvoucher.index'
]);

Route::get('language', 'HomeController@language');
Route::get('testemail', 'HomeController@testEmail');
Route::resource('home', 'HomeController');
Route::get('medias', [
    'uses' => 'AdminController@filemanager',
    'as' => 'medias',
    'middleware' => 'school'
]);
// Auth
Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController',
]);
Route::get('video/{filename}', [
    'as' => 'showvideo',
    'uses' => function ($filename) {
// return Image::make(public_path('uploads/helpDocuments/' . $filename))->response();
}
]);
//files routes
Route::get('image/{filename}', [
    'as' => 'image',
    'uses' => function ($filename) {
return Image::make(public_path('img/' . $filename))->response();
}]);
Route::get('userimg/{filename}', [
    'as' => 'userimg',
    'uses' => function ($filename) {
$size = Input::get("size");
$size = empty(Input::get("size")) ? 'large' : $size;
return Image::make(public_path('uploads/user/' . $size . '/' . $filename))->response();
}
]);
Route::get('adminimg/{filename}', [
    'as' => 'adminimg',
    'uses' => function ($filename) {
$size = Input::get("size");
$size = empty(Input::get("size")) ? 'large' : $size;
return Image::make(public_path('uploads/admin/' . $size . '/' . $filename))->response();
}
]);
//End file routes
Route::get('studentawardimg/{filename}', [
    'as' => 'studentawardimg',
    'uses' => function ($filename) {
$size = Input::get("size");
$size = empty(Input::get("size")) ? 'large' : $size;
return Image::make(public_path('uploads/studentawards/' . $size . '/' . $filename))->response();
}
]);
Route::get('questionimg/{filename}', [
    'as' => 'questionimg',
    'uses' => function ($filename) {
return Image::make(public_path('uploads/questionbuilder/' . $filename))->response();
}
]);
Route::get('questionaudio/{filename}', [
    'as' => 'questionaudio',
    'uses' => function ($filename) {
$filePath = public_path('uploads/questionbuilder/' . $filename);
$fileContents = File::get($filePath);
return Response::make($fileContents, 200);
}
]);

Route::get('helpcentrefile/{filename}', [
    'as' => 'helpcentrefile',
    'uses' => function ($filename) {
$filePath = public_path('uploads/helpdocuments/' . $filename);
$fileContents = File::get($filePath);
return Response::make($fileContents, 200);
}
]);
//Auth group
Route::group(['middleware' => 'auth'], function() {
    /* User Block */
    Route::post('user/updatepassword', [
        'uses' => 'UserController@updatePassword',
        'as' => 'user.updatepassword'
    ]);
    /* End User Block */
    /* report  block end here */
    Route::group(['prefix' => 'adminreport'], function () {
        Route::get('/studenttest', [
            'uses' => 'AdminReportController@adminreportStudenttest',
            'as' => 'adminreport.studenttest'
        ]);
        Route::get('/classgap', [
            'uses' => 'AdminReportController@adminreportClassgap',
            'as' => 'adminreport.classgap'
        ]);
        Route::get('/schooloverview', [
            'uses' => 'AdminReportController@adminreportSchooloverview',
            'as' => 'adminreport.schooloverview'
        ]);
        Route::get('/classreport', [
            'uses' => 'AdminReportController@adminreportClassreport',
            'as' => 'adminreport.classreport'
        ]);
        Route::get('/schooloverview/export/{graph}', [
            'uses' => 'AdminReportController@adminreportSchooloverviewExport',
            'as' => 'adminreport.schooloverviewexport'
        ])->where('graph', '(classtestgraph|classprogressgraph)');
        
        Route::get('/classoverview/export/{graph}', [
            'uses' => 'AdminReportController@adminreportClassoverviewExport',
            'as' => 'adminreport.classoverviewexport'
        ])->where('graph', '(classtestgraph|classprogressgraph)');
        
        Route::post('/studentgraph', [
            'uses' => 'AdminReportController@adminreportStudentgraph',
            'as' => 'adminreport.studentgraph'
        ]);
        Route::post('/higgting', [
            'uses' => 'AdminReportController@adminreportHiggting',
            'as' => 'adminreport.higgting'
        ]);
        Route::post('/clstest', [
            'uses' => 'AdminReportController@adminreportClstest',
            'as' => 'adminreport.clstest'
        ]);
        Route::post('/createfile', [
            'uses' => 'AdminReportController@adminreportCreatefile',
            'as' => 'adminreport.createfile'
        ]);
    });

    Route::group(['prefix' => 'report'], function () {

        Route::get('/school', [
            'uses' => 'ReportController@schoolReport',
            'as' => 'report.school'
        ]);
        Route::get('/listschool', [
            'uses' => 'ReportController@listSchool',
            'as' => 'report.listschool'
        ]);
        Route::get('/student', [
            'uses' => 'ReportController@studentReport',
            'as' => 'report.student'
        ]);
        Route::get('/parent', [
            'uses' => 'ReportController@parentReport',
            'as' => 'report.parent'
        ]);
        Route::get('/reportDashboard', [
            'uses' => 'ReportController@reportDashboard',
            'as' => 'report.reportDashboard'
        ]);
        Route::get('/listparent', [
            'uses' => 'ReportController@listParent',
            'as' => 'report.listparent'
        ]);
        Route::get('/show/{id}', [

            'uses' => 'ReportController@showRevenue',
            'as' => 'report.show_revenue'
        ]);
        Route::get('/admin', [
            'uses' => 'ReportController@adminReport',
            'as' => 'report.admin'
        ]);
        Route::post('/classandstudent', [
            'uses' => 'ReportController@classandstudentReport',
            'as' => 'report.classandstudent'
        ]);

        Route::get('/revenue', [
            'uses' => 'ReportController@revenueReport',
            'as' => 'report.revenue'
        ]);
        Route::get('/listrevenue', [
            'uses' => 'ReportController@listRevenue',
            'as' => 'report.listrevenue'
        ]);
    });
    /* End report module */
});
/* Enquiry  Block */
Route::get('enquiry/listRecord', [
    'uses' => 'EnquiryController@listRecord',
    'as' => 'enquiry.listrecord'
]);
Route::get('send-enquiry', [
    'uses' => 'EnquiryController@sendenquiry',
    'as' => 'enquiry.sendenquiry'
]);
Route::get('enquiryconfirm', [
    'uses' => 'EnquiryController@enquiryconfirm',
    'as' => 'enquiry.enquiryconfirm'
]);


Route::post('enquiry/delete', [
    'uses' => 'EnquiryController@delete',
    'as' => 'enquiry.delete'
]);
Route::resource('enquiry', 'EnquiryController');
/* End Enquiry  */



Route::group(['middleware' => ['auth', 'role:' . SCHOOL . '']], function() {
    Route::get('manageaccount', [
        'uses' => 'SchoolController@editProfile',
        'as' => 'manageaccount'
    ]);
    Route::post('school/updateprofile', [
        'uses' => 'SchoolController@updateProfile',
        'as' => 'school.updateprofile'
    ]);

    /* Group  Block */
    Route::group(['prefix' => 'managegroup'], function () {
        Route::get('/listrecord', [
            'uses' => 'GroupController@listRecord',
            'as' => 'managegroup.listrecord'
        ]);
        Route::post('/delete', [
            'uses' => 'GroupController@delete',
            'as' => 'managegroup.delete'
        ]);
        Route::get('/grouptudents/{id}', [
            'uses' => 'GroupController@groupStudents',
            'as' => 'managegroup.grouptudents'
        ]);
        Route::get('/grouptudentslistrecord/{id}', [
            'uses' => 'GroupController@groupStudentListRecord',
            'as' => 'managegroup.grouptudentslistrecord'
        ]);
    });
    Route::resource('managegroup', 'GroupController');
    /* End Group  */

    /* class  block goes here */
    Route::group(['prefix' => 'manageclass'], function () {
        Route::get('/listrecord', [
            'uses' => 'SchoolClassController@listRecord',
            'as' => 'manageclass.listrecord'
        ]);
        Route::post('/delete', [
            'uses' => 'SchoolClassController@delete',
            'as' => 'manageclass.delete'
        ]);
        Route::get('/classstudents/{id}', [
            'uses' => 'SchoolClassController@classStudents',
            'as' => 'manageclass.classstudents'
        ]);
        Route::get('/classstudentslistrecord/{id}', [
            'uses' => 'SchoolClassController@classStudentListRecord',
            'as' => 'manageclass.classstudentslistrecord'
        ]);
        Route::post('/teacherclassstudent', [
            'uses' => 'SchoolClassController@teacherClassStudent',
            'as' => 'manageclass.teacherclassstudent'
        ]);
    });
    Route::resource('manageclass', 'SchoolClassController');
    /* class  block end here */



    Route::get('schooladmincreate', [
        'uses' => 'SchoolController@schooladmincreate',
        'as' => 'school.schooladmincreate'
    ]);
    Route::get('schooladmindelete', [
        'uses' => 'SchoolController@schooladmindelete',
        'as' => 'school.schooladmindelete'
    ]);
    Route::get('schooladmin', [
        'uses' => 'SchoolController@schooladmin',
        'as' => 'school.schooladmin'
    ]);
    Route::get('school/schooladminlist', [
        'uses' => 'SchoolController@schooladminlist',
        'as' => 'school.schooladminlist'
    ]);
    Route::post('school/schooladmindelete', [
        'uses' => 'SchoolController@schooladmindelete',
        'as' => 'school.delete'
    ]);
});

Route::group(['middleware' => ['auth', 'role:' . TEACHER . '']], function() {
    Route::get('manageprofile', [
        'uses' => 'TeacherController@editProfile',
        'as' => 'manageprofile'
    ]);
    Route::post('teacher/updateprofile', [
        'uses' => 'TeacherController@updateProfile',
        'as' => 'teacher.updateprofile'
    ]);
    /* Test Block */
    Route::group(['prefix' => 'managetest'], function () {
        Route::get('/listrecord', [
            'uses' => 'TestController@listRecord',
            'as' => 'managetest.listrecord'
        ]);
        Route::get('/print/{id}/{mode}/{paper}', [
            'uses' => 'TestController@printTest',
            'as' => 'managetest.print'
        ]);
        Route::post('/delete', [
            'uses' => 'TestController@delete',
            'as' => 'managetest.delete'
        ]);
    });
    Route::resource('managetest', 'TestController');
    /* end Test Block */

    Route::group(['prefix' => 'managetest'], function () {
        Route::get('/assigned', [
            'uses' => 'TestController@testAssigned',
            'as' => 'managetest.assigned'
        ]);
        Route::get('/assignedlistrecord', [
            'uses' => 'TestController@testAssignedListRecord',
            'as' => 'managetest.assignedlistrecord'
        ]);
        Route::get('/{id}/assigen', [
            'uses' => 'TestController@assigenTest',
            'as' => 'managetest.assignetest'
        ]);
        Route::put('/storeassign/{id}', [
            'uses' => 'TestController@storeTestAssign',
            'as' => 'managetest.storeassign'
        ]);
    });
    /* End Test block */

    /* Revision Block */
    Route::group(['prefix' => 'managerevision'], function () {
        Route::get('/listrecord', [
            'uses' => 'RevisionController@listRecord',
            'as' => 'managerevision.listrecord'
        ]);
        Route::get('/print/{id}/{mode}', [
            'uses' => 'RevisionController@printRevision',
            'as' => 'managerevision.print'
        ]);
        Route::get('/printform', [
            'uses' => 'RevisionController@printRevisionForm',
            'as' => 'managerevision.printform'
        ]);
        Route::post('/delete', [
            'uses' => 'RevisionController@delete',
            'as' => 'managerevision.delete'
        ]);
    });
    Route::resource('managerevision', 'RevisionController');
    /* end Revision Block */


    Route::get('/task/assignedstudent/{assignid}/{classgroupid?}', [
        'uses' => 'TaskController@assignedStudent',
        'as' => 'managetask.assignedstudent'
    ]);
    Route::get('/task/assignedstudentlist/{assignid}/{classgroupid?}', [
        'uses' => 'TaskController@assignedStudentList',
        'as' => 'managetask.assignedstudentlist'
    ]);
    
   
    
    Route::get('/print/{id}/{mode}/{paper}', [
            'uses' => 'TestController@printTest',
            'as' => 'managetest.printrevision'
        ]);
    /* Group  Block */
    Route::group(['prefix' => 'managegroup'], function () {
        Route::post('/teachergroupstudent', [
            'uses' => 'GroupController@teacherGroupStudent',
            'as' => 'managegroup.teachergroupstudent'
        ]);
    });
    /* End Group  */

    /* class  block goes here */
    Route::group(['prefix' => 'manageclass'], function () {
        Route::post('/teacherclassstudent', [
            'uses' => 'SchoolClassController@teacherClassStudent',
            'as' => 'manageclass.teacherclassstudent'
        ]);
    });
    /* class  block end here */

    Route::post('/questions/questionstrandsforcreaterevision', [
        'uses' => 'QuestionbuilderController@questionStrandsForCreateRevision',
        'as' => 'question.questionstrandsforcreaterevision'
    ]);
});

Route::group(['middleware' => ['auth', 'role:' . STUDENT . '']], function() {
    /* Task centre (My Task, My Test, My Revision) */
    Route::get('task/{taskstatus?}', array(
        'as' => 'studenttask.index',
        'uses' => 'StudentTaskController@task')
    )->where('taskstatus', '(pending|overdue|completed)?');

    Route::get('test', [
        'uses' => 'StudentTaskController@test',
        'as' => 'studenttask.test'
    ]);

    Route::get('test/{subject}', array(
        'as' => 'studenttask.testsubject',
        'uses' => 'StudentTaskController@testSubject')
    )->where('subject', '(maths|english)');

    Route::get('testpaper/{taskattemptid?}', [
        'uses' => 'StudentTaskController@testPaper',
        'as' => 'studenttask.testpaper'
    ]);

    Route::get('test/{option}/{taskorattempt}', array(
        'as' => 'studenttask.testpaperattempt',
        'uses' => 'StudentTaskController@testPaperDetail')
    )->where('option', '(paper|attempt)');

    Route::get('revision', [
        'uses' => 'StudentTaskController@revision',
        'as' => 'studenttask.revision'
    ]);

    Route::get('revision/{subject}', array(
        'as' => 'studenttask.revisionsubject',
        'uses' => 'StudentTaskController@revisionSubject')
    )->where('subject', '(maths|english)');

    Route::get('revision/{subject}/{strand}', array(
        'as' => 'studenttask.strand',
        'uses' => 'StudentTaskController@revisionStrand')
    )->where('subject', '(maths|english)');

    Route::get('revision/detail/{taskid}', [
        'uses' => 'StudentTaskController@revisionDetail',
        'as' => 'studenttask.revisiondetail'
    ]);
    /* End Task */

    /* Examination block) */
    Route::get('examination/test/{studentTestId}', [
        'uses' => 'ExaminationController@examinationTest',
        'as' => 'examination.test'
    ]);

    Route::get('examination/revision/{studentRevisionId}', [
        'uses' => 'ExaminationController@examinationRevision',
        'as' => 'examination.revision'
    ]);

    Route::post('examination/questionattempt', [
        'uses' => 'ExaminationController@questionAttempt',
        'as' => 'examination.questionattempt'
    ]);

    Route::post('examination/questionattemptcomplete', [
        'uses' => 'ExaminationController@questionAttemptComplete',
        'as' => 'examination.questionattemptcomplete'
    ]);
    
    Route::get('examination/migratetestreportdata', [
        'uses' => 'ExaminationController@migrateTestReportData',
        'as' => 'examination.migratetestreportdata'
    ]);
    
    Route::get('examination/migraterevisionreportdata', [
        'uses' => 'ExaminationController@migrateRevisionReportData',
        'as' => 'examination.migraterevisionreportdata'
    ]);
    
    Route::get('examination/migrateteststrandreportdata', [
        'uses' => 'ExaminationController@migrateTestStrandReportData',
        'as' => 'examination.migrateteststrandreportdata'
    ]);
    
    Route::get('examination/updatestudentbaselinedata', [
        'uses' => 'ExaminationController@updateStudentBaseLineData',
        'as' => 'examination.updatestudentbaselinedata'
    ]);
    
    Route::get('examination/updateTestAttemptCompletedate', [
        'uses' => 'ExaminationController@updateTestAttemptCompletedate',
        'as' => 'examination.migrateteststrandreportdata'
    ]);
    /* End Examination block */

    /* Result Block */
    Route::get('test/result/{attemptid?}', [
        'uses' => 'ResultController@testResultDetail',
        'as' => 'test.result'
    ]);

    Route::get('revision/result/{attemptid?}', [
        'uses' => 'ResultController@revisionResultDetail',
        'as' => 'revision.result'
    ]);

    Route::get('myresult', [
        'uses' => 'ResultController@myresult',
        'as' => 'result.myresult'
    ]);

    Route::get('myresult/{subject}', array(
        'as' => 'result.myresultsubject',
        'uses' => 'ResultController@myresultSubject')
    )->where('subject', '(maths|english)');

    Route::get('myresult/{subject}/test', [
        'uses' => 'ResultController@myresultTest',
        'as' => 'result.myresulttest'
    ])->where('subject', '(maths|english)');

    Route::get('myresult/{subject}/revision', [
        'uses' => 'ResultController@myresultRevision',
        'as' => 'result.myresultrevision'
    ])->where('subject', '(maths|english)');

    Route::get('myresult/testattemptresult/{attemptid?}', [
        'uses' => 'ResultController@testAttemptResult',
        'as' => 'result.testattemptresult'
    ]);

    Route::get('myresult/revisionattemptresult/{attemptid?}', [
        'uses' => 'ResultController@revisionAttemptResult',
        'as' => 'result.revisionattemptresult'
    ]);

    Route::get('myresult-revision-progresschart', [
        'uses' => 'ResultController@revisionProgress',
        'as' => 'result.revisionprogress'
    ]);
    
    Route::get('revision/examinationresult/{attemptid}', [
        'uses' => 'ResultController@examinationRevisionResult',
        'as' => 'revision.examinationresult'
    ]);
    Route::get('test/examinationresult/{attemptid}', [
        'uses' => 'ResultController@examinationTestResult',
        'as' => 'test.examinationresult'
    ]);
    /* End Result block */

    /* End Task */

    /* Help Centre Start */
    Route::get('help-centre', [
        'uses' => 'HelpCentreController@helpCentre',
        'as' => 'helpcentre.helpcentre'
    ]);

    Route::get('help-centre/{subject}/{strand?}', array(
        'as' => 'helpcentre.subject',
        'uses' => 'HelpCentreController@helpcentreSubject')
    )->where('subject', '(maths|english)');

  /*  Route::get('help-centre/{subject}/{strand}', array(
        'as' => 'helpcentre.strand',
        'uses' => 'HelpCentreController@helpcentreStrand')
    )->where('subject', '(maths|english)');
*/
    Route::get('help-centre-details', [
        'uses' => 'HelpCentreController@helpCentreDetails',
        'as' => 'helpcentre.details'
    ]);
    Route::post('save-student-rating', [
        'uses' => 'ResultController@saveStudentRating',
        'as' => 'result.savestudentrating'
    ]);

    Route::get('help-centre/detail/{strand}/{substrand}', [
        'uses' => 'HelpCentreController@helpcentreDetail',
        'as' => 'helpcentre.detail'
    ]);

    /* Help centre End Task */

    /* Help Myadward Start */
    Route::get('myawards', [
        'uses' => 'StudentAwardsController@myAwards',
        'as' => 'myawards.myawards'
    ]);
    Route::get('myawards', [
        'uses' => 'StudentAwardsController@myAwards',
        'as' => 'myawards.myawards'
    ]);

    Route::get('myawards/{subject}', array(
        'as' => 'myawards.subject',
        'uses' => 'StudentAwardsController@myAwardsSubject')
    )->where('subject', '(maths|english)');
    Route::get('printawards/{filename}', [
        'uses' => 'StudentAwardsController@printawards',
        'as' => 'myawards.printawards'
    ]);
    /* End myawards */
});

Route::group(['middleware' => ['auth', 'role:' . TUTOR . '']], function() {
    Route::get('myaccount', [
        'uses' => 'TutorController@editProfile',
        'as' => 'myaccount'
    ]);
    Route::post('tutor/updateprofile', [
        'uses' => 'TutorController@updateProfile',
        'as' => 'tutor.updateprofile'
    ]);
});


Route::group(['middleware' => ['auth', 'role:' . SCHOOL . ',' . TEACHER . ',' . TUTOR . '']], function() {
    Route::get('teacher/studentrewards', [
        'uses' => 'TeacherController@studentRewards',
        'as' => 'teacher.studentrewards'
    ]);
    Route::get('task/interventiontopics', [
        'uses' => 'TaskController@interventionTopics',
        'as' => 'task.interventiontopics'
    ]);
    Route::get('task/activitytopics', [
        'uses' => 'TaskController@activityTopics',
        'as' => 'task.activitytopics'
    ]);
    Route::get('task/dashboardtestresult', [
        'uses' => 'TaskController@dashboardtestResult',
        'as' => 'task.dashboardtestresult'
    ]);
    Route::post('addEvent', [
        'uses' => 'TeacherController@addEvent',
        'as' => 'addEvent.save'
    ]);
    Route::post('updateEvent', [
        'uses' => 'TeacherController@updateEvent',
        'as' => 'updateEvent.update'
    ]);
    Route::post('deleteEvent', [
        'uses' => 'TeacherController@deleteEvent',
        'as' => 'deleteEvent.delete'
    ]);
});
Route::group(['middleware' => ['auth', 'role:' . ADMIN]], function() {

    /* Question Admin Block */
    Route::get('questionadmin/listRecord', [
        'uses' => 'QuestionAdminController@listRecord',
        'as' => 'questionadmin.listrecord'
    ]);
    Route::post('questionadmin/delete', [
        'uses' => 'QuestionAdminController@delete',
        'as' => 'questionadmin.delete'
    ]);
    Route::resource('questionadmin', 'QuestionAdminController');
    /* End Question Admin Block */

    /* Question Validator Block */
    Route::get('questionvalidator/listRecord', [
        'uses' => 'QuestionValidatorController@listRecord',
        'as' => 'questionvalidator.listrecord'
    ]);
    Route::post('questionvalidator/delete', [
        'uses' => 'QuestionValidatorController@delete',
        'as' => 'questionvalidator.delete'
    ]);
    Route::resource('questionvalidator', 'QuestionValidatorController');
    /* End Question Validator Block */


    /* Notification  */
    Route::get('notification/listrecord', [
        'uses' => 'NotificationController@listRecord',
        'as' => 'notification.listrecord'
    ]);
    Route::post('notification/delete', [
        'uses' => 'NotificationController@delete',
        'as' => 'notification.delete'
    ]);
    Route::resource('notification', 'NotificationController');
    /* Notification end here */



    /* School Block */
    Route::get('school/listrecord', [
        'uses' => 'SchoolController@listRecord',
        'as' => 'school.listrecord'
    ]);
    Route::post('school/delete', [
        'uses' => 'SchoolController@delete',
        'as' => 'school.delete'
    ]);
    Route::resource('school', 'SchoolController');
    /* End School */

    /* Invoice Block */
    Route::get('invoice/listRecord', [
        'uses' => 'InvoiceController@listRecord',
        'as' => 'invoice.listrecord'
    ]);
    Route::get('invoice/print/{id}', [
        'uses' => 'InvoiceController@prints',
        'as' => 'invoice.print'
    ]);
    Route::resource('invoice', 'InvoiceController');
    /* End Invoice Block */

    /* Voucher  Block */
    Route::get('voucher/listrecord', [
        'uses' => 'VoucherController@listRecord',
        'as' => 'voucher.listrecord'
    ]);
    Route::post('voucher/delete', [
        'uses' => 'VoucherController@delete',
        'as' => 'voucher.delete'
    ]);

    Route::resource('voucher', 'VoucherController');
    /* End Voucher  */
    /* Parent/tutor Block */
    Route::get('payment/listrecord', [
        'uses' => 'PaymentController@listRecord',
        'as' => 'payment.listrecord'
    ]);
    Route::resource('payment', 'PaymentController');
    Route::get('tutor/{id}/updatePaymentStatus', [
        'uses' => 'TutorController@updatePaymentStatus',
        'as' => 'tutor.updatepaymentstatus'
    ]);
    Route::get('tutor/listrecord', [
        'uses' => 'TutorController@listRecord',
        'as' => 'tutor.listrecord'
    ]);
    Route::post('tutor/delete', [
        'uses' => 'TutorController@delete',
        'as' => 'tutor.delete'
    ]);
    Route::resource('tutor', 'TutorController');
    /* End Parent/tutor Block */

    /* email template block */
    Route::get('emailtemplate/listrecord', [
        'uses' => 'EmailTemplatesController@listRecord',
        'as' => 'emailtemplate.listrecord'
    ]);
    Route::post('emailtemplate/delete', [
        'uses' => 'EmailTemplatesController@delete',
        'as' => 'emailtemplate.delete'
    ]);
    Route::resource('emailtemplate', 'EmailTemplatesController');
    /* email template block end here */
    /* CMS page Block Goes here */
    Route::get('cmspage/listrecord', [
        'uses' => 'CmsPagesController@listRecord',
        'as' => 'cmspage.listrecord'
    ]);
    Route::post('cmspage/delete', [
        'uses' => 'CmsPagesController@delete',
        'as' => 'cmspage.delete'
    ]);
    Route::resource('cmspage', 'CmsPagesController');
    /* CMS page Block End here */

    /* Question set Block */
    Route::get('questionset/listrecord', [
        'uses' => 'QuestionSetController@listRecord',
        'as' => 'questionset.listrecord'
    ]);
    Route::post('questionset/delete', [
        'uses' => 'QuestionSetController@delete',
        'as' => 'questionset.delete'
    ]);
    Route::resource('questionset', 'QuestionSetController');
    /* End Question set */

    /* fee block */
    Route::get('fees/listrecord', [
        'uses' => 'FeesController@listRecord',
        'as' => 'fees.listrecord'
    ]);
    Route::resource('fees', 'FeesController');
    /* fee block end */

    /* Payment block */
    Route::get('paymentmethod/listrecord', [
        'uses' => 'PaymentMethodsController@listRecord',
        'as' => 'paymentmethod.listrecord'
    ]);
    Route::resource('paymentmethod', 'PaymentMethodsController');
    /* fee block end */

    /* Strand section */
    Route::post('strand/delete', [
        'uses' => 'StrandController@delete',
        'as' => 'strand.delete'
    ]);
    Route::resource('strand', 'StrandController');
    /* Strand section end here */
});
Route::group(['middleware' => ['auth', 'role:' . ADMIN . ',' . QUESTIONADMIN . ',' . QUESTIONVALIDATOR . '']], function() {
    /* Admin Block */
    Route::get('myprofile', [
        'uses' => 'AdminController@editProfile',
        'as' => 'myprofile'
    ]);
    Route::post('admin/updateprofile', [
        'uses' => 'AdminController@updateProfile',
        'as' => 'admin.updateprofile'
    ]);
    /* End Admin Block */
    /* Question builder Block */
Route::get('questionbuilder/changeQuestionIdData', [
        'uses' => 'QuestionbuilderController@changeQuestionIdData',
        'as' => 'QuestionbuilderController.changeQuestionIdData'
    ]);

    
    Route::get('questionbuilder/update12', [
        'uses' => 'QuestionbuilderController@update12',
        'as' => 'questionbuilder.update12'
    ]);
    Route::get('questionbuilder/listrecord', [
        'uses' => 'QuestionbuilderController@listRecord',
        'as' => 'questionbuilder.listrecord'
    ]);

    Route::post('questionbuilder/uploadimage', [
        'uses' => 'QuestionbuilderController@uploadImage',
        'as' => 'questionbuilder.uploadimage'
    ]);
    Route::post('questionbuilder/uploadaudio', [
        'uses' => 'QuestionbuilderController@uploadAudio',
        'as' => 'questionbuilder.uploadaudio'
    ]);
    Route::post('questionbuilder/updatestatus', [
        'uses' => 'QuestionbuilderController@updateStatus',
        'as' => 'questionbuilder.updatestatus'
    ]);
    Route::post('questionbuilder/uploadeditorfile', [
        'uses' => 'QuestionbuilderController@uploadEditorFile',
        'as' => 'questionbuilder.uploadimage'
    ]);
    Route::post('questionbuilder/questionautonumber', [
        'uses' => 'QuestionbuilderController@questionAutoNumberumber',
        'as' => 'questionbuilder.questionautonumber'
    ]);
    Route::post('validatequestion', [
        'uses' => 'QuestionbuilderController@validateQuestion',
        'as' => 'questionbuilder.validatequestion'
    ]);
    Route::resource('questionbuilder', 'QuestionbuilderController');

    Route::get('qb_singlechoice', function() {
        return View::make('admin.questionbuilder.singlechoice');
    });
    Route::get('qb_fillintheblanks', function() {
        return View::make('admin.questionbuilder.fillintheblanks');
    });
    Route::get('qb_multiplechoice', function() {
        return View::make('admin.questionbuilder.multiplechoice');
    });
    Route::get('qb_matchdragdrop', function() {
        return View::make('admin.questionbuilder.matchdragdrop');
    });
    Route::get('qb_measurement', function() {
        return View::make('admin.questionbuilder.measurement');
    });
    Route::get('qb_simplequestion', function() {
        return View::make('admin.questionbuilder.simplequestion');
    });
    Route::get('qb_selectliteracy', function() {
        return View::make('admin.questionbuilder.selectliteracy');
    });
    Route::get('qb_labelliteracy', function() {
        return View::make('admin.questionbuilder.labelliteracy');
    });
    Route::get('qb_insertliteracy', function() {
        return View::make('admin.questionbuilder.insertliteracy');
    });
    Route::get('qb_wordonimage', function() {
        return View::make('admin.questionbuilder.wordonimage');
    });
    Route::get('qb_underlineliteracy', function() {
        return View::make('admin.questionbuilder.underlineliteracy');
    });
    Route::get('qb_numberwordselect', function() {
        return View::make('admin.questionbuilder.numberwordselect');
    });
    Route::get('qb_singlemultipleentry', function() {
        return View::make('admin.questionbuilder.singlemultipleentry');
    });
    Route::get('qb_dragdrop', function() {
        return View::make('admin.questionbuilder.dragdrop');
    });
    Route::get('qb_drawinggraph', function() {
        return View::make('admin.questionbuilder.drawinggraph');
    });
    Route::get('qb_boolean', function() {
        return View::make('admin.questionbuilder.boolean');
    });
    Route::get('qb_spellingaudio', function() {
        return View::make('admin.questionbuilder.spellingaudio');
    });
    Route::get('qb_drawlineonimage', function() {
        return View::make('admin.questionbuilder.drawlineonimage');
    });
    Route::get('qb_joiningdots', function() {
        return View::make('admin.questionbuilder.joiningdots');
    });
    /* Route::get('qb_reflection', function() {
      return View::make('admin.questionbuilder.reflection');
      }); */
    Route::get('qb_shadingshape', function() {
        return View::make('admin.questionbuilder.shadingshape');
    });
    Route::get('qb_reflectionrightleft', function() {
        return View::make('admin.questionbuilder.reflectionrightleft');
    });
    Route::get('qb_reflectionleftright', function() {
        return View::make('admin.questionbuilder.reflectionleftright');
    });
    Route::get('qb_reflectionbottomtop', function() {
        return View::make('admin.questionbuilder.reflectionbottomtop');
    });
    Route::get('qb_reflectiontopbottom', function() {
        return View::make('admin.questionbuilder.reflectiontopbottom');
    });
    Route::get('qb_reflectionleftdiagonal', function() {
        return View::make('admin.questionbuilder.reflectionleftdiagonal');
    });
    Route::get('qb_reflectionrightdiagonal', function() {
        return View::make('admin.questionbuilder.reflectionrightdiagonal');
    });
    Route::get('qb_measurementlineangle', function() {
        return View::make('admin.questionbuilder.measurementlineangle');
    });
    Route::get('qb_piechart', function() {
        return View::make('admin.questionbuilder.piechart');
    });
    Route::get('qb_symmetric', function() {
        return View::make('admin.questionbuilder.symmetric');
    });
    Route::get('qb_inputonimage', function() {
        return View::make('admin.questionbuilder.inputonimage');
    });
    Route::get('qb_tableinputentry', function() {
        return View::make('admin.questionbuilder.tableinputentry');
    });
});

Route::group(['middleware' => ['auth', 'role:' . ADMIN . ',' . QUESTIONADMIN . '']], function() {

    Route::post('questionbuilder/delete', [
        'uses' => 'QuestionbuilderController@delete',
        'as' => 'questionbuilder.delete'
    ]);
    /* End Question builder */
});
Route::group(['middleware' => ['auth', 'role:' . ADMIN . ',' . SCHOOL . '']], function() {
    /* Teacher Block */
    Route::get('teacher/listrecord', [
        'uses' => 'TeacherController@listRecord',
        'as' => 'teacher.listrecord'
    ]);
    Route::post('teacher/delete', [
        'uses' => 'TeacherController@delete',
        'as' => 'teacher.delete'
    ]);
    Route::resource('teacher', 'TeacherController');
    /* end Teacher Block */
});
Route::group(['middleware' => ['auth', 'role:' . ADMIN . ',' . SCHOOL . ',' . TEACHER . ',' . TUTOR . '']], function() {
    /* student Block */
    Route::get('student/listrecord', [
        'uses' => 'StudentController@listRecord',
        'as' => 'student.listrecord'
    ]);
    Route::post('student/delete', [
        'uses' => 'StudentController@delete',
        'as' => 'student.delete'
    ]);
    Route::resource('student', 'StudentController');
    /* end student Block */

    /* student Award section Here */
    Route::get('studentaward/listrecord', [
        'uses' => 'StudentAwardsController@listRecord',
        'as' => 'studentaward.listrecord'
    ]);
    Route::post('studentaward/delete', [
        'uses' => 'StudentAwardsController@delete',
        'as' => 'studentaward.delete'
    ]);
    Route::post('studentaward/uploadimage', [
        'uses' => 'StudentAwardsController@uploadImage',
        'as' => 'studentaward.uploadimage'
    ]);
    Route::get('importstudent/{id}', [
        'uses' => 'StudentController@importStudent',
        'as' => 'importstudent.index'
    ]);
    Route::post('importstudentstore', [
        'uses' => 'StudentController@importStudentStore',
        'as' => 'importstudent.store'
    ]);
    Route::resource('studentaward', 'StudentAwardsController');
    /* student Award section end Here */

    /* Reward Block */
    Route::get('showcertificatepreviewpmage/{image}', [
        'uses' => 'RewardController@imagePreview',
        'as' => 'rewards.imagepreview'
    ]);

    Route::group(['prefix' => 'rewards'], function () {
        Route::get('/{task}', [
            'uses' => 'RewardController@index',
            'as' => 'rewards.index'
        ])->where('task', '(test|revision)');

        Route::get('/{task}/listrecord', [
            'uses' => 'RewardController@listRecord',
            'as' => 'rewards.listrecord'
        ])->where('task', '(test|revision)');

        Route::get('/{task}/create', [
            'uses' => 'RewardController@create',
            'as' => 'rewards.create'
        ])->where('task', '(test|revision)');

        Route::get('/{task}/{id}/edit', [
            'uses' => 'RewardController@edit',
            'as' => 'rewards.edit'
        ])->where('task', '(test|revision)');

        Route::put('/{task}/{id}', [
            'uses' => 'RewardController@update',
            'as' => 'rewards.update'
        ]);
        Route::post('/{task}', [
            'uses' => 'RewardController@store',
            'as' => 'rewards.store'
        ]);
        Route::post('/rewards/delete', [
            'uses' => 'RewardController@delete',
            'as' => 'rewards.delete'
        ])->where('task', '(test|revision)');
    });

    /* Help Centre */
    Route::get('helpcentre/listrecord', [
        'uses' => 'HelpCentreController@listRecord',
        'as' => 'helpcentre.listrecord'
    ]);

    Route::post('helpcentre/delete', [
        'uses' => 'HelpCentreController@delete',
        'as' => 'helpcentre.delete'
    ]);
    Route::post('helpcentreSearch', [
        'uses' => 'HelpCentreController@helpcentreSearch',
        'as' => 'helpcentre.helpcentresearch'
    ]);
    Route::post('selectStrand', [
        'uses' => 'HelpCentreController@selectStrand',
        'as' => 'helpcentre.selectStrand'
    ]);
    Route::post('selectSubStrand', [
        'uses' => 'HelpCentreController@selectSubStrand',
        'as' => 'helpcentre.selectSubStrand'
    ]);
    Route::resource('helpcentre', 'HelpCentreController');
    /* Help Centre end here */


    Route::get('upgradeaccount/{id}', [
        'uses' => 'SchoolController@upgradeAccount',
        'as' => 'user.upgradeaccount'
    ]);
    Route::get('renewaccount/{id}', [
        'uses' => 'SchoolController@renewaccount',
        'as' => 'user.renewaccount'
    ]);
    Route::put('user/{id}/upgrade', [
        'uses' => 'SchoolController@userupgradeAccount',
        'as' => 'user.upgrade'
    ]);
    Route::put('user/{id}/renew', [
        'uses' => 'SchoolController@userrenewAccount',
        'as' => 'user.renew'
    ]);
    Route::post('paymentsuccessful/{id}', [
        'uses' => 'SchoolController@paymentSuccessful',
        'as' => 'user.paymentSuccessful'
    ]);

    /* Message create and post block */
    Route::group(['prefix' => 'messages', 'before' => 'auth'], function () {
        Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
        Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
    });
    /* Message create and post end Here */
});
Route::group(['middleware' => ['auth', 'role:' . ADMIN . ',' . SCHOOL . ',' . TEACHER . ',' . TUTOR . ',' . STUDENT . '']], function() {
    /* Message block */
    Route::group(['prefix' => 'messages'], function () {
        Route::get('/', ['as' => 'messages.inbox', 'uses' => 'MessagesController@inbox']);
        Route::get('/inbox', ['as' => 'messages.inbox', 'uses' => 'MessagesController@inbox']);
        Route::get('/listrecordinbox', ['as' => 'messages.listrecordinbox', 'uses' => 'MessagesController@listRecordInbox']);
        Route::get('/sent', ['as' => 'messages.sent', 'uses' => 'MessagesController@sent']);
        Route::get('/listrecordsent', ['as' => 'messages.listrecordsent', 'uses' => 'MessagesController@listRecordSent']);
        Route::get('{id}/read', ['as' => 'messages.read', 'uses' => 'MessagesController@read']);
        Route::get('unread', ['as' => 'messages.unread', 'uses' => 'MessagesController@unread']);
        Route::get('{message}/{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show'])->where('message', '(inbox|sent)');
        Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
        Route::post('/delete', ['as' => 'messages.delete', 'uses' => 'MessagesController@delete']);
    });

    Route::get('help-view/{id}', [
        'uses' => 'HelpCentreController@helpCentreView',
        'as' => 'helpcentre.helpcentreview'
    ]);
    /* Message block End here */
});
Route::get('signup', [
    'uses' => 'FrontSchoolController@create',
    'as' => 'frontschool.create'
]);
Route::post('frontStore', [
    'uses' => 'FrontSchoolController@store',
    'as' => 'frontschool.store'
]);
Route::get('signupconfirm/{payment_type}/{user_id}/{invoice_id}', [
    'uses' => 'FrontSchoolController@confirm',
    'as' => 'frontschool.signupconfirm'
]);

Route::get('termsconditions', function() {
    return View::make('front.tutor.termsconditions');
});
Route::get('privacypolicy', function() {
    return View::make('front.tutor.privacypolicy');
});
//Route::post('termsconditions', [
//    'uses' => 'FrontTutorController@termsconditions',
  //  'as' => 'termsconditions'
//]);
// Route::get('register', [
//     'uses' => 'FrontTutorController@create',
//     'as' => 'fronttutor.create'
// ]);
Route::get('register', [
    'uses' => 'RegisterController@create',
    'as' => 'student.register'
]);
Route::post('register', [
    'uses' => 'RegisterController@store',
    'as' => 'student.register'
]);
Route::get('{user}/verify', [
    'uses' => 'RegisterController@verify',
    'as' => 'student.verify'
]);
Route::get('register/verify/{confirmationCode}', [
    'as' => 'confirmation_path',
    'uses' => 'RegisterController@confirm'
]);
Route::get('register/resend/{confirmationCode}', [
    'as' => 'resend_code',
    'uses' => 'RegisterController@resend'
]);
Route::post('frontTutorStore', [
    'uses' => 'FrontTutorController@store',
    'as' => 'fronttutor.store'
]);
Route::get('register_payment', [
    'uses' => 'FrontTutorController@payment',
    'as' => 'fronttutor.payment'
]);
Route::get('signup_payment', [
    'uses' => 'FrontTutorController@payment',
    'as' => 'frontschool.payment'
]);

Route::post('make_payment', [
    'uses' => 'FrontTutorController@makePayment',
    'as' => 'fronttutor.make_payment'
]);
Route::post('update_subscription_make_payment', [
    'uses' => 'SchoolController@updateSubscriptionMakePayment',
    'as' => 'school.update_subscription_make_payment'
]);
Route::post('renew_subscription_make_payment', [
    'uses' => 'SchoolController@renewSubscriptionMakePayment',
    'as' => 'school.renew_subscription_make_payment'
]);
Route::get('registerconfirm/{payment_type}', [
    'uses' => 'FrontTutorController@confirm',
    'as' => 'fronttutor.signupconfirm'
]);

/* Question render Block */
Route::get('qb_singlechoicerender', function() {
    return View::make('front.examination.singlechoicerender');
});
Route::get('qb_fillintheblanksrender', function() {
    return View::make('front.examination.fillintheblanksrender');
});
Route::get('qb_multiplechoicerender', function() {
    return View::make('front.examination.multiplechoicerender');
});
Route::get('qb_matchdragdroprender', function() {
    return View::make('front.examination.matchdragdroprender');
});
Route::get('qb_measurementrender', function() {
    return View::make('front.examination.measurementrender');
});
Route::get('qb_simplequestionrender', function() {
    return View::make('front.examination.simplequestionrender');
});
Route::get('qb_selectliteracyrender', function() {
    return View::make('front.examination.selectliteracyrender');
});
Route::get('qb_labelliteracyrender', function() {
    return View::make('front.examination.labelliteracyrender');
});
Route::get('qb_insertliteracyrender', function() {
    return View::make('front.examination.insertliteracyrender');
});
Route::get('qb_wordonimagerender', function() {
    return View::make('front.examination.wordonimagerender');
});
Route::get('qb_underlineliteracyrender', function() {
    return View::make('front.examination.underlineliteracyrender');
});
Route::get('qb_numberwordselectrender', function() {
    return View::make('front.examination.numberwordselectrender');
});
Route::get('qb_singlemultipleentryrender', function() {
    return View::make('front.examination.singlemultipleentryrender');
});
Route::get('qb_dragdroprender', function() {
    return View::make('front.examination.dragdroprender');
});
Route::get('qb_drawinggraphrender', function() {
    return View::make('front.examination.drawinggraphrender');
});
Route::get('qb_booleanrender', function() {
    return View::make('front.examination.booleanrender');
});
Route::get('qb_spellingaudiorender', function() {
    return View::make('front.examination.spellingaudiorender');
});
Route::get('qb_joiningdotsrender', function() {
    return View::make('front.examination.joiningdotsrender');
});
Route::get('qb_drawinggraphrender', function() {
    return View::make('front.examination.drawinggraphrender');
});
Route::get('qb_drawlineonimagerender', function() {
    return View::make('front.examination.drawlineonimagerender');
});
/* Route::get('qb_reflectionrender', function() {
  return View::make('front.examination.reflectionrender');
  }); */
Route::get('qb_reflectionrightleftrender', function() {
    return View::make('front.examination.reflectionrightleftrender');
});
Route::get('qb_reflectionleftrightrender', function() {
    return View::make('front.examination.reflectionleftrightrender');
});
Route::get('qb_shadingshaperender', function() {
    return View::make('front.examination.shadingshaperender');
});
Route::get('qb_reflectionbottomtoprender', function() {
    return View::make('front.examination.reflectionbottomtoprender');
});
Route::get('qb_reflectiontopbottomrender', function() {
    return View::make('front.examination.reflectiontopbottomrender');
});
Route::get('qb_reflectionleftdiagonalrender', function() {
    return View::make('front.examination.reflectionleftdiagonalrender');
});
Route::get('qb_reflectionrightdiagonalrender', function() {
    return View::make('front.examination.reflectionrightdiagonalrender');
});
Route::get('qb_measurementlineanglerender', function() {
    return View::make('front.examination.measurementlineanglerender');
});
Route::get('qb_piechartrender', function() {
    return View::make('front.examination.piechartrender');
});
Route::get('qb_symmetricrender', function() {
    return View::make('front.examination.symmetricrender');
});
Route::get('qb_inputonimagerender', function() {
    return View::make('front.examination.inputonimagerender');
});
Route::get('qb_tableinputentryrender', function() {
    return View::make('front.examination.tableinputentryrender');
});

