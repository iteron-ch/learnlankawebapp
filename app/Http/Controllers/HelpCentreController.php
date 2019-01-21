<?php

/**
 * This controller is used for HelpCentre.
 * @package    HelpCentre
 * @author     Icreon Tech  - dev5.
 */

namespace App\Http\Controllers;

use App\Repositories\HelpCentreRepository;
use App\Repositories\StrandRepository;
use App\Repositories\StudentTaskRepository;
use App\Http\Requests\HelpCentre\HelpCentreCreateRequest;
use App\Http\Requests\HelpCentre\HelpCentreUpdateProfileRequest;
use Illuminate\Http\Request;
use DB;
use Datatables;

/**
 * This controller is used for HelpCentre.
 * @author     Icreon Tech - dev5.
 */
class HelpCentreController extends Controller {

    /**
     * The HelpCentreRepository instance.
     *
     * @var App\Repositories\HelpCentreRepository
     */
    protected $helpcentreRepo;
    public $strandRepo;

    /**
     * The StudentTaskRepository instance.
     *
     * @var App\Repositories\StudentTaskRepository
     */
    protected $studentTaskRepo;

    /**
     * Create a new HelpCentreController instance.
     * @param  App\Repositories\HelpCentreRepository helpcentreRepo
     * @return void
     */
    public function __construct(HelpCentreRepository $helpcentreRepo, StrandRepository $strandRepo, StudentTaskRepository $studentTaskRepo) {
        $this->helpcentreRepo = $helpcentreRepo;
        $this->strandRepo = $strandRepo;
        $this->studentTaskRepo = $studentTaskRepo;
    }

    /**
     * Display strand listing page.
     * @author     Icreon Tech - dev1.
     * @return Response
     */
    function assignChildren($strands) {
        $strandResult = array();
        foreach ($strands as $strand) {
            if (!isset($strand['children'])) {
                // set the children
                $strand['children'] = array();
                foreach ($strands as $substrand) {
                    if ($strand['id'] == $substrand['parent_id']) {
                        $strand['children'][] = $substrand;
                    }
                }
            }
            if ($strand['parent_id'] == 0) {
                $strandResult[$strand['subject']][] = $strand;
            }
        }

        return $strandResult;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $arrStrands = $this->strandRepo->getstrandTree(FALSE);
        $strands = $arrStrands['strands'];
        $substrands = $arrStrands['substrands'];
        foreach ($substrands as $strandId => $substrand) {
            $key = isset($strands[MATH][$strandId]) ? $strands[MATH][$strandId] : $strands[ENGLISH][$strandId];
            $strandsData[$key] = $substrand;
        }
        $data['strands'] = $strands;
        $data['substrands'] = $strandsData;
        $category = helpCenterCategoryGroup();
        $data['category'] = $category;
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $visible_to = ['' => trans('admin/admin.select_option')] + groupOwnerType();
        $subject = ['' => trans('admin/admin.select_option')] + array(MATH => 'Math', ENGLISH => 'English');
        $data['status'] = $status;
        $data['visible_to'] = $visible_to;
        $data['subject'] = $subject;
        $user_type = session()->get('user')['user_type'];
        $data['user_type'] = $user_type;
        if ($user_type == TEACHER || $user_type == SCHOOL || $user_type == TUTOR) {
            $param['user_type'] = ADMIN;
            $help = $this->helpcentreRepo->getHelpCentreList($param)->get()->toArray();
            $data['all_records'] = $help;
            return view('admin.helpcentre.helpcentrelist_other', $data);
        } else {
            return view('admin.helpcentre.helpcentrelist', $data);
        }
    }

