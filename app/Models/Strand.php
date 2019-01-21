<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Strand extends Model {

    protected $table = 'strands';
    protected $fillable = ['subject', 'strand', 'alias_sub_strand', 'sub_strand', 'reference_code', 'appendices', 'strant_status', 'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by'];

    protected function getStrandAllList() {
        return $this->select(['id', 'strand', 'parent_id', 'subject', 'reference_code','alias_sub_strand'])
                        ->where('status','!=', DELETED)
                        ->orderBy('strand')
                        ->get()->toArray();
    }
    protected function getStrandList() {
        return $this->select(['id', 'strand', 'parent_id', 'subject', 'reference_code','alias_sub_strand'])
                        ->where('status','=', ACTIVE)
                        ->orderBy('strand')
                        ->get()->toArray();
    }

}
