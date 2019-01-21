<?php 

/**
 * This controller is used for Report.
 * @package    Report
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;
use App\Repositories\ReportRepository;
use App\Repositories\UserRepository;
use App\Repositories\GroupClassRepository;
use Illuminate\Http\Request;
use App\Repositories\StrandRepository;
use Datatables;
use App\Models\User;
use DB;
use App\Models\Questionset;
/*
 * This controller is used for Report.
 * @author     Icreon Tech - dev5.
 */

class AdminReportController extends Controller {

    /**
     * The ReportRepository instance.
     *
     * @var App\Repositories\ReportRepository
     */
    protected $reportRepo;

    /**
     * The UserRepository instance.
     *
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;

    /**
     * The GroupClassRepository instance.
     *
     * @var App\Repositories\GroupClassRepository
     */
    protected $groupClassRepo;

    /**
     * Create a new ReportController instance.
     *
     * @param  App\Repositories\UserRepository reportRepo
     * @return void
     */
    public function __construct(ReportRepository $reportRepo, UserRepository $userRepo, GroupClassRepository $groupClassRepo) {
        $this->reportRepo = $reportRepo;
        $this->userRepo = $userRepo;
        $this->groupClassRepo = $groupClassRepo;
    }

    /**
     * 
     * @param type $id
     * @return Response
     */
    public function index() {
        echo "Dgfd"; die;
    }
    
    public function adminreportStudenttest(Request $request) { 
        $data = array();
        $user = User::findOrFail($request->input('id'))->toArray();
        if(!empty($request->input('layout'))){
            $data['layout'] = $request->input('layout');
        }else{
            $data['layout'] = 'normal';
        }
        $data['footer_layout'] = $data['layout'];
        $data['studentId'] = $request->input('id');
        $data['studentId'] = $request->input('id');
        $data['testtype'] = $request->input('report');
        $data['tab'] = $request->input('tab');
        $data['subject'] = ($request->input('tab') == 'eng') ? "English" : "Math";
        $data['report'] = ($request->input('report') == 'test') ? "Test" : "Revision";
        $data['sortby'] = ($request->input('sortby') == 'asc') ? "asc" : "de";
        $data['tutor_id'] = $user['tutor_id'];
        if(!empty($request->input('sortby'))){
            $data['sortby'] = $request->input('sortby');
        }else{
            $data['sortby'] = 'asc';
        }
        $data['studentDetail'] = $this->reportRepo->studentDetail(array('studentId' => $data['studentId']));
        $data['graph'] = $this->reportRepo->studentGraph($data);
        //asd($data['graph'],0);
        $data['graph'] = $this->reportRepo->studentTestGraph($data);
        //asd($data['graph']);
        if ($data['report'] == 'Test') {
            $reportResultMath = $this->reportRepo->pupilOverviewtest(array(
                'studentId' => $data['studentId'],
                'subject' => MATH
            ));
            $reportResultEnglish = $this->reportRepo->pupilOverviewtest(array(
                'studentId' => $data['studentId'],
                'subject' => ENGLISH
            ));
            $reportResult = compact('reportResultMath','reportResultEnglish');
            $data['reportResult'] = $reportResult;
            //asd($data);
            return view('admin.adminreport.pupiloverview_test', $data);
        } else {
            $data['showTestGraph'] = true;
            $reportResult = $this->reportRepo->studentRevisionReport($data);
            $data['reportResult'] = $reportResult['grid'];
            return view('admin.adminreport.pupiloverview_topic', $data);
        }
        
    }