    /**
     * Get record for HelpCentre list
     * @author     Icreon Tech - dev5.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) {
        $user_type = session()->get('user')['user_type'];
        //$data['user_type'] = $user_type;
        $param['user_type'] = session()->get('user')['user_type'];
        $help = $this->helpcentreRepo->getHelpCentreList($param);
        // $help = $this->helpcentreRepo->getHelpCentreList($param)->get()->toArray();
        // asd($help);

        return Datatables::of($help)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('title')) {
                                $query->where('helpcentres.title', 'like', "%{$request->get('title')}%");
                            }
                            if ($request->has('category')) {
                                if (!empty($request->get('category'))) {
                                    $query->whereRaw(DB::raw("FIND_IN_SET(helpcentre_categories.categories, '" . $request->get('category') . "')"));
                                }
                            }
                            if ($request->has('strand')) {
                                if (!empty($request->get('strand'))) {
                                    $strand = implode(',', $request->get('strand'));
                                    $query->whereRaw(DB::raw("FIND_IN_SET(helpcentre_strands.strands, '" . $strand . "')"));
                                }
                            }
                            if ($request->has('substrand')) {
                                if (!empty($request->get('substrand'))) {
                                    $subStrand = implode(',', $request->get('substrand'));
                                    $query->whereRaw(DB::raw("FIND_IN_SET(helpcentre_substrands.substrands, '" . $subStrand . "')"));
                                }
                                //$query->whereRaw(DB::raw("FIND_IN_SET(helpcentre_substrands.substrands, '" . $request->get('help_strands') . "')"));
                            }
                            if ($request->has('status')) {
                                $query->where('helpcentres.status', '=', "{$request->get('status')}");
                            }
                        })
                        ->addColumn('action', function ($help) use ($param) {
                            if ($param['user_type'] == ADMIN) {
                                $act = '<a href="javascript:void(0);" data-remote="' . route('helpcentre.show', encryptParam($help->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>';
                                $act .= '&nbsp;<a href="' . route('helpcentre.edit', encryptParam($help->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>';
                                $act .= '&nbsp;<a href="javascript:void(0);" data-id="' . encryptParam($help->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                                return $act;
                            }
                            if ($param['user_type'] == TEACHER || $param['user_type'] == SCHOOL) {
                                //$act = '<a href="javascript:void(0);" data-remote="' . route('helpcentre.show', encryptParam($help->id)) . '" class="view_row"><i class="glyphicon glyphicon-download-alt"></i></a>';
                                //return $act;
                            }
                        })
                        ->editColumn('visible_to', function ($help) {
                            $visible_to = !empty($help->visible_to) ? explode(",", $help->visible_to) : array();
                            $userType = array();
                            $userTypeArray = visibleToArray();
                            foreach ($visible_to as $value) {
                                $userType[] = $userTypeArray[$value];
                            }
                            return implode(', ', $userType);
                        })
                        ->editColumn('category', function ($help) {
                            $categories = !empty($help->category) ? (explode(",", $help->category)) : array();
                            $categoriesArr = array();
                            $categoryMaster = helpCenterCategory();
                            foreach ($categories as $value) {
                                $categoriesArr[] = $categoryMaster[$value];
                            }
                            $categoriesStr = implode(', ', $categoriesArr);
                            if (strlen($categoriesStr) > 100)
                                return substr($categoriesStr, 0, 100) . '...';
                            else
                                return $categoriesStr;
                        })
                        ->editColumn('strand', function ($help) {
                            if (strlen($help->all_strand) > 100)
                                return substr($help->all_strand, 0, 100) . '...';
                            else
                                return $help->all_strand;
                        })
                        ->editColumn('substrand', function ($help) {
                            if (strlen($help->all_sub_strand) > 100)
                                return substr($help->all_sub_strand, 0, 100) . '...';
                            else
                                return $help->all_sub_strand;
                        })
                        ->editColumn('filename', function ($help) {
                            return str_replace(',', '<br>', $help->all_file_name);
                        })
                        ->editColumn('created_at', function ($help) {
                            return $help->created_at ? outputDateFormat($help->created_at) : '';
                        })
                        ->editColumn('updated_at', function ($help) {
                            return $help->updated_at ? outputDateFormat($help->updated_at) : '';
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new Group.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function create() {
        $data['page_heading'] = trans('admin/helpcentre.manage_helpcentre');
        $data['page_title'] = trans('admin/helpcentre.add_helpcentre');
        $data['trait'] = array('trait_1' => trans('admin/helpcentre.template_heading'), 'trait_1_link' => route('helpcentre.index'), 'trait_2' => trans('admin/helpcentre.add_group'));
        $data['JsValidator'] = 'App\Http\Requests\HelpCentre\HelpCentreCreateRequest';
        $data['status'] = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['category'] = helpCenterCategoryGroup();
        $data['media_types'] = ['' => trans('admin/admin.select_option')] + $this->helpcentreRepo->HelpcentreMediaType();
        $data['visible'] = $this->helpcentreRepo->visible_array();
        $data['setSubject'] = array('' => trans('admin/admin.select_option')) + questionSetSubjects();
        $arrStrands = $this->strandRepo->getstrandTree(FALSE);
        $data['strands'] = json_encode($arrStrands['strands']);
        $data['substrands'] = json_encode($arrStrands['substrands']);
        return view('admin.helpcentre.create', $data);
    }

    /**
     * Insert a new the Group
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\Group\GroupCreateRequest $request
     * @return Response
     */
    public function store(HelpCentreCreateRequest $request) {
        $inputs = $request->all();
        $inputs['created_by'] = session()->get('user')['id'];
        $this->helpcentreRepo->store($inputs);
        return redirect(route('helpcentre.index'))->with('ok', trans('admin/helpcentre.added_successfully'));
    }

