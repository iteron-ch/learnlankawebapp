<?php

/**
 * This controller is used for Vouchers.
 * @package    Vouchers
 * @author     Icreon Tech  - dev5.
 */

namespace App\Http\Controllers;

use App\Repositories\VoucherRepository;
use App\Http\Requests\Voucher\VoucherCreateRequest;
use App\Http\Requests\Voucher\VoucherUpdateRequest;
use Illuminate\Http\Request;
use App\Repositories;
use App\Models\Voucher;
use Datatables;

/**
 * This controller is used for Voucher.
 * @author     Icreon Tech - dev5.
 */
class VoucherController extends Controller {

    /**
     * The VoucherRepository instance.
     *
     * @var App\Repositories\VoucherRepository
     */
    protected $voucherRepo;

    /**
     * Create a new VoucherController instance.
     * @param  App\Repositories\VoucherRepository voucherRepo
     * @return void
     */
    public function __construct(VoucherRepository $voucherRepo) {
        $this->voucherRepo = $voucherRepo;
    }

    /**
     * Display a listing of the all voucher records.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function index() {
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $user_type = ['' => trans('admin/admin.select_option')] + voucherUserType();
        $data['status'] = $status;
        $data['user_type'] = $user_type;
        return view('admin.voucher.voucherlist', $data);
    }

    /**
     * Get record for Voucher list
     * @author     Icreon Tech - dev5.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) {
        $param['user_type'] = 'VOUCHER'; // voucher    
        $vouchers = $this->voucherRepo->getVoucherList();
        return Datatables::of($vouchers)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('voucher_code')) {
                                $query->where('voucher_code', 'like', "%{$request->get('voucher_code')}%");
                            }
                            if ($request->has('user_type')) {
                                $query->where('user_type', '=', "{$request->get('user_type')}");
                            }
                            if ($request->has('status')) {
                                $query->where('status', '=', "{$request->get('status')}");
                            }
                        })
                        ->addColumn('action', function ($voucher) {
                            return '<a href="javascript:void(0);" data-remote="' . route('voucher.show', encryptParam($voucher->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                    <a href="' . route('voucher.edit', encryptParam($voucher->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="javascript:void(0);" data-id="' . encryptParam($voucher->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                        })
                        ->editColumn('start_date', function ($voucher) {
                            return $voucher->start_date ? outputDateFormat($voucher->start_date) : '';
                        })
                        ->editColumn('end_date', function ($voucher) {
                            return $voucher->end_date ? outputDateFormat($voucher->end_date) : '';
                        })
                        ->editColumn('user_type', function ($voucher) {
                            if ($voucher->user_type == SCHOOL) {
                                return 'School';
                            } elseif ($voucher->user_type == PARENT) {
                                return 'Parent/Tutor';
                            }
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new Voucher record.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function create() {

        $data['page_title'] = trans('admin/voucher.add_voucher');
        $data['page_heading'] = trans('admin/voucher.manage_voucher');
        $data['trait'] = array('trait_1' => trans('admin/voucher.voucher'), 'trait_1_link' => route('voucher.index'), 'trait_2' => trans('admin/voucher.add_voucher'));
        $data['JsValidator'] = 'App\Http\Requests\Voucher\VoucherCreateRequest';
        $data['discount_type'] = ['' => trans('admin/admin.select_option')] + voucherDiscountType();
        $data['user_type'] = ['' => trans('admin/admin.select_option')] + voucherUserType();
        $data['status'] = ['' => trans('admin/admin.select_option')] + statusArray();
        return view('admin.voucher.create', $data);
    }

    /**
     * Insert a new the voucher
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\Voucher\VoucherCreateRequest $request
     * @return Response
     */
    public function store(VoucherCreateRequest $request) {
        $inputs = $request->all();
        $inputs['created_by'] = session()->get('user')['id'];
        $inputs['updated_by'] = session()->get('user')['id'];
        $this->voucherRepo->store($inputs);
        return redirect(route('voucher.index'))->with('ok', trans('admin/voucher.added_successfully'));
    }

    /**
     * Show the Voucher detail
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function show($id) {
        $status = statusArray();
        $voucherRepo = $this->voucherRepo->showVoucher(decryptParam($id));
        return view('admin.voucher.show', compact('voucherRepo', 'status'));
    }

    /**
     * Show the form for edit voucher record.
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function edit($id) {
        $id = decryptParam($id);
        $voucher = Voucher::findOrFail($id)->toArray();
        $voucher['start_date'] = outputDateFormat($voucher['start_date']);
        $voucher['end_date'] = outputDateFormat($voucher['end_date']);
        $data['voucher'] = $voucher;
        $data['discount_type'] = ['' => trans('admin/admin.select_option')] + voucherDiscountType();
        $data['user_type'] = ['' => trans('admin/admin.select_option')] + voucherUserType();
        $data['status'] = ['' => trans('admin/admin.select_option')] + statusArray();
        $data['page_title'] = trans('admin/voucher.edit_voucher');
        $data['page_heading'] = trans('admin/voucher.manage_voucher');
        $data['trait'] = array('trait_1' => trans('admin/voucher.voucher'), 'trait_1_link' => route('voucher.index'), 'trait_2' => trans('admin/voucher.edit_voucher'));
        $data['JsValidator'] = 'App\Http\Requests\Voucher\VoucherUpdateRequest';
        return view('admin.voucher.edit')->with($data); //, );
    }

    /**
     * Update the voucher record.
     * @author Icreon Tech - dev5.
     * @param \App\Http\Requests\Voucher\VoucherUpdateRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(VoucherUpdateRequest $request, $id) {
        $id = decryptParam($id);
        $inputs = $request->all();
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->voucherRepo->update($inputs, $id);
        return redirect(route('voucher.index'))->with('ok', trans('admin/voucher.updated_successfully'));
    }

    /**
     * Delete a voucher record. 
     * @author Icreon Tech - dev5.
     * @param \Illuminate\Http\Request $request
     * @return type JSON Response
     */
    public function delete(Request $request) {
        $inputs = $request->all();
        $id = decryptParam($inputs['id']);
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['status'] = DELETED;
        $inputs['id'] = $id;
        $this->voucherRepo->destroyVoucher($inputs, $id);
        return response()->json();
    }

    /**
     * apply a voucher code for subscription
     * @author Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return true/false
     */
    public function applyVoucher(Request $request) {
        $inputs = $request->all();
        $params = array();
        $params['voucher_code'] = $inputs['voucher_code'];
        $params['user_type'] = $inputs['user_type'];
        $voucherRepo = $this->voucherRepo->getVoucherDetails($params);
        
        if(!empty($voucherRepo)) {
            $returnArray['discount_type'] = $voucherRepo['discount_type'];
            $returnArray['discount'] = $voucherRepo['discount'];
            return json_encode($returnArray);
        }
        else {
            
        }
        die;
       // return json_encode() 
    }

}
