<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Notification;
use DB;

class NotificationRepository extends BaseRepository {

    /**
     * The Notification instance.
     *
     * @var App\Models\Notification
     */
    protected $notification;

    /**
     * Create a new NotificationRepository instance.
     *
     * @param  App\Models\Notification $notification
     * @return void
     */
    public function __construct(Notification $notification) {
        $this->notification = $notification;
    }

    /**
     * 
     * @param type $params
     * @return type
     */
    public function getNotificationList() {
        return $this->notification
                        ->where('status', '!=', DELETED)
                        ->select(['id', 'user_type', 'title', 'description', 'status', 'created_at', 'created_by', 'deleted_at', 'updated_by', 'updated_at']);
    }

    public function getNotificationRecord($id) {
        return $this->notification
                        ->where('status', '!=', DELETED)
                        ->where('id', '=', $id)
                        ->select(['id', 'user_type', 'title', 'description', 'status', 'created_at', 'created_by', 'deleted_at', 'updated_by', 'updated_at'])
                        ->get();
    }
    public function getLatestNotification($params = array())  {
         return $this->notification
                        ->where('status', '!=', DELETED)
                        ->whereRaw(DB::raw("FIND_IN_SET(".$params['user_type'].",user_type)"))
                        ->select(['title', 'description'])
                        ->orderBy('id','decs')
                        ->limit('1')
                        ->get();
    }
    /**
     * Save the Notification record.
     *
     * @param  App\Models\Notification 
     * @param  Array  $inputs
     * @return void
     */
    public function save($inputs, $notification) {
        //asd($inputs);
        $notification->status = ACTIVE;
        $notification->created_by = session()->get('user')['id'];
        $notification->title = $inputs['title'];
        $notification->description = $inputs['description'];
        //asd($data);
        foreach ($inputs['user_type'] as $value) {
            $dataArr = array(
                'user_type' => $value,
            );
            $data[] = $dataArr;
        }
        $selected_ids = Array();
        foreach ($data as $u)
            $selected_ids[] = $u['user_type'];
        $list = implode(",", $selected_ids);
        //asd($list);
        $notification->user_type = $list;
        $notification->save();
    }

    /**
     * Create a Notification Record.
     *
     * @param  array  $inputs
     * @return App\Models\HelpCentre 
     */
    public function store($inputs) {
        //asd($inputs);
        $notification = new $this->notification;
        $this->save($inputs, $notification);
    }

   
    /**
     * Update a Notification.
     *
     * @param  array  $inputs
     * @param  App\Models\Notification 
     * @return void
     */
    public function update($inputs, $id) {
        $notification = $this->notification->where('id', '=', $id)->first();
        $this->save($inputs, $notification);
    }

    /**
     * Get a 2-dimension list of all user types.
     *
     * @param  array  $inputs
     * @param  App\Models\Notification 
     * @return void
     */
    public function visible_array() {
        $owner_type = groupOwnerType();
        $ownerArray = array();
        foreach ($owner_type as $key => $value) {
            $ownerArray['ALL'][$key] = $value;
        }
        return $ownerArray;
    }

    /**
     * Update a Notification status.
     * @param  array  $inputs
     * @param  App\Models\Notification 
     * @return void
     */
     public function destroyNotification($inputs, $id) {
        $notification = $this->notification->where('id', '=', $id)->first();
        $dateTime = Carbon::now()->toDateTimeString();
        $notification->updated_by = $inputs['updated_by'];
        $notification->deleted_at = $dateTime;
        $notification->status = DELETED; // deleted
        $notification->save();
    }

}
