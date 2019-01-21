<?php

namespace App\Http\Controllers;

use App\Repositories\PaymentmethodRepository;
use App\Http\Requests\PaymentmethodSaveRequest;
use App\Models\Paymentmethod;
use Illuminate\Http\Request;
use Datatables;

class PaymentMethodsController extends Controller {

    /**
     * The UserRepository instance.
     *
     * @var App\Repositories\UserRepository
     */
    protected $paymentmethodRepository;

    /**
     * Create a new UserController instance.
     *
     * @param  App\Repositories\UserRepository $user_gestion
     * @param  App\Repositories\RoleRepository $role_gestion
     * @return void
     */
    public function __construct(PaymentmethodRepository $paymentmethodRepository) {
        $this->paymentmethodRepository = $paymentmethodRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $title = trans('admin/paymentmethod.template_heading');
        $manage_payment = trans('admin/paymentmethod.manage_payment');
        $paymentmethod = $this->paymentmethodRepository->getPaymentDetails($status = ACTIVE)->get()->toArray();
        //asd($paymentmethod[0]['id']);    
        return view('admin.paymentmethod.paymentmethod', array_merge($this->paymentmethodRepository->edit(($paymentmethod[0]['id'])), compact('title', 'manage_payment')));
        //return view('admin.paymentmethod.paymentmethod', array_merge($this->feesRepository->edit(($feeRecord[0]['id'])), compact('title', 'updated_title','manage_fees')));
        //return view('admin.tutor.create', $data);
    }

    /**
     * Display a listing of the Paymen records.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function listRecord(Request $request) {
        $status = INACTIVE;
        $paymentDetails = $this->paymentmethodRepository->getPaymentDetails($status);
        return Datatables::of($paymentDetails)
                        ->editColumn('transaction_key', function ($paymentDetails) {
                            return $paymentDetails->transaction_key;
                        })
                        ->editColumn('transaction_user_id', function ($paymentDetails) {
                            return $paymentDetails->transaction_user_id;
                        })
                        ->editColumn('password_label', function ($paymentDetails) {
                            return $paymentDetails->transaction_password;
                        })
                        ->editColumn('email_label', function ($paymentDetails) {
                            return $paymentDetails->paypal_email;
                        })
                        ->editColumn('paypal_type', function ($paymentDetails) {
                            return $paymentDetails->paypal_method;
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PaymentmethodSaveRequest $request) {
        $this->paymentmethodRepository->store($request->all());
        //return redirect('cmspage')->with('ok', trans('admin/cmspage.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $title = trans('admin/paymentmethod.template_heading');
        return view('admin.paymentmethod.paymentmethod', array_merge($this->paymentmethodRepository->edit($id), compact('title')));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PaymentmethodSaveRequest $request, $id) {
        $title = trans('admin/paymentmethod.template_heading');
        $ok = trans('admin/paymentmethod.success_msg');
        $this->paymentmethodRepository->update($request->all(), $id);
        return redirect(route('paymentmethod.index'))->with('ok', trans('admin/paymentmethod.save_msg'));
        //return view('admin.paymentmethod.paymentmethod', array_merge($this->paymentmethodRepository->edit($id), compact('title', 'ok')));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}