    /**
     * Show the Group detail
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function show($id) {
        $id = decryptParam($id);
        $helpcentreQuery = $this->helpcentreRepo->getHelpCentreList(array('id' => $id, 'user_type' => session()->get('user')['user_type']));
        $helpcentre = $helpcentreQuery->get()->first();
        if (!count($helpcentre)) {
            redirect('error404');
        }
        $helpcentre = $helpcentre->toArray();
        $helpcentreFiles = $this->helpcentreRepo->getHelpfileRecord(array('helpcentres_id' => $helpcentre['id']));
        $helpcentre['helpcentreFiles'] = $helpcentreFiles;
        $helpcentre['userType'] = '';
        if (!empty($helpcentre['visible_to'])) {
            $userTypeArray = visibleToArray();
            $visible_to = explode(",", $helpcentre['visible_to']);
            foreach ($visible_to as $value) {
                $userType[] = $userTypeArray[$value];
            }
            $helpcentre['userType'] = implode(", ", $userType);
        }
        $mediaTypeIcon = $this->helpcentreRepo->HelpcentreMediaTypeTypeIcon();
        return view('admin.helpcentre.show', compact('helpcentre', 'mediaTypeIcon'));
    }

    /**
     * Show the form for edit Group.
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function edit($id) {
        $id = decryptParam($id);
        $data['id'] = $id;
        $helpcentre = $this->helpcentreRepo->getById($id)->toArray();
        $helpcentreFiles = $this->helpcentreRepo->getHelpfileRecord(array('helpcentres_id' => $helpcentre['id']));
        $helpcentre['helpcentreFiles'] = $helpcentreFiles;
        $helpcentre['visible_to'] = explode(",", $helpcentre['visible_to']);
        $helpcentre['category_str'] = $helpcentre['category'];
        $helpcentre['category'] = explode(",", $helpcentre['category']);
        $data['helpcentre'] = $helpcentre;

        $data['status'] = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['category'] = helpCenterCategoryGroup();
        $data['media_types'] = ['' => trans('admin/admin.select_option')] + $this->helpcentreRepo->HelpcentreMediaType();
        $data['visible'] = $this->helpcentreRepo->visible_array();
        $data['setSubject'] = array('' => trans('admin/admin.select_option')) + questionSetSubjects();
        $arrStrands = $this->strandRepo->getstrandTree(FALSE);
        $data['strands'] = json_encode($arrStrands['strands']);
        $data['substrands'] = json_encode($arrStrands['substrands']);

        $data['page_heading'] = trans('admin/helpcentre.manage_helpcentre');
        $data['page_title'] = trans('admin/helpcentre.edit_helpcentre');
        $data['trait'] = array('trait_1' => trans('admin/helpcentre.template_heading'), 'trait_1_link' => route('helpcentre.index'), 'trait_2' => trans('admin/helpcentre.edit_helpcentre'));
        $data['JsValidator'] = 'App\Http\Requests\HelpCentre\HelpCentreCreateRequest';
        $data['mediaTypeIcon'] = $this->helpcentreRepo->HelpcentreMediaTypeTypeIcon();
        return view('admin.helpcentre.edit')->with($data);
    }

    /**
     * Update the Group.
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\Group\GroupUpdateRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(HelpCentreCreateRequest $request, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();
        $inputs['id'] = $id;
        $inputs['updated_by'] = session()->get('user')['id'];
        $this->helpcentreRepo->update($inputs, $id);
        return redirect(route('helpcentre.index'))->with('ok', trans('admin/helpcentre.updated_successfully'));
    }

    /**
     * Delete a Group 
     * @author     Icreon Tech - dev5.
     * @param \Illuminate\Http\Request $request
     * @return type JSON Response
     */
    public function delete(Request $request) {
        $inputs = $request->all();
        $id = decryptParam($inputs['id']);
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['class_status'] = DELETED;
        $inputs['id'] = $id;
        $this->helpcentreRepo->destroyGroup($inputs, $id);
        return response()->json();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function helpCentre() {
        return view('front.helpcentre.helpcentre');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function helpcentreSubject($subject, $strand = NULL) {
        $subject = getSubject(ucfirst($subject));
        
        $resultStrandId = '';
        if (isset($strand) && !empty($strand)) {
           $resultStrandId = decryptParam($strand);
        }

        $strandResult = $this->strandRepo->getStrandTree(false);
        $strandTreeEnglish = $strandResult['strands'][ENGLISH];
        $strandTreeMath = $strandResult['strands'][MATH];
        if ($subject == 'Math') {
            $selectedStrandTree = $strandTreeMath;
            $param['cat'] = 10;
            $param['subcat'] = array_keys($selectedStrandTree);
        } else {
            $selectedStrandTree = $strandTreeEnglish;
            $param['cat'] = 9;
            $param['subcat'] = array_keys($selectedStrandTree);
        }
       

        $helpResult = $this->helpcentreRepo->searchHelpFrontSubject($param);
         

        $arrData = array();
        if (count($helpResult)) {
            foreach ($selectedStrandTree as $strandId => $strandName) {
                foreach ($helpResult as $row) {
                    $arrStrands = !empty($row['strands_id']) ? explode(",", $row['strands_id']) : array();
                    if (in_array($strandId, $arrStrands)) {
                        $arrData[$strandId]['strandname'] = $strandName;
                        $arrData[$strandId]['filedata'][$row['id']]['title'] = $row['title'];
                        $arrData[$strandId]['filedata'][$row['id']]['data'][] = $row;
                    }
                }
            }
        }
        
        if (isset($strand) && !empty($strand)) {
            if(isset($arrData[$resultStrandId])) {
                    $newArrData = $arrData[$resultStrandId];
                    $arrData = array();
                    $arrData[$resultStrandId] = $newArrData;
            }
            else {
                $arrData = array();
            }
        }

        $data['helpResult'] = $arrData;
        $data['category'] = $param['cat'];
        $data['subject'] = $subject;
        return view('front.helpcentre.helpcentre-detail', $data);
    }

    function helpCentreDetails(Request $request) {
        $inputs = $request->all();

        //$strand = decryptParam($strand);
        //$substrand = decryptParam($substrand);
        $strand = $inputs['strand'];
        $substrand = $inputs['substrand'];
        // $strand = $this->strandRepo->getById($strand)->toArray();
        $strandResult = $this->strandRepo->getStrandTree(false);
        // $strand['substrand'] = $strandResult['substrands'][$strand['id']][$substrand];
        $helpCentreData = $this->helpcentreRepo->getHelpCentreData(array(
            'strands_id' => $strand,
            'substrands_id' => $substrand
        ));

        $data['helpcentre_record'] = $helpCentreData;

        return view('front.helpcentre.helpcentre-detail', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function helpcentreStrand($subject, $strand) {
        $strandId = decryptParam($strand);
        $strand = $this->strandRepo->getById($strandId)->toArray();
        $subject = strtolower($subject);
        $data['subject'] = $subject;
        $data['trait'] = array('trait_1' => $strand['strand'], 'back_link' => route('helpcentre.subject', $subject));
        $data['revisions']['strand'] = array('strandId' => $strand['id'], 'strandName' => $strand['strand'], '');
        $subjectVar = getSubject($subject);
        $studentId = session()->get('user')['id'];
        $data['revisions']['assigned_revision'] = $this->studentTaskRepo->getStudentAssignedRevisions(array(
            'tutor_id' => session()->get('user')['tutor_id'],
            'student_id' => $studentId,
            'task_subject' => $subjectVar,
            'task_strand' => $strand['id'],
            'key_stage' => session()->get('user')['key_stage'],
            'year_group' => session()->get('user')['year_group'],
        ));
        return view('front.helpcentre.substrands', $data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function helpcentreDetail($strand, $substrand) {
        $strand = decryptParam($strand);
        $substrand = decryptParam($substrand);
        $strand = $this->strandRepo->getById($strand)->toArray();
        $strandResult = $this->strandRepo->getStrandTree(false);
        $strand['substrand'] = $strandResult['substrands'][$strand['id']][$substrand];
        $helpCentreData = $this->helpcentreRepo->getHelpCentreData(array(
            'strands_id' => $strand['id'],
            'substrands_id' => $substrand
        ));
        $data['helpcentre_record'] = $helpCentreData;
        $subject = $strand['subject'] == ENGLISH ? strtolower(ENGLISH) : strtolower(MATHS);
        $data['subject'] = $subject;
        $data['trait'] = array('trait_1' => $strand['strand'], 'trait_2' => $strand['substrand'], 'back_link' => route('helpcentre.strand', [$subject, encryptParam($strand['id'])]));
        return view('front.helpcentre.helpcentre-detail', $data);
    }

    /**
     * Check which subject is selected.
     *
     * @return Response
     */
    public function selectStrand(Request $request) {
        $inputs = $request->all();
        if ($inputs['selected_strands'] != '') {
            $selected_strands = explode(',', $inputs['selected_strands']);
        } else {
            $selected_strands = '';
        }
        if ((in_array("9", $inputs['id']) && in_array("10", $inputs['id'])) || in_array("11", $inputs['id'])) {
            $arrStrands = $this->strandRepo->getstrandTree(FALSE);
            $str = '';

            $subject = 'Math';
            $str.= '<optgroup label="' . $subject . '">';
            foreach ($arrStrands['strands'][$subject] as $key => $val) {
                if ($selected_strands != '' && in_array($key, $selected_strands))
                    $selected = 'selected';
                else
                    $selected = '';
                if (session()->get('user')['user_type'] == ADMIN) {
                    $str.= '<option ' . $selected . ' value="' . $key . '">' . $val . '</option>';
                } else {
                    $str.= '<option ' . $selected . ' value="' . $key . "-10" . '">' . $val . '</option>';
                }
            }
            $str.= '</optgroup>';
            $subject = 'English';
            $str.= '<optgroup label="' . $subject . '">';
            foreach ($arrStrands['strands'][$subject] as $key => $val) {
                if ($selected_strands != '' && in_array($key, $selected_strands))
                    $selected = 'selected';
                else
                    $selected = '';

                if (session()->get('user')['user_type'] == ADMIN) {
                    $str.= '<option ' . $selected . ' value="' . $key . '">' . $val . '</option>';
                } else {
                    $str.= '<option ' . $selected . ' value="' . $key . "-9" . '">' . $val . '</option>';
                }
            }
            $str.= '</optgroup>';
            echo $str;
        } else if (in_array("10", $inputs['id']) || in_array("9", $inputs['id'])) {
            $arrStrands = $this->strandRepo->getstrandTree(FALSE);
            $str = '';
            if (in_array("10", $inputs['id'])) {
                $subject = 'Math';
                $appendSelecter = '10';
            } else {
                $subject = 'English';
                $appendSelecter = '9';
            }

            $arrStrands = $arrStrands['strands'][$subject];
            foreach ($arrStrands as $key => $val) {
                if ($selected_strands != '' && in_array($key, $selected_strands))
                    $selected = 'selected';
                else
                    $selected = '';

                if (session()->get('user')['user_type'] == ADMIN) {
                    $str.= '<option ' . $selected . ' value="' . $key . '">' . $val . '</option>';
                } else {
                    $str.= '<option ' . $selected . ' value="' . $key . "-" . $appendSelecter . '">' . $val . '</option>';
                }
            }
            $str.= '</optgroup>';
            echo $str;
            //asd($subCategory);
        }
    }

    public function selectSubStrand(Request $request) {
        $inputs = $request->all();

        if ($inputs['selected_strands'] != '') {
            $selected_strands = explode(',', $inputs['selected_strands']);
        } else {
            $selected_strands = '';
        }
        $arrStrands = $this->strandRepo->getstrandTree(FALSE);
        //  asd($arrStrands);
        if (count($arrStrands) > 0) {
            foreach ($arrStrands['strands'] as $key => $val) {
                foreach ($val as $aa => $bb)
                    $parentStrandArray[$aa] = $bb;
            }
        }$str = '';
        if (!empty($inputs['id'])) {
            foreach ($inputs['id'] as $key => $val) {
                if (session()->get('user')['user_type'] == ADMIN) {
                    $val = $val;
                } else {
                    $detailArr = explode("-", $val);
                    $val = $detailArr[0];
                }
                $str.= '<optgroup label="' . $parentStrandArray[$val] . '">';
                foreach ($arrStrands['substrands'][$val] as $key2 => $val2) {
                    if ($selected_strands != '' && in_array($key2, $selected_strands))
                        $selected = 'selected';
                    else
                        $selected = '';
                    $str.= '<option ' . $selected . ' value="' . $key2 . '">' . $val2 . '</option>';
                }
                $str.= '</optgroup>';
            }
        }
        echo $str;
    }

    public function helpcentreSearch(Request $request) {
        $inputs = $request->all();
        $arrDatasystemCategory = array();
        $cntDatasystemCategory = 0;

        $arrDataenglishCategory = array();
        $cntDataenglishCategory = 0;

        $arrDatamathCategory = array();
        $cntDatamathCategory = 0;


        $categoriesArr = array("System Help" => array(1 => "User Centre", 2 => "Message Centre", 3 => "Revision Centre", 4 => "Test Centre", 5 => "Reporting", 6 => "My Account", 7 => 'Billing', 8 => 'General System Help'), "Educational" => array(9 => 'English', 10 => 'Math', 11 => 'General Educational Help'));
        $systemCategory = $categoriesArr['System Help'] + array(11 => 'General Educational Help');
        $allCategoryIds = array_merge(array_keys($systemCategory), [9, 10]);
        $param['user_type'] = session()->get('user')['user_type'];
        $param['category'] = !empty($inputs['category']) ? implode(',', $inputs['category']) : implode(',', $allCategoryIds);
        if (!empty($inputs['strands']))
            $param['strands'] = implode(',', $inputs['strands']);

        $helpResult = $this->helpcentreRepo->searchHelpCentre($param);
        //system category data
        if (count($helpResult['system']['result'])) {
            $resultsystemCategory = $helpResult['system']['result'];
            $systemCategorySelected = $helpResult['system']['cat'];
            foreach ($systemCategorySelected as $catId) {
                foreach ($resultsystemCategory as $row) {
                    $arrCategoriesData = !empty($row->category) ? explode(",", $row->category) : array();
                    if (in_array($catId, $arrCategoriesData)) {
                        /* $arrDatasystemCategory[$catId]['catname'] = $systemCategory[$catId];
                          $arrDatasystemCategory[$catId]['filedata'][$row->id]['title'] = $row->title;
                          $arrDatasystemCategory[$catId]['filedata'][$row->id]['data'][] = $row; */

                        $arrDatasystemCategory[$catId]['catname'] = $systemCategory[$catId];
                        $arrDatasystemCategory[$catId]['data'][] = $row;
                        $cntDatasystemCategory++;
                    }
                }
            }
        }

        $strandResult = $this->strandRepo->getStrandTree(false);
        $strandTreeEnglish = $strandResult['strands'][ENGLISH];
        $strandTreeEnglishIds = array_keys($strandTreeEnglish);
        $strandTreeMath = $strandResult['strands'][MATH];
        $strandTreeMathIds = array_keys($strandTreeMath);

        //english category data
        if (count($helpResult['english']['result'])) {
            $resultenglishCategory = $helpResult['english']['result'];
            $selectedStrandEnglsih = count($helpResult['english']['strands']) ? $helpResult['english']['strands'] : $strandTreeEnglishIds;
            foreach ($selectedStrandEnglsih as $strandId) {
                foreach ($resultenglishCategory as $row) {
                    $arrStrands = !empty($row->strands_id) ? explode(",", $row->strands_id) : $strandTreeEnglishIds;
                    if (in_array($strandId, $arrStrands)) {
                        /* $arrDataenglishCategory[$strandId]['catname'] = $strandTreeEnglish[$strandId];
                          $arrDataenglishCategory[$strandId]['filedata'][$row->id]['title'] = $row->title;
                          $arrDataenglishCategory[$strandId]['filedata'][$row->id]['data'][] = $row; */

                        $arrDataenglishCategory[$strandId]['catname'] = $strandTreeEnglish[$strandId];
                        $arrDataenglishCategory[$strandId]['data'][] = $row;
                        $cntDataenglishCategory++;
                    }
                }
            }
        }

        //math category data
        if (count($helpResult['math']['result'])) {
            $resultmathCategory = $helpResult['math']['result'];
            $selectedStrandMath = count($helpResult['math']['strands']) ? $helpResult['math']['strands'] : $strandTreeMathIds;
            foreach ($selectedStrandMath as $strandId) {
                foreach ($resultmathCategory as $row) {
                    $arrStrands = !empty($row->strands_id) ? explode(",", $row->strands_id) : $strandTreeMathIds;
                    if (in_array($strandId, $arrStrands)) {
                        /* $arrDatamathCategory[$strandId]['catname'] = $strandTreeMath[$strandId];
                          $arrDatamathCategory[$strandId]['filedata'][$row->id]['title'] = $row->title;
                          $arrDatamathCategory[$strandId]['filedata'][$row->id]['data'][] = $row; */

                        $arrDatamathCategory[$strandId]['catname'] = $strandTreeMath[$strandId];
                        $arrDatamathCategory[$strandId]['data'][] = $row;
                        $cntDatamathCategory++;
                    }
                }
            }
        }
        $data['resultCount'] = $cntDatasystemCategory + $cntDataenglishCategory + $cntDatamathCategory;
        $data['helpResultSystem'] = $arrDatasystemCategory;
        $data['helpResultMath'] = $arrDatamathCategory;
        $data['helpResultEnglsih'] = $arrDataenglishCategory;
        $data['filePath'] = public_path('uploads/helpdocuments/');
        $data['mediaTypeIcon'] = $this->helpcentreRepo->HelpcentreMediaTypeTypeIcon();
        return view('admin.helpcentre.helpcentrelistresult', $data);
    }

    public function helpCentreView($id) {
        $helpFileResult = $this->helpcentreRepo->getHelpfile($id);
        $this->helpcentreRepo->updateFileDownloadCount($helpFileResult);
        $data['helpResult'] = $helpFileResult;
        switch ($helpFileResult->media_type):
            case 1: //youtube and video
                return view('front.helpcentre.show-helpcentre-detail', $data);
                break;
            case 2: //youtube and video
                return view('front.helpcentre.show-helpcentre-detail', $data);
                break;
            case 3: // files and images 
                return response()->download(public_path('/uploads/helpdocuments/' . $helpFileResult->file_name), $helpFileResult->original_file_name);
                break;
            case 4: // files and images 
                return response()->download(public_path('/uploads/helpdocuments/' . $helpFileResult->file_name), $helpFileResult->original_file_name);
                break;
            case 5: // link
                return redirect($helpFileResult->media_link);
                break;
        endswitch;
    }

}
