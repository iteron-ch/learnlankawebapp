<?php

/**
 * This controller is used for Invoice.
 * @package    User
 * @author     Icreon Tech  - dev5.
 */

namespace App\Http\Controllers;

use App\Repositories\InvoiceRepository;
use Illuminate\Http\Request;
use App\Models\Invoice;
use DB;
use Datatables;

/**
 * This controller is used for Invoice.
 * @author     Icreon Tech - dev5.
 */
class InvoiceController extends Controller {

    /**
     * The UserRepository instance.
     * @var App\Repositories\UserRepository
     */
    protected $invoice;

    /**
     * Create a new TutorController instance.
     * @param  App\Repositories\UserRepository $userRepo
     * @return void
     */
    public function __construct(InvoiceRepository $invoice) {
        $this->invoice = $invoice;
        $this->middleware('ajax', ['only' => ['delete', 'listRecord']]);
    }

    /**
     * Display Invoice listing page.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function index() {
        return view('admin.invoice.invoicelist');
    }

    /**
     * Get record for invoice list
     * @author     Icreon Tech - dev1.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) {
        //asd($request->all());
        //$users = $this->invoice->getInvoiceList()->get()->toArray();
        $users = $this->invoice->getInvoiceList();
        //asd($users);
        return Datatables::of($users)
                        ->filter(function ($query) use ($request) {
                             if ($request->has('transaction_id')) {
                                $query->where('payments.transaction_id', '=', "{$request->get('transaction_id')}");
                            }
                            if ($request->has('no_of_students')) {
                                $query->where('no_of_students', '=', "{$request->get('no_of_students')}");
                            }
                            if ($request->has('amount')) {
                                $query->where('amount', '=', "{$request->get('amount')}");
                            }
                        })
                        /*->editColumn('subscription_start_date', function ($user) {
                            return $user->subscription_start_date ? outputDateFormat($user->subscription_start_date) : '';
                        })
                        ->editColumn('subscription_expiry_date', function ($user) {
                            return $user->subscription_expiry_date ? outputDateFormat($user->subscription_expiry_date) : '';
                        })*/
                        ->editColumn('no_of_students', function ($user) {
                            return $user->no_of_students ? $user->no_of_students : '0';
                        })
                        ->editColumn('status', function ($user) {
                            if(strtolower($user->status) == 'success')
                                return 'Completed';
                            else 
                                return $user->status;
                            
                        })
                        ->editColumn('payment_type', function ($user) {
                            if(strtolower($user->payment_type) == 'creditcard')
                                return 'Card';
                            else 
                                return 'Invoice';
                            
                        })
                        ->addColumn('action', function ($user) {
                            return '<a href="javascript:void(0);" data-remote="' . route('invoice.show', encryptParam($user->payment_id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>'
                                    .'&nbsp;<a href="javascript:void(0);" data-remote="' . route('invoice.print', encryptParam($user->payment_id)) . '" class="print_row"><i class="glyphicon glyphicon-print"></i></a>';
                        })
                        ->make(true);
    }

    /**
     * Show the Invoice detail
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function show($id) {
        $params['id'] = decryptParam($id);
        $invoiceDetail = $this->invoice->getInvoiceList($params)->get()->toArray();
        $invoiceDetails = $invoiceDetail[0];
        return view('admin.invoice.show', compact('invoiceDetails'));
    }

    /**
     * Show the Invoice detail
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function prints($id) {
        $params['id'] = decryptParam($id);
        $invoiceDetail = $this->invoice->getInvoicePrintList($params)->get()->toArray();
        $invoiceDetails = $invoiceDetail[0];
        //asd($invoiceDetails);
        return view('admin.invoice.print', compact('invoiceDetails'));
    }
}
