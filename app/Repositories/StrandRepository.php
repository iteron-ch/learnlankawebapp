<?php

/**
 * This is used for user save/edit for users data.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Repositories;

use App\Models\Strand;
use Carbon\Carbon;
use DB;

/**
 * This is used for user save/edit for users data.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */
class StrandRepository extends BaseRepository {


    /**
     * Create a new UserRepository instance.
     *
     * @param  App\Models\User $user
     * @param  App\Models\Student $student
     * @return void
     */
    public function __construct(Strand $strand) {
        $this->model = $strand;
    }

    /**
     * Save the strand.
     *
     * @param  App\Models\User $user
     * @param  Array  $inputs
     * @return void
     */
    private function save($strand, $inputs) {
        if (isset($inputs['is_substrand']) && $inputs['is_substrand'] == 1) {
            $strand->parent_id = $inputs['parent_id'];
        } else {
            $strand->parent_id = 0;
        }
        $strand->subject = $inputs['subject'];
        $strand->strand = isset($inputs['strand']) ? $inputs['strand'] : '';
        $strand->alias_sub_strand = isset($inputs['alias_sub_strand']) ? $inputs['alias_sub_strand'] : '';
        $strand->appendices = isset($inputs['appendices']) ? $inputs['appendices'] : '';
        $strand->reference_code = isset($inputs['reference_code']) ? $inputs['reference_code'] : '';
        
        if (isset($inputs['status'])) {
            $strand->status = $inputs['status'];
        }

        if (isset($inputs['id'])) {
            $strand->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '0';
        } else {
            $strand->created_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
            $strand->updated_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
        }
        $strand->save();
    }

    /**
     * Create a user.
     *
     * @param  array  $inputs
     * @return App\Models\User 
     */
    public function store($inputs) {
        $strand = new $this->model;
        $this->save($strand, $inputs);
    }

    /**
     * Update a user.
     *
     * @param  array  $inputs
     * @param  App\Models\User $user
     * @return void
     */
    public function update($inputs, $id) {
        $strand = $this->model->where('id', '=', $id)->first();
        $this->save($strand, $inputs);
    }

    /**
     * delete strand.
     *
     * @param  array  $inputs
     * @param  type int $id
     * @return void
     */
    public function destroyStrand($inputs, $id) {
        $strand = $this->model->where('id', '=', $id)->first();
        $dateTime = Carbon::now()->toDateTimeString();
        $strand->updated_by = $inputs['updated_by'];
        $strand->deleted_at = $dateTime;
        $strand->status = DELETED; // deleted
        $strand->save();
    }

    public function getstrandTree($isArr = TRUE) {
        $strand = Strand::getStrandList();
        $strands = array();
        $substrands = array();
        $strandTree = buildTree($strand);
        if (count($strandTree)) {
            foreach ($strandTree as $row) {
                if ($isArr) {
                    $strands[$row['subject']][] = array('id' => $row['id'], 'name' => $row['strand'], 'code' => $row['reference_code']);
                } else {
                    $strands[$row['subject']][$row['id']] = $row['strand'];
                }
                if (isset($row['children'])) {
                    foreach ($row['children'] as $child) {
                        if ($isArr) {
                            $substrands[$row['id']][] = array('id' => $child['id'], 'name' => $child['strand'], 'code' => $child['reference_code']);
                        } else {
                            $substrands[$row['id']][$child['id']] = $child['strand'];
                        }
                    }
                }
            }
        }
        return array('strands' => $strands, 'substrands' => $substrands);
    }


    /*
      public function getstrandTree() {
      $strand = Strand::getStrandList();
      $strands = array();
      $substrands = array();
      $strandTree = buildTree($strand);
      if(count($strandTree)){
      foreach ($strandTree as $row) {
      $strands[$row['subject']][] = array('id' => $row['id'], 'name' => $row['strand'], 'code' => $row['reference_code']);
      if (isset($row['children'])) {
      foreach ($row['children'] as $child) {
      $substrands[$row['id']][] = array('id' => $child['id'], 'name' => $child['strand'], 'code' => $child['reference_code']);;
      }
      }
      }
      }
      return array('strands' => $strands, 'substrands' => $substrands);
      } */

    /* public function getStrand($params = array()) {

      $query = $this->model->select(['id', 'strand', 'reference_code']);
      $query->where('status', ACTIVE);

      if (isset($params['id']) && !empty($params['id']))
      $query->where('id', '=', $params['id']);

      if (isset($params['subject']) && !empty($params['subject']))
      $query->where('subject', '=', $params['subject']);

      if (isset($params['parent_id']))
      $query->where('parent_id', '=', $params['parent_id']);

      return $query->orderBy('strand')->get()->toArray();
      } 

    public function getSubStrand($params = array()) {
        $query = $this->model->select(['strands.id', 'strands.strand', 'strands.reference_code', 'parentStrand.strand as main_strand', 'strands.subject']);
        $query->where('strands.status', ACTIVE)
                ->join('strands as parentStrand', 'parentStrand.id', '=', 'strands.parent_id');

        if (isset($params['whereCondition']) && !empty($params['whereCondition'])) {
            if ($params['whereCondition'] == 'id')
                $query->where('strands.id', '=', $params['strand_id']);
            if ($params['whereCondition'] == 'parent_id')
                $query->where('strands.parent_id', '=', $params['strand_id']);
        }
        return $query->orderBy('strands.strand')->get()->toArray();
    }
*/
}