    public function adminreportStudenttestOld(Request $request) { 
        $data = array();
        if(!empty($request->input('layout'))){
            $data['layout'] = $request->input('layout');
        }else{
            $data['layout'] = 'normal';
        }
        $data['studentId'] = $request->input('id');
        $testtype = $request->input('report');
        if(!empty($request->input('sortby'))){
            $data['sortby'] = $request->input('sortby');
        }else{
            $data['sortby'] = 'asc';
        }
        $data['studentDetail'] = $this->reportRepo->studentDetail(array('studentId' => $data['studentId']));
        if(!empty($testtype)){ 
            $data['tab'] = $request->input('tab');
            $data['report'] = ($testtype == 'test') ? "Test" : "Revision";
            $data['subject'] = ($request->input('tab') == 'eng') ? "English" : "Math";
            $data['testtype'] = $testtype;
            if ($data['report'] == 'Test') {
                $reportResultMath = $this->reportRepo->pupilOverviewtest(array(
                    'studentId' => $data['studentId'],
                    'subject' => MATH
                ));
                
                $reportResultEnglish = $this->reportRepo->pupilOverviewtest(array(
                    'studentId' => $data['studentId'],
                    'subject' => ENGLISH
                ));
                $reportResult = compact('reportResultMath','reportResultEnglish');
                $data['reportResult'] = $reportResult;
                return view('admin.adminreport.pupiloverview_test', $data);
            } else{ 
                $testReportResult = $this->reportRepo->studentTestReport($data);
                $reportResult = $this->reportRepo->studentRevisionReport($data);
                $data['reportResult'] = $reportResult['grid'];
                $data['studentDetail'] = $testReportResult['studentDetail'];
                $data['graph'] = $testReportResult['graph'];
                return view('admin.adminreport.pupiloverview_topic', $data);
            }
        }else{
            $data['testtype'] = 'test';
            $data['graph'] = $this->reportRepo->studentGraph($data);
            return view('admin.adminreport.pupiloverview', $data);
        }
        
        
    }

    public function adminreportClassgap(Request $request,StrandRepository $strandRepo) { 
        $data = array();
        $schoolId = $request->input('schoolId');
        $classId = $request->input('classId');
        $data['schoolId'] = $schoolId;
        $data['classId'] = $classId;
        if(!empty($request->input('schoolId')) && !empty($request->input('classId')) && !empty($request->input('question_set')) && !empty($request->input('paper_id'))){
            $schoolClassStudents = array();
            $schoolClassStudents = $this->groupClassRepo->getSchoolClassStudents($classId);
            $data['schoolClassStudents'] = $schoolClassStudents;
            if(count($schoolClassStudents)){
                foreach($schoolClassStudents as $cStudents){
                    $sids[] = $cStudents['id'];
                }
                //get strands
                $arrStrandsData = $strandRepo->getstrandTree(FALSE);
                $data['arrStrands'] = $arrStrandsData['strands'][$request->input('subject')];
                $data['arrSubStrands'] = $arrStrandsData['substrands'];
                $pData = array(
                    'set_id' => $request->input('question_set'),
                    'paper_id' => $request->input('paper_id')
                );
                //get set question from question table
                $data['setQuestions'] = $this->reportRepo->getSetQuestions($pData);
                $pData['student_ids'] = $sids;
                //get gap data (from answer table)
                $classGapReportData = $this->reportRepo->classGapReportData($pData);
                $studentQuestionAttempt = array();
                if(count($classGapReportData)){
                    foreach($classGapReportData as $crRow){
                        $studentQuestionAttempt[$crRow['student_id']][$crRow['question_id']] = $crRow['mark_obtain'];
                    }
                }
                $data['studentQuestionAttempt'] = $studentQuestionAttempt;
            }
            $data['layout'] = 'iframe';
            $data['footer_layout'] = $data['layout'];
            return view('admin.adminreport.classgapreport', $data);
        }
        $questionsetsMath = Questionset::orderBy('set_name')->where('subject','=',MATH)->where('status','!=',DELETED)->lists('set_name', 'id')->toArray();
        $questionsetsEnglish = Questionset::orderBy('set_name')->where('subject','=',ENGLISH)->where('status','!=',DELETED)->lists('set_name', 'id')->toArray();
        $data['question_sets'] = array('Math' => $questionsetsMath,'English' => $questionsetsEnglish);
        $data['papers'] = subjectPapers();
        if(!empty($request->input('layout'))){
            $data['layout'] = $request->input('layout');
        }else{
            $data['layout'] = 'normal';
        }
        $data['footer_layout'] = $data['layout'];        
        return view('admin.adminreport.classgap', $data);
    }

