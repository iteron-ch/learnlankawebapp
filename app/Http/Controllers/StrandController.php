<?php

/**
 * This controller is used for Strand.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Http\Requests\Strand\StrandCreateRequest;
use App\Http\Requests\Strand\StrandUpdateRequest;
use App\Repositories\StrandRepository;
use App\Models\Strand;
use Illuminate\Http\Request;

/**
 * This controller is used for strand.
 * @author     Icreon Tech - dev1.
 */
class StrandController extends Controller {

    /**
     * The StrandRepository instance.
     * @var App\Repositories\StrandRepository
     */
    protected $strandRepo;

    /**
     * Create a new SchoolController instance.
     * @param  App\Repositories\StrandRepository $strandRepo
     * @return void
     */
    public function __construct(StrandRepository $strandRepo) {
        $this->strandRepo = $strandRepo;
        $this->middleware('ajax', ['only' => ['delete', 'listRecord']]);
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
     * this is ued to display the strands in array
     * @return type
     */
    public function index() {
        $data['page_heading'] = trans('admin/strand.manage_strand');
        $data['page_title'] = trans('admin/strand.strands');
        $data['from_title'] = trans('admin/strand.add_strand');
        $data['JsValidator'] = 'App\Http\Requests\Strand\StrandCreateRequest';
        
        $strandArray = array();
        $data['subject'] = ['' => trans('admin/admin.select_option')] + questionSetSubjects();
        $parentStrandArray = array();
        $strandResult = Strand::getStrandAllList();
        if (count($strandResult) > 0) {
            foreach ($strandResult as $key => $val) {
                if ($val['parent_id'] == 0)
                    $parentStrandArray[$val['subject']][$val['id']] = $val['strand'];
            }
        }
        $data['parentStrandJson'] = json_encode($parentStrandArray);
        $data['parentStrand'] = array();
        $strandResult = $this->assignChildren($strandResult);
        $data['strand'] = $strandResult;
        $data['is_substrand'] = FALSE;
        return view('admin.strand.index', $data);
    }

    /**
     * Insert a new the strand
     * @author     Icreon Tech - dev1.
     * @param \App\Http\Requests\Strand\StrandCreateRequest $request
     * @return Response
     */
    public function store(StrandCreateRequest $request) {
        $inputs = $request->all();
        $inputs['created_by'] = session()->get('user')['id'];
        $this->strandRepo->store($inputs);
        return redirect(route('strand.index'))->with('ok', trans('admin/strand.added_successfully'));
    }

    /**
     * Show the form for edit school.
     * @author     Icreon Tech - dev1.
     * @param type String $id
     * @return Response
     */
    public function edit($id) {
        $id = decryptParam($id);
        $data['page_heading'] = trans('admin/strand.manage_strand');
        $data['page_title'] = trans('admin/strand.strands');
        $data['from_title'] = trans('admin/strand.edit_strand');
        $data['JsValidator'] = 'App\Http\Requests\Strand\StrandUpdateRequest';
        $data['status'] = array('' => trans('admin/admin.select_option')) +  array("Active" => "Active", "Inactive" => "Inactive");
        $strandRes = Strand::findOrFail($id)->toArray();
        $strandRes['is_substrand'] = $strandRes['parent_id'] != 0 ? TRUE: FALSE;
        $strandArray = array();
        $data['subject'] = ['' => trans('admin/admin.select_option')] + questionSetSubjects();
        $parentStrandArray = array();
        $strandResult = Strand::getStrandAllList();
        if (count($strandResult) > 0) {
            foreach ($strandResult as $key => $val) {
                if ($val['parent_id'] == 0)
                    $parentStrandArray[$val['subject']][$val['id']] = $val['strand'];
            }
        }
        $data['parentStrandJson'] = json_encode($parentStrandArray);
        $data['parentStrand'] = array('' => trans('admin/admin.select_option')) + $parentStrandArray[$strandRes['subject']];
        $strandResult = $this->assignChildren($strandResult);
        $data['strand'] = $strandResult;
        $data['id'] = $id;
        $data['strandResult'] = $strandRes;
        return view('admin.strand.edit',$data);
    }

    /**
     * Update the school.
     * @author     Icreon Tech - dev1.
     * @param \App\Http\Requests\School\SchoolUpdateRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(StrandUpdateRequest $request, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->strandRepo->update($inputs, $id);
        return redirect(route('strand.index'))->with('ok', trans('admin/strand.updated_successfully'));
    }
    
     /**
     * Delete a strand 
     * @author     Icreon Tech - dev2.
     * @param \Illuminate\Http\Request $request
     * @return type JSON Response
     */
    public function delete(Request $request) {
        $inputs = $request->all();
        $id = decryptParam($inputs['id']);
        $inputs['updated_by'] = session()->get('user')['id'];
        //$inputs['status'] = DELETED;
        $inputs['id'] = $id;
        $this->strandRepo->destroyStrand($inputs, $id);
        session()->flash('ok', trans('admin/admin.delete-success')); 
        return response()->json();
    }

}
