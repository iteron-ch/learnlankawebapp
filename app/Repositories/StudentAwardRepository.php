<?php

/**
 * This controller is used for student awards.
 * @package    Student awards
 * @author     Icreon Tech.
 */

namespace App\Repositories;

use App\Models\Studentaward;
use App\Models\Studentearnrewards;
use Carbon\Carbon;
use Image;
use DB;

class StudentAwardRepository extends BaseRepository {

    /**
     * The studentaward instance.
     *
     * @var App\Models\studentaward
     */
    protected $model;
    protected $studentearnrewards;

    /**
     * Create a new studentaward instance.
     *
     * @param  App\Models\studentaward $studentaward
     * @return void
     */
    public function __construct(Studentaward $model, Studentearnrewards $studentearnrewards) {
        $this->model = $model;
        $this->studentearnrewards = $studentearnrewards;
    }

    /**
     * Get Studendt award  collection.
     *
     * @return Response
     */
    public function getSchoolawardListData() {
        return $this->model->select(['studentawards.id', 'studentawards.title', 'studentawards.status', 'studentawards.updated_at', 'studentawards.image'])->where('status', '!=', DELETED)
                        ->where('studentawards.status', '!=', DELETED);
    }

    /**
     * Save Student award
     *
     * @param  App\Models\Studentaward
     * @param  Array  $inputs
     * @return void
     */
    private function save($studentaward, $inputs) {
        $studentaward->title = $inputs['title'];
        $studentaward->image = $inputs['image'];
        $studentaward->content = $inputs['content'];
        $studentaward->status = $inputs['status'];
        $studentaward->position = serialize($inputs['position']);
        if (isset($inputs['id'])) {
            $studentaward->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '0';
        } else {
            $studentaward->created_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
            $studentaward->updated_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
        }
        $studentaward->save();
        return $studentaward->id;
    }

    /**
     * Create a Student award.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     * @return App\Models\Emailtemplate 
     */
    public function store($inputs) {
        $studentaward = new $this->model;
        return $this->save($studentaward, $inputs);
    }

    /**
     * Create a Student award.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     * @return App\Models\Emailtemplate 
     */
    public function getStudentAward($id) {
        return $this->model->findOrFail($id, ['id', 'title', 'image', 'content', 'position', 'status'])->toArray();
    }

    /**
     * Update a Student award.
     *
     * @param  array  $inputs
     * @param  App\Models\Studentaward $studentaward
     * @return void
     */
    public function update($inputs, $id) {
        $studentaward = $this->getById($id);
        return $this->save($studentaward, $inputs);
    }

    /**
     * Get post collection.
     *
     * @param  int  $id
     * @return array
     */
    public function edit($id) {
        return $this->model->findOrFail($id);
    }

    /**
     * Get post collection.
     *
     * @param  int  $id
     * @return array
     */
    public function studentAwardData($id = NULL) {
        $studentAwardJson = array(
            'status' => statusArray(),
            'awardFontStyle' => awardFontStyle()
        );
        return json_encode($studentAwardJson);
    }

    /**
     * Update a CMS page status.
     *
     * @param  array  $inputs
     * @param  App\Models\Cmspage
     * @return void
     */
    public function updateStatus($inputs, $id) {
        $id = decryptParam($id);
        $studentaward = $this->model->where('id', '=', $id)->first();
        $studentaward->status = $inputs['status'];
        $studentaward->updated_by = $inputs['updated_by'];
        $studentaward->save();
    }

    /**
     * upload student Award image
     * @object type $image 
     */
    function studenAwardImageUpload($image) {
        $fileName = str_random(10) . '_' . time() . '.' . $image->getClientOriginalExtension();
        $pathLarge = public_path('uploads/studentawards/large/' . $fileName);
        $pathMedium = public_path('uploads/studentawards/medium/' . $fileName);
        $pathSmall = public_path('uploads/studentawards/small/' . $fileName);
        Image::make($image->getRealPath())->resize(UIMG_L_W, UIMG_L_H)->save($pathLarge);
        Image::make($image->getRealPath())->resize(UIMG_M_W, UIMG_M_H)->save($pathMedium);
        Image::make($image->getRealPath())->resize(UIMG_S_W, UIMG_S_H)->save($pathSmall);
        return $fileName;
    }

    /**
     * delete user image
     * @string type $image 
     */
    function studenAwardDeleteImage($fileName) {
        $pathLarge = public_path('uploads/studentawards/large/' . $fileName);
        $pathMedium = public_path('uploads/studentawards/medium/' . $fileName);
        $pathSmall = public_path('uploads/studentawards/small/' . $fileName);
        if (file_exists($pathLarge)) {
            unlink($pathLarge);
        }
        if (file_exists($pathMedium)) {
            unlink($pathMedium);
        }
        if (file_exists($pathSmall)) {
            unlink($pathSmall);
        }
    }

    /**
     * upload question image
     * @object type $image 
     */
    function questionImageUpload($image, $dimension) {
        list($width, $height) = explode(",", $dimension);
        $fileName = str_random(10) . '_' . time() . '.' . $image->getClientOriginalExtension(); // renameing image
        $path = public_path('uploads/studentawards/' . $fileName);
        Image::make($image->getRealPath())->resize($width, $height)->save($path);
        return $fileName;
    }