    public function adminreportSchooloverview(Request $request) { 
        $data = array();
        $schoolId = $request->input('id');
        $schoolDetail = $this->userRepo->getUser(array(
                    'id' => $schoolId,
                    'user_type' => SCHOOL
                ))->get()->first();
        if (!count($schoolDetail)) {
            return redirect('error404');
        }
        $schoolDetail = $schoolDetail->toArray();
        $data['schoolId'] = $schoolId;
        $data['testtype'] = $request->input('report');
        $data['tab'] = $request->input('tab');
        $data['subject'] = ($request->input('tab') == 'eng') ? "English" : "Math";
        $data['report'] = ($request->input('report') == 'test') ? "Test" : "Revision";
        
        if(!empty($request->input('setId'))){
            $data['setId'] = $request->input('setId');
        }else{
            $data['setId'] = "";
        }
        if(!empty($request->input('paperId'))){
            $data['paperId'] = $request->input('paperId');
        }else{
            $data['paperId'] = "";
        }
        if(!empty($request->input('layout'))){
            $data['layout'] = $request->input('layout');
        }else{
            $data['layout'] = 'normal';
        }
        
        $data['footer_layout'] = $data['layout'];
                
                
        if ($data['testtype'] == 'dashboard') {
            $dashboarddetail = $this->reportRepo->schoolReort($data);
            $data['dashboarddetail'] = $dashboarddetail;
            return view('admin.adminreport.schooloverview_dashboard', $data);
        } else if ($data['testtype'] == 'test') {
            if(empty($request->input('setId'))){
                $dashboarddetailRes = $this->reportRepo->schoolTestReport($data);                
                if(!empty($dashboarddetailRes['satArray']))
                    $data['setId'] = $dashboarddetailRes['satArray'][0]->id;
                if($data['subject'] == 'English')
                    $data['paperId'] = '4';
                else if($data['subject'] == 'Math')
                    $data['paperId'] = '1';

            }
            $dashboarddetail = $this->reportRepo->schoolTestReport($data);
            
            $data['dashboarddetail'] = $dashboarddetail;
            return view('admin.adminreport.schooloverview_test', $data);
        } else if ($data['testtype'] == 'topic') {
            if (!empty($request->input('strand'))) {
                $data['strand'] = $request->input('strand');
            }
            if (!empty($request->input('substrand'))) {
                $data['substrand'] = $request->input('substrand');
            }
            $dashboarddetail = $this->reportRepo->schoolTopicReport($data);
            $data['dashboarddetailTopic'] = $dashboarddetail;
            return view('admin.adminreport.schooloverview_topic', $data);
        }
       
        $data['trait'] = array('trait_1' => trans('admin/school.school'), 'trait_1_link' => route('school.index'), 'trait_2' => $schoolDetail['school_name'] . ' : ' . trans('School Overview Report'));
        
//        asd($data);
        return view('admin.adminreport.schooloverview', $data);
    }

