<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HelpCentre extends Model {

    protected $table = "helpcentres";

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