    /**
     * delete question image
     * @object type $image 
     */
    function questionDeleteFile($fileName) {
        $path = public_path('uploads/studentawards/' . $fileName);
        if (file_exists($path)) {
            unlink($path);
        }
    }

    public function rewardToStudent($params) {
        $awards = $this->getStudentAssignedAward(array(
            'task_type' => $params['task_type'],
            'subject' => $params['subject'],
            'question_set' => isset($params['question_set']) ? $params['question_set'] : '',
            'strand' => isset($params['strand']) ? $params['strand'] : '',
            'substrand' => isset($params['substrand']) ? $params['substrand'] : '',
            'student_id' => $params['student_id'],
            'tutor_id' => $params['tutor_id'],
            'student_percent' => $params['student_percent']
        ));
        if (count($awards)) {
            foreach ($awards as $award) {
                $filename = $this->createStudentRewardFile($award, array(
                    'name' => $params['name'],
                    'date' => outputDateFormat($params['date']),
                    'signature' => $params['signature'],
                ));
                $studentAwardData[] = array(
                    'student_id' => $params['student_id'],
                    'task_type' => $params['task_type'],
                    'subject' => $params['subject'],
                    'title' => $award['title'],
                    'description' => $award['content'],
                    'filename' => $filename,
                    'created_at' => $params['date']
                );
            }
            //inset student reward into student_earn_rewards
            DB::table('student_earn_rewards')->insert($studentAwardData);
        }
    }
    public function createStudentRewardFile($studentaward, $insertData) {
        if (file_exists(public_path('uploads/studentawards/' . $studentaward['image']))) {
            $image = Image::make(public_path('uploads/studentawards/' . $studentaward['image']));
            $positions = unserialize($studentaward['position']);
            $namePosition = $positions[0];
            if ($namePosition['visible']) {
                $image->text($insertData['name'], $namePosition['xPos'], $namePosition['yPos'], function($font) use($namePosition) {
                    $fontPath = public_path('css/fonts/' . $namePosition['font'] . '.ttf');
                    if (file_exists($fontPath)) {
                        $font->file($fontPath);
                    }
                    $font->size($namePosition['size']);
                    $font->align('left');
                    $font->valign('top');
                });
            }

            $datePosition = $positions[3];
            if ($datePosition['visible']) {
                $image->text($insertData['date'], $datePosition['xPos'], $datePosition['yPos'], function($font) use($datePosition) {
                    $fontPath = public_path('css/fonts/' . $datePosition['font'] . '.ttf');
                    if (file_exists($fontPath)) {
                        $font->file($fontPath);
                    }
                    $font->size($datePosition['size']);
                    $font->align('left');
                    $font->valign('top');
                });
            }
            $signaturePosition = $positions[5];
            if ($signaturePosition['visible']) {
                $image->insert($insertData['signature'], 'top-left', $signaturePosition['xPos'], $signaturePosition['yPos']);
            }
            $filename = str_random(10) . '_' . time() . '.' . $image->extension;
            //make award file
            $destinationPath = public_path('uploads/myawards/' . $filename);
            $image->save($destinationPath);

            //make award file thumbnail
            $imgThumb = Image::make($destinationPath);
            $imgThumb->resize(STUDENT_REWARD_THUMBNAIL_WIDTH, null, function ($constraint) {
                $constraint->aspectRatio();
            });
            $imgThumb->save(public_path('uploads/myawards/thumbnail/' . $filename));
            return $filename;
        }
    }

    public function getStudentAssignedAward($params) {
        $query = $this->model->select(['studentawards.id', 'studentawards.title', 'studentawards.image', 'studentawards.content', 'studentawards.position', 'rewards.*'])
                ->join('rewards', 'studentawards.id', '=', 'rewards.studentawards_id')
                ->where(['studentawards.status' => ACTIVE, 'rewards.status' => ACTIVE, 'rewards.subject' => $params['subject']])
                ->where('rewards.percent_min', '<=', $params['student_percent'])
                ->where('rewards.percent_max', '>=', $params['student_percent']);
        if ($params['task_type'] == TEST) {
            $query->where(['rewards.task_type' => TEST, 'rewards.question_set' => $params['question_set']]);
        } elseif ($params['task_type'] == REVISION) {
            $query->where(['rewards.task_type' => REVISION, 'rewards.strand' => $params['strand']])
                    ->whereRaw(DB::raw("(rewards.substrand = " . $params['substrand'] . " OR rewards.substrand = '' )"));
        }
        if (!empty($params['tutor_id'])) {
            $query->whereRaw(DB::raw("(rewards.created_by = " . $params['tutor_id'] . " OR rewards.user_type = " . ADMIN . ") "));
        } else {
            $query->whereRaw(DB::raw("(`rewards`.`id` IN (SELECT rewards_id FROM reward_students where student_id = " . $params['student_id'] . ") OR rewards.user_type = " . ADMIN . " )"));
        }
        return $query->get()->toArray();
    }

    public function getStudentEarnRewards($params) {
        return $this->studentearnrewards
                        ->select('*')
                        ->where(['task_type' => $params['task_type'], 'student_id' => $params['student_id'], 'subject' => $params['subject']])
                        ->get()->toArray();
    }

}