    public function adminreportClassreport(Request $request) { 
        $data = array();
        $schoolId = $request->input('schoolId');
        $classId = $request->input('classId');
        $data['schoolId'] = $schoolId;
        $data['classId'] = $classId;
        $classId = $request->input('id');
        $data['testtype'] = $request->input('report');
        $data['tab'] = $request->input('tab');
        $data['subject'] = ($request->input('tab') == 'eng') ? "English" : "Math";
        $data['report'] = ($request->input('report') == 'test') ? "Test" : "Revision";
        
        
        if(!empty($request->input('setId'))){
            $data['setId'] = $request->input('setId');
        }else{
            $data['setId'] = "";
        }
        if(!empty($request->input('paperId'))){
            $data['paperId'] = $request->input('paperId');
        }else{
            $data['paperId'] = "";
        }
        
        if(!empty($request->input('layout'))){
            $data['layout'] = $request->input('layout');
        }else{
            $data['layout'] = 'normal';
        }
        $data['footer_layout'] = $data['layout'];
        if ($data['testtype'] == 'dashboard') {
            $dashboarddetail = $this->reportRepo->classReport($data);
            $data['dashboarddetail'] = $dashboarddetail;
            return view('admin.adminreport.classoverview_dashboard', $data);
        } else if ($data['testtype'] == 'test') {
            
            if(empty($request->input('setId'))){
                $dashboarddetailRes = $this->reportRepo->classTestReport($data);
                if(!empty($dashboarddetailRes['satArray']))
                    $data['setId'] = $dashboarddetailRes['satArray'][0]->id;
                
                if($data['subject'] == 'English')
                    $data['paperId'] = '4';
                else if($data['subject'] == 'Math')
                    $data['paperId'] = '1';

            }
            $dashboarddetail = $this->reportRepo->classTestReport($data);
            //asd($dashboarddetail);
            $data['dashboarddetail'] = $dashboarddetail;
            return view('admin.adminreport.classoverview_test', $data);
        } else if ($data['testtype'] == 'topic') {
            if (!empty($request->input('strand'))) {
                $data['strand'] = $request->input('strand');
            }
            if (!empty($request->input('substrand'))) {
                $data['substrand'] = $request->input('substrand');
            }
            $dashboarddetail = $this->reportRepo->classTopicReport($data);
            $data['dashboarddetailTopic'] = $dashboarddetail;
            return view('admin.adminreport.classoverview_topic', $data);
        }
        $data['trait'] = array('trait_1' => trans('admin/schoolclass.template_heading'), 'trait_1_link' => route('manageclass.index'), 'trait_2' => $schoolClass['class_name'].' : '.trans('School Overview Report'));
        return view('admin.adminreport.classoverview', $data);
    }
    function to_excel($array, $filename) {
        header('Content-type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename='.$filename.'.xls');

        // Filter all keys, they'll be table headers
        $h = array();
        foreach($array as $row){
            foreach($row as $key=>$val){
                if(!in_array($key, $h)){
                 $h[] = $key;   
                }
            }
        }
                //echo the entire table headers
        echo '<table><tr>';
        foreach($h as $key) {
            $key = ucwords($key);
            echo '<th>'.$key.'</th>';
        }
        echo '</tr>';
        
        foreach($array as $row){
             echo '<tr>';
            foreach($row as $val)
                 $this->writeRow($val);   
        }
        echo '</tr>';
        echo '</table>';
                
            
    }
    function writeRow($val) {
        echo '<td>'.utf8_decode($val).'</td>';              
    }
    public function adminreportStudentgraph(Request $request){
        $postDetail=$request->all();
        $report = array();
        if(!empty($postDetail['graphdata'])){
            $dataArray  = json_decode($postDetail['graphdata'],true);
            $arrayone = array();
            $arraytwo = array();
            foreach($dataArray as $key=>$val){
                if($key ==0){
                    $arrayone['sub/test'] = $val[1];
                    $arraytwo['sub/test'] = $val[2];
                }else{
                    $arrayone['test'.$key] = number_format($val[1],2,'.',"");
                    $arraytwo['test'.$key] = number_format($val[2],2,'.',"");
                }
            }
            $report[0] = $arrayone;
            $report[1] = $arraytwo;
        }
        
        $this->to_excel($report, 'student_graph_detail');die;
    }
    
