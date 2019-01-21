<?php

/**
 * This controller is used for Enquiries.
 * @package    Enquiries
 * @author     Icreon Tech  - dev5.
 */

namespace App\Http\Controllers;

use App\Repositories\EnquiryRepository;
use App\Http\Requests\Enquiry\EnquiryRequest;
use Illuminate\Http\Request;
use App\Models\County;
use App\Models\User;
use App\Repositories;
use App\Models\Enquiry;
use Datatables;
use App\Repositories\EmailRepository;
use App\Models\Howfind;
/**
 * This controller is used for Enquiry.
 * @author     Icreon Tech - dev5.
 */
class EnquiryController extends Controller {

    /**
     * The EnquiryRepository instance.
     *
     * @var App\Repositories\EnquiryRepository
     */
    protected $enquiryRepo;

    /**
     * Create a new EnquiryController instance.
     * @param  App\Repositories\EnquiryRepository enquiryRepo
     * @return void
     */
    public function __construct(EnquiryRepository $enquiryRepo) {
        $this->enquiryRepo = $enquiryRepo;
    }

    /**
     * Display a listing of the all Enquiry records.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function index() {
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $userType = ['' => trans('admin/admin.select_option')] + array('Teacher' => 'Teacher', 'Parent' => 'Parent', 'Tutor' => 'Tutor');
        $data['how_hear'] = ['' => trans('admin/admin.select_option')] + Howfind::getHowFindEnquiry() + [OTHER_VALUE => trans('admin/admin.how_find_other')];
        $data['status'] = $status;
        $data['userType'] = $userType;
        return view('enquiry.enquirylist', $data);
    }

    /**
     * Get record for Enquiry list
     * @author     Icreon Tech - dev5.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) {
        $enquiry = $this->enquiryRepo->getEnquiryList();
        return Datatables::of($enquiry)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('first_name')) {
                                $query->where('first_name', 'like', "%{$request->get('first_name')}%");
                            }
                            if ($request->has('last_name')) {
                                $query->where('last_name', 'like', "%{$request->get('last_name')}%");
                            }
                            if ($request->has('email')) {
                                $query->where('email', 'like', "%{$request->get('email')}%");
                            }

                            if ($request->has('user_type')) {
                                $query->where('user_type', '=', "{$request->get('user_type')}");
                            }
                            if ($request->has('how_hear')) {
                                $query->where('how_hear', 'like', "%{$request->get('how_hear')}%");
                            }
                            if ($request->has('job_role')) {
                                $query->where('job_role', 'like', "%{$request->get('job_role')}%");
                            }
                        })
                        ->addColumn('action', function ($enquiry) {
                            return '<a href="javascript:void(0);" data-remote="' . route('enquiry.show', encryptParam($enquiry->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>';
                        })
                         ->editColumn('created_at', function ($enquiry) {
                            return $enquiry->created_at ? outputDateFormat($enquiry->created_at) : '';
                        })
                        ->editColumn('how_hear', function ($enquiry) {
                            return ($enquiry->how_hear == '-1') ? $enquiry->how_hear_other : $enquiry->how_hear;
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new Enquiry record.
     * @author     Icreon Tech - dev5.
     * @return Response
     */
    public function create() {
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        $data['county'] = ['' => trans('admin/admin.select_option')] + County::getCounty() + [OTHER_VALUE => trans('admin/admin.other')];
        $data['page_title'] = trans('front/enquiry.add_enquiry');
        $data['page_heading'] = trans('front/enquiry.manage_enquiry');
        $data['trait'] = array('trait_1' => trans('front/enquiry.enquiry'), 'trait_1_link' => route('enquiry.index'), 'trait_2' => trans('admin/enquiry.add_enquiry'));
        $data['JsValidator'] = 'App\Http\Requests\Enquiry\EnquiryCreateRequest';
        $data['user_types'] = ['' => trans('admin/admin.select_option')] + enquiryUserType();
        $user_type = SCHOOL;
        $schools = ['' => trans('admin/admin.select_option')] + User::getSchools($user_type);
        $data['schools'] = $schools;
        $data['how_hear'] = ['' => trans('admin/admin.select_option')] + enquiryUserType() + [OTHER_VALUE => trans('admin/admin.other')];
        $data['cities'] = ['' => trans('admin/admin.select_option')] + cityDD();
        return view('enquiry.create', $data);
    }

