<?php

/**
 * This controller is used for Report.
 * @package    Report
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Repositories\EmailRepository;
use App\Http\Requests\School\SchoolCreateRequest;
use App\Http\Requests\School\SchoolUpdateRequest;
use App\Http\Requests\School\SchoolUpdateProfileRequest;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\User;
use App\Models\Howfind;
use App\Models\Schooltype;
use App\Models\Whoyou;
use App\Models\County;
use Datatables;
use DB;

/**
 * This controller is used for Report.
 * @author     Icreon Tech - dev5.
 */
class ReportController extends Controller {

    /**
     * The UserRepository instance.
     *
     * @var App\Repositories\UserRepository
     */
    protected $reportRepo;

    /**
     * Create a new ReportController instance.
     *
     * @param  App\Repositories\UserRepository reportRepo
     * @return void
     */
    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
        $this->middleware('ajax', ['only' => ['delete', 'listRecord']]);
    }

    /**
     * 
     * @param type $id
     * @return Response
     */
    public function index() {
        
    }

    public function schoolReport() {
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        $params = array();
        $userStats = $this->userRepo->showUserTypeTotal($params);
        $userStatsArray = array();
        foreach ($userStats as $key => $graph_val) {
            $userStatsArray[$graph_val['user_type']] = $graph_val['total_count'];
        }
        $reportParam['type'] = SCHOOL;
        $last_month_registrations = $this->userRepo->showLastRegistration($reportParam);
        $data['last_month_registrations'] = $last_month_registrations[0]->userCnt;
        $data['userStats'] = $userStatsArray;
        //asd($data['userStats']);
    //    asd($data);
        return view('admin.report.schoollist', $data);
    }

    public function listSchool(Request $request) {
        $param['user_type'] = SCHOOL;
        if ($request->has('isLimited')) {
            $param['limit'] = DASHBOARD_RECORD_LIMIT;
        }
        $users = $this->userRepo->getUserList($param);
        return Datatables::of($users)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('school_name')) {
                                $query->where('school_name', 'like', "%{$request->get('school_name')}%");
                            }
                            if ($request->has('email')) {
                                $query->where('email', 'like', "%{$request->get('email')}%");
                            }
                            if ($request->has('status')) {
                                $query->where('users.status', '=', "{$request->get('status')}");
                            }
                        })
                        ->editColumn('school_type', function ($user) {
                            $school_type = ($user->school_type);
                            $userType = array();
                            $userTypeArray = Schooltype::getSchoolType() + [OTHER_VALUE => trans('admin/admin.other')];
                            foreach ($userTypeArray as $key => $graph_val) {
                                switch ($school_type) {
                                    case $key:
                                        $userType[] = $userTypeArray[$key];
                                        break;
                                }
                            }
                            return ($userType);
                        })
                        ->editColumn('county', function ($user) {
                            $county = ($user->county);
                            $userType = array();
                            $userTypeArray = County::getCounty();
                            foreach ($userTypeArray as $key => $graph_val) {
                                switch ($county) {
                                    case $key:
                                        $userType[] = $userTypeArray[$key];
                                        break;
                                }
                            }
                            return($userType);
                        })
                        ->editColumn('howfinds_id', function ($user) {
                            $howfinds_id = ($user->howfinds_id);
                            $howfinds_other = ($user->howfinds_other);
                            $userType = array();
                            $userTypeArray = Howfind::getHowFind();

                            if ($howfinds_id == '-1') {
                                $userType[] = $howfinds_other;
                            } else {
                                foreach ($userTypeArray as $key => $graph_val) {
                                    switch ($howfinds_id) {
                                        case $key:
                                            $userType[] = $userTypeArray[$key];
                                            break;
                                    }
                                }
                            }
                            return ($userType);
                        })
                        ->editColumn('no_of_students', function($user) {
                            if (!empty($user->no_of_students))
                                $no_of_students = $user->no_of_students;
                            else
                                $no_of_students = '0';
                            return
                                    '<a href="' . route('student.index', ['id' => encryptParam($user->id)]) . '" class="">' . $no_of_students . '</a>';
                        })
                        ->editColumn('no_of_teachers', function ($user) {
                            if (!empty($user->no_of_teachers))
                                $no_of_teachers = $user->no_of_teachers;
                            else
                                $no_of_teachers = '0';
                            return
                                    '<a href="' . route('teacher.index', ['id' => encryptParam($user->id)]) . '" class="">' . $no_of_teachers . '</a>';
                        })
                        ->editColumn('renew_date', function ($user) {
                            if (($user->subscription_expiry_date) != '0000-00-00 00:00:00') {
                                $date = date_create($user->subscription_expiry_date);
                                date_add($date, date_interval_create_from_date_string("1 days"));
                                return $due_date = date_format($date, "d/m/Y");
                            } else {
                                return'';
                            }
                        })
                        ->editColumn('created_at', function ($user) {
                            return $user->created_at ? outputDateFormat($user->created_at) : '';
                        })
                        ->editColumn('subscription_expiry_date', function ($user) {
                            return $user->subscription_expiry_date ? outputDateFormat($user->subscription_expiry_date) : '';
                        })
                        ->editColumn('updated_at', function ($user) {
                            return $user->updated_at ? outputDateFormat($user->updated_at) : '';
                        })
                        ->make(true);
    }

    public function parentReport() {
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        $params = array();
        $userStats = $this->userRepo->showUserTypeTotal($params);
        $userStatsArray = array();
        foreach ($userStats as $key => $graph_val) {
            $userStatsArray[$graph_val['user_type']] = $graph_val['total_count'];
        }
        $reportParam['type'] = TUTOR;
        $last_month_registrations = $this->userRepo->showLastRegistration($reportParam);
        //asd($last_month_registrations[0]->userCnt);
        $data['last_month_registrations'] = $last_month_registrations[0]->userCnt;
        $data['userStats'] = $userStatsArray;
        return view('admin.report.tutorlist', $data);
    }

    public function listParent(Request $request) {
        $param['user_type'] = TUTOR;
        if ($request->has('isLimited'))
            $param['limit'] = DASHBOARD_RECORD_LIMIT;
        $users = $this->userRepo->getPaymentUserList($param);
        return Datatables::of($users)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('name')) {
                                $query->where(function ($query) use ($request) {
                                    $query->where('first_name', 'like', "%{$request->get('name')}%")
                                    ->orwhere('last_name', 'like', "%{$request->get('name')}%")
                                    ->orwhere(DB::raw('CONCAT(first_name, " ", last_name)'), 'like', "%{$request->get('name')}%");
                                });
                            }
                            if ($request->has('username')) {
                                $query->where('username', 'like', "%{$request->get('username')}%");
                            }
                            if ($request->has('email')) {
                                $query->where('email', 'like', "%{$request->get('email')}%");
                            }
                            if ($request->has('status')) {
                                $query->where('users.status', '=', "{$request->get('status')}");
                            }
                        })
                        ->editColumn('no_of_students', function ($user) {
                            return '<a href="' . route('student.index', ['id' => encryptParam($user->id)]) . '" class="">' . $user->no_of_students . '</a>';
                        })
                        ->editColumn('county', function ($user) {
                            $county = ($user->county);
                            $userType = array();
                            $userTypeArray = County::getCounty();
                            foreach ($userTypeArray as $key => $graph_val) {
                                switch ($county) {
                                    case $key:
                                        $userType[] = $userTypeArray[$key];
                                        break;
                                }
                            }
                            return($userType);
                        })
                        ->editColumn('additional_amount', function ($user) {
                            if (empty($user->additional_amount) || $user->additional_amount=='0.00') {
                                return ( '0.00');
                            } else {
                                return (($user->additional_amount));
                            }
                        })
                        ->editColumn('howfinds_id', function ($user) {
                            $howfinds_id = ($user->howfinds_id);
                            $howfinds_other = ($user->howfinds_other);
                            $userType = array();
                            $userTypeArray = Howfind::getHowFind();

                            if ($howfinds_id == '-1') {
                                $userType[] = $howfinds_other;
                            } else {
                                foreach ($userTypeArray as $key => $graph_val) {
                                    switch ($howfinds_id) {
                                        case $key:
                                            $userType[] = $userTypeArray[$key];
                                            break;
                                    }
                                }
                            }
                            return ($userType);
                        })
                        ->editColumn('created_at', function ($user) {
                            return $user->created_at ? outputDateFormat($user->created_at) : '';
                        })
                        ->editColumn('subscription_expiry_date', function ($user) {
                            if (($user->subscription_expiry_date) != '0000-00-00 00:00:00') {
                                $date = date_create($user->subscription_expiry_date);
                                date_add($date, date_interval_create_from_date_string("1 days"));
                                return $abc = date_format($date, "d/m/Y");
                            } else {
                                return'';
                            }
                        })
                        ->editColumn('name', function ($user) {
                            return $user->first_name . ' ' . $user->last_name;
                        })
                        ->make(true);
    }

    public function revenueReport() {
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['status'] = $status;
        $params = array();
        $userStats = $this->userRepo->showUserTypeTotal($params);
        $userStatsArray = array();
        foreach ($userStats as $key => $graph_val) {
            $userStatsArray[$graph_val['user_type']] = $graph_val['total_count'];
        }
        //$last_month_registrations = $this->userRepo->showLastRegistration();
        //asd($last_month_registrations[0]->userCnt);
       // $data['last_month_registrations'] = $last_month_registrations[0]->userCnt;
        $data['userStats'] = $userStatsArray;
        $param['tutor'] = TUTOR;
        $param['school'] = SCHOOL;
        $param['total'] = '';
        $amount = $this->userRepo->showRevenueTotal($param['total']);
        $amount_school = $this->userRepo->showRevenueTotal($param['school']);
        $amount_tutor = $this->userRepo->showRevenueTotal($param['tutor']);
        $data['total_revenue_till_date'] = $amount[0]['total'];
        $data['school_revenue'] = $amount_school[0]['total'];
        $data['tutor_revenue'] = $amount_tutor[0]['total'];

        $param['userType'] = SCHOOL;
        $graph_data['school'] = $this->userRepo->getGraphData($param);
        $param['userType'] = TUTOR;
        $graph_data['tutor'] = $this->userRepo->getGraphData($param);
        // asd($graph_data);

        $recordRegistredArray = array();
        foreach ($graph_data as $key => $graph_val) {
            foreach ($graph_val as $record => $graph_value) {
                if ($key == 'school')
                    $recordRegistredArray[$graph_value->month]['school_total'] = $graph_value->total;
                if ($key == 'tutor')
                    $recordRegistredArray[$graph_value->month]['tutor_total'] = $graph_value->total;
            }
        }
        $str = "['','School','Parent/Tutor'],";

        foreach ($recordRegistredArray as $key => $graph_val) {
            $school_total = isset($graph_val['school_total']) ? $graph_val['school_total'] : 0;
            $tutor_total = isset($graph_val['tutor_total']) ? $graph_val['tutor_total'] : 0;
            $str.= "['" . $key . "'," . $school_total . "," . $tutor_total . "],";
        }
        $data['recordRegistredArray'] = trim($str, ",");




        $param['userType'] = SCHOOL;
        $graph_data2['school'] = $this->userRepo->getGraphDataYearly($param);
        $param['userType'] = TUTOR;
        $graph_data2['tutor'] = $this->userRepo->getGraphDataYearly($param);
        $recordRegistredArray = array();
        foreach ($graph_data2 as $graph_key => $graph_val2) {
            foreach ($graph_val2 as $record => $graph_value2) {
                if ($graph_key == 'school')
                    $recordRegistredArray[$graph_value2->YEAR]['school_total'] = $graph_value2->total;
                if ($graph_key == 'tutor')
                    $recordRegistredArray[$graph_value2->YEAR]['tutor_total'] = $graph_value2->total;
            }
        }
        $str_year = "['','School','Tutor'],";
        foreach ($recordRegistredArray as $graph_key => $graph_val2) {
            $school_total = isset($graph_val2['school_total']) ? $graph_val2['school_total'] : '0';
            $tutor_total = isset($graph_val2['tutor_total']) ? $graph_val2['tutor_total'] : '0';
            $str_year.= "['" . $graph_key . "'," . $school_total . "," . $tutor_total . "],";
        }
        $data['recordRegistredArrayYearly'] = trim($str_year, ",");
        return view('admin.report.revenuelist', $data);
    }

    public function listRevenue(Request $request) {
        $param['user_type'] = $request->get('user_type');
//        $users = $this->userRepo->getListUser($param)->get()->toArray();
//        asd($users);
        $users = $this->userRepo->getListUser($param);
        return Datatables::of($users)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('user_type')) {
                                $query->where('user_type', '=', "{$request->get('user_type')}");
                            }
                            if ($request->has('username')) {
                                $query->where('username', 'like', "%{$request->get('username')}%");
                            }
                            if ($request->has('amount')) {
                                $query->where('amount', '=', "{$request->get('amount')}");
                            }
                        })
                        ->addColumn('action', function ($user) {
                            return '<div class="btn-group">
                                    <a href="javascript:void(0);" data-remote="' . route('report.show_revenue', encryptParam($user->payments_id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                    </div>';
                        })
                        ->editColumn('payment_created_at', function ($user) {
                            return $user->payment_created_at ? outputDateFormat($user->payment_created_at) : '';
                        })
                        ->editColumn('username', function ($user) {
                            if($user->user_type == TUTOR)
                                $name = $user->first_name .' '. $user->last_name;
                            else 
                                $name = $user->school_name;
                            return $name ;
                        })
                        ->editColumn('voucher_code', function ($user) {
                            if ($user->voucher_code == '') {
                                return "N/A";
                            } else {
                                return $user->voucher_code;
                            }
                        })
                        ->editColumn('upgrade_type', function ($users) {
                            if ($users->upgrade_type == '1') {
                                $no_of_std = ($users->no_of_students);
                                return '+ ' . $no_of_std . ' Student(s)';
                            } else if ($users->upgrade_type == '2') {
                                return YEARLY_SUBS;
                            } else if ($users->upgrade_type == '3') {
                                return RENEW_YEARLY_SUBS;
                            }
                        })
                        ->make(true);
    }

    public function adminReport(Request $request) {
        $data = array();
        $param['user_type'] = SCHOOL;
        if (session()->get('user')['user_type'] == SCHOOL) {
            $param['id'] = session()->get('user')['id'];
        }
        if (session()->get('user')['user_type'] == TEACHER) {

            $param['id'] = session()->get('user')['school_id'];
        }
        $schoolList = $this->userRepo->getUser($param)->get()->toArray();
        $data['schoolList'] = $schoolList;
        return view('admin.report.admin', $data);
    }

    public function classandstudentReport(Request $request) {
        $postDetail = $request->all();
        if ($postDetail['report'] == 2) {
            $classList = $this->userRepo->getClassList($postDetail ['school']);
            $responseArray = array(
                'status' => 'success',
                'postDetail' => $postDetail,
                'dataArray' => $classList,
            );
            echo json_encode($responseArray);
            die;
        } else {
            if (session()->get('user')['user_type'] == TEACHER) {
                $param = array('school_id' => $postDetail ['school'], 'user_type' => STUDENT);
                $param['teacher_id'] = session()->get('user')['id'];
                $users = $this->userRepo->getUserList($param)->get()->toArray();
            } elseif (session()->get('user')['user_type'] == TUTOR) {
                $param = array('user_type' => STUDENT);
                $param['tutor_id'] = session()->get('user')['id'];
                $users = $this->userRepo->getUserList($param)->get()->toArray();
            } else {
                $param = array('school_id' => $postDetail['school'], 'user_type' => STUDENT);
                $users = $this->userRepo->getUser($param)->get()->toArray();
            }
            $responseArray = array(
                'status' => 'success',
                'postDetail' => $postDetail,
                'dataArray' => $users,
            );
            echo json_encode($responseArray);
            die;
        }
    }

    public function showRevenue($id) {
        $params['id'] = decryptParam($id);
        $revenue_detail = $this->userRepo->getListUser($params)->get()->toArray();
        $revenue_details = $revenue_detail[0];
        return view('admin.report.show_revenue', compact('revenue_details'));
    }

    public function reportDashboard() {
        $params = array();
        $params['user_type'] = SCHOOL;
        $typeOfSchoolArray = $this->userRepo->getTypeofSchoolCounts($params);
        $typeOfSchoolArray[] = $this->userRepo->getOtherTypeofSchoolCounts($params);
        $data['typeOfSchoolStats'] = $typeOfSchoolArray;
        $params = array();
        $params['user_type'] = SCHOOL;
        $countiesSchoolUsersArray = $this->userRepo->getCountiesUserCounts($params);
        $data['countiesSchoolUsersArray'] = $countiesSchoolUsersArray;
        $params = array();
        $params['user_type'] = TUTOR;
        $countiesParentUsersArray = $this->userRepo->getCountiesUserCounts($params);
        $data['countiesParentUsersStats'] = $countiesParentUsersArray;
        $params = array();
        $params['user_type'] = TUTOR;
        $howHearParantArray = $this->userRepo->getHowHearCounts($params);
        $howHearParantArray[] = $this->userRepo->getOtherHowHearCounts($params);
        $data['howHearParantArray'] = $howHearParantArray;
        $params = array();
        $params['user_type'] = SCHOOL;
        $howHearSchoolArray = $this->userRepo->getHowHearCounts($params);
        $howHearSchoolArray[] = $this->userRepo->getOtherHowHearCounts($params);
        $data['howHearSchoolArray'] = $howHearSchoolArray;
        $params = array();
        $userStats = $this->userRepo->showUserTypeTotal($params);
        $params['active_tutors'] = TUTOR;
        $active_tutors = $this->userRepo->showUserTypeTotal($params);
        $params = array();
        $params['tutor'] = TUTOR;
        $activeTutors = $this->userRepo->showMemberCount($params);
        //asd($activeTutors);
        $params = array();
        $params['school'] = SCHOOL;
        $activeSchools = $this->userRepo->showMemberCount($params);
        //asd($activeSchools);
        $params = array();
        $params['school_inactive'] = SCHOOL;
        $inactiveSchools = $this->userRepo->showMemberCount($params);
        //asd($inactiveSchools);
        $params = array();
        $params['tutor_inactive'] = TUTOR;
        $inactiveTutors = $this->userRepo->showMemberCount($params);
        //asd($inactiveTutors);
        $params = array();
        $params['tutor'] = TUTOR;
        $lastMonthActiveTutors = $this->userRepo->showMonthlyRegistration($params);
        //asd($lastMonthTutors);
        $params = array();
        $params['school'] = SCHOOL;
        $lastMonthActiveSchools = $this->userRepo->showMonthlyRegistration($params);
        //asd($lastMonthActiveSchools);
        $params = array();
        $params['tutor_inactive'] = TUTOR;
        $lastMonthInactiveTutors = $this->userRepo->showMonthlyRegistration($params);
        //asd($lastMonthInactiveTutors);
        $params = array();
        $params['school_inactive'] = SCHOOL;
        $lastMonthInactiveSchools = $this->userRepo->showMonthlyRegistration($params);
        //asd($lastMonthInactiveSchools);
        $userStatsArray = array();
        foreach ($userStats as $key => $val) {
            $userStatsArray[$val['user_type']] = $val['total_count'];
        }
        $data['userStats'] = $userStatsArray;
        $params = array();
        $param['userType'] = SCHOOL;
        $graph_data['school'] = $this->userRepo->getGraphDataDashboard($param);
        $param['userType'] = TUTOR;
        $graph_data['tutor'] = $this->userRepo->getGraphDataDashboard($param);
        $recordRegistredArray = array();
        foreach ($graph_data as $key => $graph_val) {
            foreach ($graph_val as $record => $graph_value) {
                if ($key == 'school')
                    $recordRegistredArray[$graph_value->month]['school_total'] = $graph_value->total;
                if ($key == 'tutor')
                    $recordRegistredArray[$graph_value->month]['tutor_total'] = $graph_value->total;
            }
        }
        $str = "['','School','Parent/Tutor'],";
        foreach ($recordRegistredArray as $key => $graph_val2) {
            $school_total = isset($graph_val2['school_total']) ? $graph_val2['school_total'] : '0';
            $tutor_total = isset($graph_val2['tutor_total']) ? $graph_val2['tutor_total'] : '0';
            $str.= "['" . $key . "'," . $school_total . "," . $tutor_total . "],";
        }

        $data['recordRegistredArray'] = trim($str, ",");
        $data['activeTutors'] = $activeTutors;
        $data['activeSchools'] = $activeSchools;
        $data['inactiveTutors'] = $inactiveTutors;
        $data['inactiveSchools'] = $inactiveSchools;
        $data['lastMonthActiveTutors'] = $lastMonthActiveTutors;
        $data['lastMonthActiveSchools'] = $lastMonthActiveSchools;
        $data['lastMonthInactiveTutors'] = $lastMonthInactiveTutors;
        $data['lastMonthInactiveSchools'] = $lastMonthInactiveSchools;
        //asd($data);

        return view('admin.report.report_dashboard', $data);
    }

}