    public function adminreportHiggting(Request $request){
        $postDetail=$request->all();
        $calssArray = json_decode($postDetail['class'],true);
        $dataArray = unserialize($postDetail['graphdata']);
        $report = array();
        if(!empty($calssArray)){
            foreach($calssArray as $key=>$val){
                $arrayon = array();
                $arrayon['Class'] =$val;
                $arrayon['Less Than 20 %'] =$dataArray[$key]['red'];
                $arrayon['21-50 % '] =$dataArray[$key]['orange'];
                $arrayon['51-70%'] =$dataArray[$key]['green'];
                $arrayon['Greater than 70%'] =$dataArray[$key]['blue'];
                $report[] = $arrayon;    
            }
        }
        $this->to_excel($report, 'dashboard_hittingtarget');die;
    }
    public function adminreportSchooloverviewExport($grapthType,Request $request){
        $data = array();
        $schoolId = $request->input('id');
        $data['schoolId'] = $schoolId;
        $data['testtype'] = $request->input('report');
        $data['tab'] = $request->input('tab');
        $data['subject'] = ($request->input('tab') == 'eng') ? "English" : "Math";
        $data['report'] = ($request->input('report') == 'test') ? "Test" : "Revision";
        $dashboarddetail = $this->reportRepo->schoolReort($data);
        $report = array();
        if($grapthType == 'classtestgraph'){
            $file_name = 'class test';
            $allClassTestAvg = $dashboarddetail['allClassTestAvg'];
            foreach($allClassTestAvg as $key => $row){
                $report[] = array_merge(array('Test/class' => 'Test'.($key+1)),$row);
            }
        }
        if($grapthType == 'classprogressgraph'){
            $file_name = 'class progress';
            $allClassStudentAvg = $dashboarddetail['allClassStudentAvg'];
            $report = $allClassStudentAvg;
        }
        $this->to_excel($report, $file_name);die;
    }
    public function adminreportClassoverviewExport($grapthType,Request $request){
        $data = array();
        $schoolId = $request->input('schoolId');
        $classId = $request->input('classId');
        $data['schoolId'] = $schoolId;
        $data['classId'] = $classId;
        $data['testtype'] = $request->input('report');
        $data['tab'] = $request->input('tab');
        $data['subject'] = ($request->input('tab') == 'eng') ? "English" : "Math";
        $data['report'] = ($request->input('report') == 'test') ? "Test" : "Revision";
        $dashboarddetail = $this->reportRepo->classReport($data);
        $report = array();
        if($grapthType == 'classtestgraph'){
            $file_name = 'class test';
            $allClassTestAvg = $dashboarddetail['allClassTestAvg'];
            foreach($allClassTestAvg as $key => $row){
                $report[] = array_merge(array('Test/class' => 'Test'.($key+1)),$row);
            }
        }
        if($grapthType == 'classprogressgraph'){
            $file_name = 'class progress';
            $allClassStudentAvg = $dashboarddetail['allClassStudentAvg'];
            $report = $allClassStudentAvg;
        }
        $this->to_excel($report, $file_name);die;
    }
    public function adminreportClstest(Request $request){
        $postDetail=$request->all();
        asd($postDetail);
        $calssArray = json_decode($postDetail['testclass'],true);
        $graphdata = unserialize($postDetail['testgraphdata']);
        $mainArray = array();
        for ($k = 0; $k < 20; $k++) {
            $dataArray = array();
            // $j = $k;
            // $dataArray[] = $j + 1;
            foreach ($calssArray as $key => $val) {
                $testMarks = 0;
                if (!empty($graphdata[$key][$k])) {
                    $dataArray[] = round(number_format($graphdata[$key][$k],2,'.',''));
                } else {
                    $dataArray[] = 0; //rand(25,60);
                }
            }
            $mainArray[] = $dataArray;
        }
        $report = array();
        if(!empty($calssArray)){
            $classlenghtArray =array();
            foreach($calssArray as $c=>$l){
                $classlenghtArray[] = array('class/test'=>$l);
            
            }
            // asd($classlenghtArray);
            // $array1 = array('class/test'=>"school1");
            // $array2 = array('class/test'=>"school2");
            // $array3 = array('class/test'=>"school3");
            // $array4 = array('class/test'=>"school4");
            // $array5 = array('class/test'=>"school4");
            // $array6 = array('class/test'=>"school6");
            foreach($mainArray as $key=>$val){
                $index = $key+1;
                foreach($classlenghtArray as $a=>$b){
                    $classlenghtArray[$a]['test'.$index] =$val[$a];
                }
                // $array1['test'.$index] = $val[0];
                // $array2['test'.$index] = $val[1];
                // $array3['test'.$index] = $val[2];
                // $array4['test'.$index] = $val[3];
                // $array5['test'.$index] = $val[4];
                // $array6['test'.$index] = $val[5];
            }
            
            foreach($classlenghtArray as $e=>$r){
                $report[] =$r;
            
            }
            // $report[0] = $array1;
            // $report[1] = $array2;
            // $report[2] = $array3;
            // $report[3] = $array4;
            // $report[4] = $array5;
            // $report[5] = $array6;
        }
        $this->to_excel($report, 'dashboard_hittingtarget');die;
    }
    public function adminreportCreatefile(Request $request){
        
        $postDetail=$request->all();
        $fileName = time()."_".$postDetail['name'];
        $filePath = $_SERVER['DOCUMENT_ROOT'].'/excel/'.$fileName;
        $dataArray = explode(",",$postDetail['data']);
        $content =  base64_decode($dataArray[1],true);
        $fp = fopen($filePath,"wb");
        fwrite($fp,$content);
        fclose($fp);
        $responseArray = array("status"=>"suceess","url"=>"/excel/".$fileName);
        echo json_encode($responseArray);exit;
    }
}
