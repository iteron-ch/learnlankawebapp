<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model {

    protected $table = "notifications";

    protected function getUsers($id) {
        return HelpCentre::where('status', '!=', DELETED)
                        ->where('id', '=', $id)
                        ->orderBy('visible_to')->lists('visible_to', 'id')->all();
    }

    protected function getGroups($params) {
        return User::where('status', '!=', DELETED)
                        ->where("user_type", "=", $params['user_type'])->orderBy('username')->lists('username', 'id')->all();
    }

}
