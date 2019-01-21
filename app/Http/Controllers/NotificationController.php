<?php

/**
 * This controller is used for Notification.
 * @package    Notification
 * @author     Icreon Tech  - dev5.
 */

namespace App\Http\Controllers;

use App\Repositories\NotificationRepository;
use App\Http\Requests\Notification\NotificationCreateRequest;
use App\Http\Requests\Notification\NotificationUpdateRequest;
use App\Http\Requests\Notification\NotificationUpdateProfileRequest;
use Illuminate\Http\Request;
use Input;
use App\Repositories;
use App\Models\Notification;
use Datatables;

/**
 * This controller is used for Notification.
 * @author     Icreon Tech - dev5.
 */
class NotificationController extends Controller {

    /**
     * The NotificationRepository instance.
     *
     * @var App\Repositories\NotificationRepository
     */
    protected $notification_repo;
    

    /**
     * Create a new NotificationController instance.
     * @param  App\Repositories\NotificationRepository notification_repo
     * @return void
     */
    public function __construct(NotificationRepository $notification_repo) {
        $this->notification_repo = $notification_repo;
        
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $status = ['' => trans('admin/admin.select_option')] + statusArray();
        $visible_to = ['' => trans('admin/admin.select_option')] + visibleToArray();
        $data['status'] = $status;
        $data['visible_to'] = $visible_to;
        return view('admin.notification.notificationlist', $data);
    }

    /**
     * Get record for Notification list
     * @author     Icreon Tech - dev5.
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function listRecord(Request $request) {
        $notification = $this->notification_repo->getNotificationList();
        return Datatables::of($notification)
                        ->filter(function ($query) use ($request) {
                            if ($request->has('title')) {
                                $query->where('title', 'like', "%{$request->get('title')}%");
                            }
                            if ($request->has('status')) {
                                $query->where('status', '=', "{$request->get('status')}");
                            }
                        })
                        ->addColumn('action', function ($notification) {
                            
                            return '<a href="javascript:void(0);" data-remote="' . route('notification.show', encryptParam($notification->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                    <a href="' . route('notification.edit', encryptParam($notification->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="javascript:void(0);" data-id="' . encryptParam($notification->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                            
                        })
                        ->editColumn('user_type', function ($notification) {
                            $user_type = ($notification->user_type);
                            $user_array = (explode(",", $user_type));
                            $allUserType = array();
                            $userTypeArray = visibleToArray();
                            foreach ($user_array as $value) {
                                if ($value == SCHOOL) {
                                    $allUserType[] = $userTypeArray[SCHOOL];
                                } if ($value == TEACHER) {
                                    $allUserType[] = $userTypeArray[TEACHER];
                                } if ($value == TUTOR) {
                                    $allUserType[] = $userTypeArray[TUTOR];
                                } if ($value == ADMIN) {
                                    $allUserType[] = $userTypeArray[ADMIN];
                                }
                                if ($value == STUDENT) {
                                    $allUserType[] = $userTypeArray[STUDENT];
                                }
                            }
                            return implode(', ', $allUserType);
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
        $data['page_heading'] = trans('admin/notification.manage_notification');
        $data['page_title'] = trans('admin/notification.add_notification');
        $data['trait'] = array('trait_1' => trans('admin/notification.template_heading'), 'trait_1_link' => route('notification.index'), 'trait_2' => trans('admin/notification.add_notification'));
        $data['JsValidator'] = 'App\Http\Requests\Notification\NotificationCreateRequest';
        $data['status'] = array('' => 'Select') + statusArray();
        $data['selected_visible'] = $this->notification_repo->visible_array();
        return view('admin.notification.create', $data);
    } 

    /**
     * Insert a new the Group
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\Group\GroupCreateRequest $request
     * @return Response
     */
    public function store(NotificationCreateRequest $request) {
        $inputs = $request->all();
        //asd($inputs);
        $this->notification_repo->store($inputs);
        return redirect(route('notification.index'))->with('ok', trans('admin/notification.added_successfully'));
    }

    /**
     * Show the Group detail
     * @author     Icreon Tech - dev5.
     * @param type String $id
     * @return Response
     */
    public function show($id) { 
        $status = statusArray();
        $notification_record = $this->notification_repo->getNotificationRecord(decryptParam($id))->toArray();
        //asd($notification_record);
        return view('admin.notification.show', compact('notification_record', 'status'));
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
        $notification_list = $this->notification_repo->getNotificationRecord($id)->toArray();
        $notification_list = $notification_list[0];
        $notification_list['user_type'] = explode(",", $notification_list['user_type']);
        
        //asd($notification_list['user_type']);
        $data['notification_list'] = $notification_list;
        $data['selected_visible'] = $this->notification_repo->visible_array();
        $data['status'] = array('' => 'Select') + statusArray();
        $data['page_heading'] = trans('admin/notification.manage_notification');
        $data['page_title'] = trans('admin/notification.edit_notification');
        $data['trait'] = array('trait_1' => trans('admin/notification.template_heading'), 'trait_1_link' => route('notification.index'), 'trait_2' => trans('admin/notification.edit_notification'));
        $data['JsValidator'] = 'App\Http\Requests\Notification\NotificationCreateRequest';
        return view('admin.notification.edit')->with($data); //, );
    }

    /**
     * Update the Group.
     * @author     Icreon Tech - dev5.
     * @param \App\Http\Requests\Group\GroupUpdateRequest $request
     * @param type string $id
     * @return Response
     */
    public function update(NotificationUpdateProfileRequest $request, $id) { 
        $id = decryptParam($id);
        $inputs = $request->all();
        //$helpCentre_list = $this->helpcentre_repo->getHelpCentreRecord($id)->toArray();
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['id'] = $id;
        $this->notification_repo->update($inputs, $id);
        return redirect(route('notification.index'))->with('ok', trans('admin/notification.updated_successfully'));
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
        $this->notification_repo->destroyNotification($inputs, $id);
        return response()->json();
    }

}