    /**
     * Insert a new the Enquiry
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\Enquiry\EnquiryCreateRequest $request
     * @return Response
     */
    public function store(EnquiryRequest $request, EmailRepository $emailRepo) {
        $inputs = $request->all();
        //asd($inputs);
        $id = $this->enquiryRepo->store($inputs);
        //send email notification to admin
        if ($inputs['user_type'] == 'Parent') {
            $emailTemplateId = '15';
        } else if ($inputs['user_type'] == 'Teacher') {
            $emailTemplateId = '16';
        } else if ($inputs['user_type'] == 'Tutor') {
            $emailTemplateId = '17';
        }

        $enquiryRepo = $this->enquiryRepo->showEnquiry($id)->toArray();
        $emailParam = array(
            'addressData' => array(
                'to_email' => env('ADMIN_FROM_EMAIL'),
                'to_name' => env('ADMIN_FROM_NAME') ,

               // 'cc_email' => array('rohanjetha1992@gmail.com','sanjeev.rajput@icreon.com'),
               // 'cc_name' => array('rohan','sanjeev'), 
               // 'cc_email' => env('ADMIN_CC_EMAIL'),
              //  'cc_name' => env('ADMIN_CC_NAME'), 

                
            ),
            'userData' => array(
                'first_name' => ucfirst($enquiryRepo[0]['first_name']),
                'last_name' => ucfirst($enquiryRepo[0]['last_name']),
                'title' => ucfirst($enquiryRepo[0]['title']),
                'user_type' => $enquiryRepo[0]['user_type'],
                'email' => $enquiryRepo[0]['email'],
                'city' => $enquiryRepo[0]['city'],
                'postal_code' => $enquiryRepo[0]['postal_code'],
                'county' => $enquiryRepo[0]['county'],
                'school' => $enquiryRepo[0]['school'],
                'contact_no' => $enquiryRepo[0]['contact_no'],
                'created_at' => outputDateFormat($enquiryRepo[0]['created_at']),
                'how_hear' => !empty($enquiryRepo[0]['how_hear_other']) ? $enquiryRepo[0]['how_hear_other'] : $enquiryRepo[0]['how_hear'],
                'job_role' => $enquiryRepo[0]['job_role'],
            )
        );
        // asd($emailParam);
        //$emailRepo->sendEmail($emailParam, $emailTemplateId);
        //end email notification to admin
        
        $emailParam = array(
            'addressData' => array(
                'to_email' => $enquiryRepo[0]['email'],
                'to_name' => ucfirst($enquiryRepo[0]['first_name']) . ucfirst($enquiryRepo[0]['last_name']),
            ),
            'userData' => array(
                'first_name' => ucfirst($enquiryRepo[0]['first_name']),
                'title' => ucfirst($enquiryRepo[0]['title']),
            )
        );
        if ($inputs['user_type'] == 'Parent' || $inputs['user_type'] == 'Tutor') {
            $emailTemplateId = '18';
            $emailParam['userData']['username'] = 'demoparentsats';
            $emailParam['userData']['child_username'] = 'demopupilparent';
            $emailParam['userData']['password'] = '123456';
        } else if ($inputs['user_type'] == 'Teacher') {
            $emailTemplateId = '43';
            $emailParam['userData']['username'] = 'demoteachersats';
            $emailParam['userData']['child_username'] = 'demopupilschool';
            $emailParam['userData']['password'] = '123456';
        }
        //asd($emailParam);
        $emailRepo->sendEmail($emailParam, $emailTemplateId);
        return redirect(route('enquiry.enquiryconfirm'))->with('ok', trans('front/enquiry.added_successfully'));
    }

    /**
     * Show the Enquiry detail
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function show($id) {
        $enquiryRepo = $this->enquiryRepo->showEnquiry(decryptParam($id))->toArray();
        $data['enquiryRepo'] = $enquiryRepo[0];
        return view('enquiry.show', $data);
    }

    /**
     * Show the form for sending new Enquiry.
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function sendenquiry() {
        $data = array();
        $data['title'] = ['' => trans('admin/admin.select_option')] + titles();
        $data['how_hear'] = ['' => trans('admin/admin.select_option')] + Howfind::getHowFindEnquiry() + [OTHER_VALUE => trans('admin/admin.how_find_other')];
        $data['JsValidator'] = 'App\Http\Requests\Enquiry\EnquiryRequest';
        return view('enquiry.sendenquiry', $data);
    }

    public function enquiryconfirm() {
        return view('enquiry.thanks');
    }

}
