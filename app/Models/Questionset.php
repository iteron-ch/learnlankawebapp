<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questionset extends Model {

    protected $table = 'questionsets';
    protected $fillable = ['id', 'ks_id', 'group_id', 'set_name', 'subject', 'set_group', 'year_group', 'ispublished', 'set_status', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by'];

    protected function getQuestionsetList($published = FALSE) {
        $query = $this
                ->select(['id', 'set_name', 'ks_id', 'set_group', 'year_group', 'subject'])
                ->where('status','!=',DELETED);
        if ($published) {
            $query->where('status', 'Published');
        }

        return $query->orderBy('set_name')
                ->get()->toArray();
    }

}
