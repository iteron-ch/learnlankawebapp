<?php

/**
 * This controller is used for Fee.
 * @package    Fee
 * @author     Icreon Tech  - dev5.
 */
namespace App\Http\Controllers;

use App\Repositories\FeesRepository;
use App\Http\Requests\FeesSaveRequest;
use Illuminate\Http\Request;
use App\Http\Requests;
use Datatables;

class FeesController extends Controller {

    /**
     * The FeesRepository instance.
     * @var App\Repositories\FeesRepository
     */
    protected $feesRepository;

    /**
     * Create a new FeesController instance.
     * @author     Icreon Tech - dev5.
     * @param  App\Repositories\FeeRepository
     * @return void
     */
    public function __construct(FeesRepository $feesRepository) {
        $this->feesRepository = $feesRepository;
    }

    /**
     * Display a listing of the Fee data.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function index() {
        //
        $title = trans('admin/fee.template_heading');
        $manage_fees = trans('admin/fee.manage_fees');
        $updated_title = trans('admin/fee.updated_template_heading');
        $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->toArray();
        return view('admin.fees.fee', array_merge($this->feesRepository->edit(($feeRecord[0]['id'])), compact('title', 'updated_title','manage_fees')));
    }

    /**
     * Show the form for creating a new Fee structure.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function create() {
        return view('admin.fees.fee')->with([
                    'title' => trans('admin/fee.template_heading')
        ]);
    }

    /**
     * Store a newly created Fee record in storage.
     * @param  Request  $request
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function store(FeesSaveRequest $request) {
        $this->feesRepository->store($request->all());
        //return redirect('cmspage')->with('ok', trans('admin/cmspage.created'));
    }

    /**
     * Display the specified Fee records.
     * @param  int  $id
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified Fee .
     * @param  int  $id
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function edit($id) {
        // $title = trans('admin/fee.template_heading');
        // $feeRecord = $this->feesRepository->getFee($status = ACTIVE)->get()->toArray();
        // return view('admin.fees.fee', array_merge($this->feesRepository->edit(($feeRecord[0]['id'])), compact('title')));
    }

    /**
     * Update the specified Fee structure in storage.
     * @param  Request  $request
     * @param  int  $id
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function update(FeesSaveRequest $request, $id) {
        $title = trans('admin/fee.template_heading');
        $ok = trans('admin/fee.save_msg');
        $this->feesRepository->update($request->all(), $id);
        //  return view('admin.fees.fee', array_merge($this->feesRepository->edit($id), compact('title', 'ok')));
        return redirect(route('fees.index'))->with('ok', trans('admin/fee.save_msg'));
    }

    /**
     * Display a listing of the Fee records.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function listRecord(Request $request) {
        $fees = $this->feesRepository->getFee($status = INACTIVE);
        return Datatables::of($fees)
                        ->editColumn('school_sign_up_fee', function ($fee) {
                            return $fee->school_sign_up_fee;
                        })
                        ->editColumn('parent_sign_up_fee', function ($fee) {
                            return $fee->parent_sign_up_fee;
                        })
                        ->editColumn('per_student_fee', function ($fee) {
                            return $fee->per_student_fee;
                        })
                        ->editColumn('per_5_student_fee', function ($fee) {
                            return $fee->per_5_student_fee;
                        })
                        ->editColumn('updated_by', function ($fee) {
                            return $fee->username;
                        })
                        ->editColumn('updated_at', function ($fee) {
                            return $fee->updated_at ? outputDateFormat($fee->updated_at) : '';
                        })
                        ->make(true);
    }

    /**
     * Remove the specified Fee record from storage.
     * @param  int  $id
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function destroy($id) {
        //
    }

}
