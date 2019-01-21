<?php

/**
 * This controller is used for Reward.
 * @package    reward
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\RewardRepository;
use App\Repositories\StrandRepository;
use App\Repositories\GroupClassRepository;
use App\Repositories\UserRepository;
use App\Http\Requests\RewardsRequest;
use App\Models\Questionset;
use App\Models\Studentaward;
use Illuminate\Http\Request;
use Datatables;
use DB;
use Illuminate\Support\Collection;

/**
 * This controller is used for reward centre.
 * @author     Icreon Tech - dev1.
 */
class RewardController extends Controller {

    /**
     * The RewardRepository instance.
     * @var App\Repositories\RewardRepository
     */
    protected $rewardRepo;

    /**
     * The StrandRepository instance.
     *
     * @var App\Repositories\StrandRepository
     */
    protected $strandRepo;
    protected $groupClassRepo;
    protected $userRepo;

    /**
     * Create a new RewardController instance.
     * @param  App\Repositories\RewardRepository $rewardRepo
     * @param  App\Repositories\StrandRepository $strandRepo
     * @return void
     */
    public function __construct(RewardRepository $rewardRepo, StrandRepository $strandRepo, GroupClassRepository $groupClassRepo, UserRepository $userRepo) {
        $this->rewardRepo = $rewardRepo;
        $this->strandRepo = $strandRepo;
        $this->groupClassRepo = $groupClassRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Display test listing page.
     * @author     Icreon Tech - dev2.
     * @return Response
     */
    public function index($task_type) {
        $task_type = ucfirst($task_type);
        $data['subject'] = ['' => trans('admin/admin.select_option')] + questionSetSubjects();
        $arrStrands = $this->strandRepo->getstrandTree(FALSE);
        $data['strands'] = json_encode($arrStrands['strands']);
        $studentawards = Studentaward::getActiveStudentAwards();
        $title = array();
        if (!empty($studentawards)) {
            foreach ($studentawards as $key => $val) {
                $title[$val['title']] = $val['title'];
            }
        }
        $data['title'] = ['' => trans('admin/admin.select_option')] + $title;
        //asd( $data['title']);
        $data['substrands'] = json_encode($arrStrands['substrands']);
        $questionSets = Questionset::getQuestionsetList();
        foreach ($questionSets as $key => $value) {
            $sets[$value['set_name']] = $value['set_name'];
        }
        $data['questionsets'] = ['' => trans('admin/admin.select_option')] + $sets;
        $data['status'] = array('' => trans('admin/admin.select_option')) + statusArray();
        if ($task_type == TEST) {
            $questionSets = Questionset::getQuestionsetList();
            $data['questionSets'] = json_encode($questionSets);
            return view('admin.rewards.rewards-test', $data);
        } elseif ($task_type == REVISION) {
            return view('admin.rewards.rewards-revision', $data);
        }
    }

    public function listRecord(Request $request, $task_type) {
        if (ucfirst($task_type) == TEST) {
            $params = array('task_type' => TEST, 'created_by' => session()->get('user')['id']);
            $rewardsTest = $this->rewardRepo->getRewardsTestList($params);
            //asd($rewardsTest);
            return Datatables::of($rewardsTest)
                            ->filter(function ($query) use ($request) {
                                if ($request->has('percentage')) {
                                    $query->where('rewards.percent_min', '<=', "{$request->get('percentage')}");
                                    $query->where('rewards.percent_max', '>=', "{$request->get('percentage')}");
                                }
                                if ($request->has('subject')) {
                                    $query->where('rewards.subject', '=', "{$request->get('subject')}");
                                }
                                if ($request->has('questionset')) {
                                    $query->where('questionsets.set_name', '=', "{$request->get('questionset')}");
                                }
                                if ($request->has('title')) {
                                    $query->where('studentawards.title', '=', "{$request->get('title')}");
                                }
                            })
                            ->editColumn('percentage', function($studentRewards) {
                                return $studentRewards->percent_min . ' - ' . $studentRewards->percent_max;
                            })
                            ->addColumn('action', function ($studentRewards) {
                                return '
                                    <a href="' . route('rewards.edit', ['test', encryptParam($studentRewards->id)]) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="javascript:void(0);" data-id="' . encryptParam($studentRewards->id) . '" class="delete_record"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                            })
                            ->editColumn('created_at', function ($questionset) {
                                return $questionset->created_at ? outputDateFormat($questionset->created_at) : '';
                            })
                            ->editColumn('question_set', function ($test) {
                                return $test->set_name;
                            })
                            ->make(true);
        } else {
            $params = array('task_type' => REVISION, 'created_by' => session()->get('user')['id']);
            $revision = $this->rewardRepo->getRewardsRevisionList($params);
            return Datatables::of($revision)
                            ->filter(function ($query) use ($request) {
                                if ($request->has('percentage')) {
                                    $query->where('rewards.percent_min', '<=', "{$request->get('percentage')}");
                                    $query->where('rewards.percent_max', '>=', "{$request->get('percentage')}");
                                }
                                if ($request->has('subject')) {
                                    $query->where('rewards.subject', '=', "{$request->get('subject')}");
                                }
                                if ($request->has('strand')) {
                                    $query->where('rewards.strand', '=', "{$request->get('strand')}");
                                }
                                if ($request->has('substrand')) {
                                    $query->where('rewards.substrand', '=', "{$request->get('substrand')}");
                                }
                                if ($request->has('status')) {
                                    $query->where('rewards.status', '=', "{$request->get('status')}");
                                }
                            })
                            ->editColumn('percentage', function($studentRewards) {
                                return $studentRewards->percent_min . ' - ' . $studentRewards->percent_max;
                            })
                            ->addColumn('action', function ($studentRewards) {
                                return '
                                    <a href="' . route('rewards.edit', ['revision', encryptParam($studentRewards->id)]) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="javascript:void(0);" data-id="' . encryptParam($studentRewards->id) . '" class="delete_record"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                            })
                            ->editColumn('created_at', function ($questionset) {
                                return $questionset->created_at ? outputDateFormat($questionset->created_at) : '';
                            })
                            ->make(true);
        }
    }

    public function create($task_type) {
        $taskType = ucfirst($task_type);
        $authUser = session()->get('user');
        $data['user_type'] = $authUser['user_type'];
        $data['task_type'] = $taskType;
        $data['JsValidator'] = 'App\Http\Requests\RewardsRequest';
        $data['subject'] = ['' => trans('admin/admin.select_option')] + questionSetSubjects();
        $data['studentawards'] = Studentaward::getActiveStudentAwards();
        /* if ($authUser['user_type'] == SCHOOL || $authUser['user_type'] == TEACHER) {
          $data['selected_students_Class'] = array();
          $data['selected_students_Group'] = array();
          $data['selected_students_School'] = array();
          $data['classes_array'] = $this->groupClassRepo->teacherClassStudent(array(
                'teacher_id' => $authUser['id']
            ));
          $data['group_array'] = $this->groupClassRepo->teacherGroupStudent(array(
                'teacher_id' => $authUser['id']
            ));
          if ($authUser['user_type'] == SCHOOL) {
          $data['school_array']['All'] = $this->userRepo->schoolStudentsList(array(
          'school_id' => session()->get('user')['school_id']
          ));
          }
          } */

        if ($authUser['user_type'] == SCHOOL) {
            $data['selected_students_Class'] = array();
            $data['selected_students_Group'] = array();
            $data['selected_students_School'] = array();
            $data['classes_array'] = $this->groupClassRepo->schoolClassStudent($authUser['id']);
            $data['group_array'] = $this->groupClassRepo->schoolGroupStudent($authUser['id']);
            if ($authUser['user_type'] == SCHOOL) {
                $data['school_array']['All'] = $this->userRepo->schoolStudentsList(array(
                    'school_id' => session()->get('user')['school_id']
                ));
            }
        }
        if ($authUser['user_type'] == TEACHER) {
            $data['selected_students_Class'] = array();
            $data['selected_students_Group'] = array();
            $data['selected_students_School'] = array();
            $data['classes_array'] = $this->groupClassRepo->teacherClassStudent(array(
                'teacher_id' => $authUser['id']
            ));
            $data['group_array'] = $this->groupClassRepo->teacherGroupStudent(array(
                'teacher_id' => $authUser['id']
            ));
            if ($authUser['user_type'] == SCHOOL) {
                $data['school_array']['All'] = $this->userRepo->schoolStudentsList(array(
                    'school_id' => session()->get('user')['school_id']
                ));
            }
        }

        if ($taskType == TEST) {
            $data['page_title'] = trans('admin/rewards.add_test_rewards');
            $data['page_heading'] = trans('admin/rewards.manage_test_rewards');
            $data['trait'] = array('trait_1' => trans('admin/rewards.test_rewards'), 'trait_1_link' => route('rewards.index', $task_type), 'trait_2' => trans('admin/rewards.add_test_rewards'));
            $questionSets = Questionset::getQuestionsetList();
            $data['questionSets'] = json_encode($questionSets);
        } elseif ($taskType == REVISION) {
            $data['page_title'] = trans('admin/rewards.add_revision_rewards');
            $data['page_heading'] = trans('admin/rewards.manage_revision_rewards');
            $data['trait'] = array('trait_1' => trans('admin/rewards.revision_rewards'), 'trait_1_link' => route('rewards.index', $task_type), 'trait_2' => trans('admin/rewards.add_revision_rewards'));
            $arrStrands = $this->strandRepo->getstrandTree(FALSE);
            $data['strands'] = json_encode($arrStrands['strands']);
            $data['substrands'] = json_encode($arrStrands['substrands']);
        }
        return view('admin.rewards.create', $data);
    }

    /**
     * Insert a new Test
     * @author     Icreon Tech - dev2.
     * @param App\Http\Requests\Reward\RewardtestRequest $request
     * @return Response
     */
    public function store(RewardsRequest $request, $task_type) {
        $inputs = $request->all();
        $inputs['created_by'] = session()->get('user')['id'];
        $inputs['task_type'] = ucfirst($task_type);
        $inputs['user_type'] = session()->get('user')['user_type'];
        $this->rewardRepo->store($inputs);
        return redirect(route('rewards.index', $task_type))->with('ok', trans('admin/rewards.created'));
    }

    public function edit($task_type, $id) {
        $taskType = ucfirst($task_type);
        $authUser = session()->get('user');
        $id = decryptParam($id);
        $data['user_type'] = $authUser['user_type'];
        $data['task_type'] = $taskType;
        $data['JsValidator'] = 'App\Http\Requests\RewardsRequest';
        $data['subject'] = ['' => trans('admin/admin.select_option')] + questionSetSubjects();
        $data['status'] = statusArray();
        $data['studentawards'] = Studentaward::getActiveStudentAwards();
        $rewardsData = $this->rewardRepo->getRewardsData($id);
        $data['rewardsData'] = $rewardsData;
        if ($authUser['user_type'] == SCHOOL || $authUser['user_type'] == TEACHER) {
            $data['selected_students_Class'] = array();
            $data['selected_students_Group'] = array();
            $data['selected_students_School'] = array();
            $rewardsStudents = $this->rewardRepo->getRewardsStudents(array(
                'rewards_id' => $rewardsData['id']
            ));
            if (count($rewardsStudents)) {
                foreach ($rewardsStudents as $key => $value) {
                    $selectedStudent[] = $value['student_id'];
                }
            }
            $data['selected_students_' . $rewardsData['student_source']] = $selectedStudent;
            $data['classes_array'] = $this->groupClassRepo->teacherClassStudent(array(
                'teacher_id' => $authUser['id']
            ));
            $data['group_array'] = $this->groupClassRepo->teacherGroupStudent(array(
                'teacher_id' => $authUser['id']
            ));
            if ($authUser['user_type'] == SCHOOL) {
                $data['school_array']['All'] = $this->userRepo->schoolStudentsList(array(
                    'school_id' => session()->get('user')['school_id']
                ));
            }
        }

        if ($taskType == TEST) {
            $questionSets = Questionset::getQuestionsetList();
            $data['questionSets'] = json_encode($questionSets);
            $data['page_title'] = trans('admin/rewards.edit_test_rewards');
            $data['page_heading'] = trans('admin/rewards.manage_test_rewards');
            $data['trait'] = array('trait_1' => trans('admin/rewards.test_rewards'), 'trait_1_link' => route('rewards.index', $task_type), 'trait_2' => trans('admin/rewards.edit_test_rewards'));
        } elseif ($taskType == REVISION) {
            $arrStrands = $this->strandRepo->getstrandTree(FALSE);
            $data['strands'] = json_encode($arrStrands['strands']);
            $data['substrands'] = json_encode($arrStrands['substrands']);
            $data['page_title'] = trans('admin/rewards.edit_revision_rewards');
            $data['page_heading'] = trans('admin/rewards.manage_revision_rewards');
            $data['trait'] = array('trait_1' => trans('admin/rewards.revision_rewards'), 'trait_1_link' => route('rewards.index', $task_type), 'trait_2' => trans('admin/rewards.edit_revision_rewards'));
        }
        return view('admin.rewards.edit', $data);
    }

    /**
     * Update the test.
     * @author     Icreon Tech - dev2.
     * @param \App\Http\Requests\Reward\RewardtestRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(RewardsRequest $request, $task_type, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();
        $inputs['task_type'] = ucfirst($task_type);
        $inputs['user_type'] = session()->get('user')['user_type'];
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->rewardRepo->update($inputs, $id);
        return redirect(route('rewards.index', $task_type))->with('ok', trans('admin/rewards.save_msg'));
    }

    /**
     * Delete a rewards 
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return type JSON Response
     */
    public function delete(Request $request) {
        $inputs = $request->all();
        $id = decryptParam($inputs['id']);

        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['status'] = DELETED;
        $inputs['id'] = $id;
        $this->rewardRepo->destroyReward($inputs, $id);
        return response()->json();
    }

    public function imagePreview($image) {
        $data['image'] = $image;
        return view('admin.rewards.imagepreview', compact('data'));
    }

}
