<?php

/**
 * This is used for question save/edit for questions data.
 * @package    Question builder
 * @author     Icreon Tech  - dev2.
 */

namespace App\Repositories;

use App\Models\Question;
use App\Models\Questionset;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Image;
use App\Repositories\StrandRepository;
use DB;

class QuestionbuilderRepository extends BaseRepository {

    /**
     * The Question instance.
     *
     * @var App\Models\Question
     */
    protected $question;

    /**
     * The StrandRepository instance.
     *
     * @var App\Repositories\StrandRepository
     */
    protected $strandRepo;
    protected $userRepo;

    /**
     * Create a new QuestionbuilderRepository instance.
     *
     * @param  App\Models\Question $question
     * @param  App\Models\QuestionDetail $questionDetail
     * @return void
     */
    public function __construct(
    Question $question, StrandRepository $strandRepo, StrandRepository $strandRepo, UserRepository $userRepo) {
        $this->question = $question;
        $this->strandRepo = $strandRepo;
        $this->userRepo = $userRepo;
    }

    /**
     * Save the question.
     *
     * @param  App\Models\Question $question
     * @param  Array  $inputs
     * @return void
     */
    private function saveQuestion($question, $inputs) {
        if (array_key_exists('questions', $inputs) && count($inputs['questions'])) {
            $totalMarks = 0;
            foreach ($inputs['questions'] as $questionRef) {
                $sub_questions_ans[] = $questionRef['correctAns'];
                $totalMarks = $totalMarks + $questionRef['mark'];
                unset($questionRef['correctAns']);
                $sub_questions[] = $questionRef;
            }
        }
        $question->description = $inputs['description'];
        $question->descvisible = $inputs['descvisible'] ? 'True' : 'False';
        $question->key_stage = $inputs['questionparam']['keystage'];
        $question->year_group = $inputs['questionparam']['yeargroup'];
        $question->subject = $inputs['questionparam']['subject'];
        $question->set_group = $inputs['questionparam']['setgroup'];
        $question->question_set_id = $inputs['questionparam']['setname'];
        $question->paper_id = $inputs['questionparam']['paperset'];
        $question->difficulty = $inputs['questionparam']['difficulty'];
        $question->strands_id = $inputs['questionparam']['strand'];
        $question->substrands_id = $inputs['questionparam']['substrand'];
        $question->question_type_id = $inputs['questionparam']['questiontype'];
        $question->question_id_pre = $inputs['questionparam']['populatedID'];
        $question->question_id_mid = $inputs['questionparam']['dynamicId1'];
        $question->question_id_post = $inputs['questionparam']['dynamicId2'];
        if(isset($inputs['questionparam']['dynamicId2']) && !empty($inputs['questionparam']['dynamicId2']))
            $question->question_id = $inputs['questionparam']['populatedID'] .'.'. $inputs['questionparam']['dynamicId1'] .'.'. $inputs['questionparam']['dynamicId2'];
        else
            $question->question_id = $inputs['questionparam']['populatedID'] .'.'. $inputs['questionparam']['dynamicId1'];
        
        $question->sub_questions = isset($sub_questions) ? serialize($sub_questions) : '';
        $question->sub_questions_ans = isset($sub_questions_ans) ? serialize($sub_questions_ans) : '';
        $question->total_marks = $totalMarks;
        $question->total_questions = count($inputs['questions']);
        $question->quesnote = $inputs['questionparam']['quesnote'];

        if (isset($inputs['id'])) {
            $question->updated_by = $inputs['updated_by'];
        } else {
            $question->created_by = $inputs['created_by'];
            $question->updated_by = $inputs['created_by'];
            if (session()->get('user')['user_type'] == ADMIN) {
                $question->status = PUBLISH;
                $question->published_date = Carbon::now()->toDateTimeString();
            }
        }

        $question->save();
        $lastInsertedId = $question->id;
        //save revision for this question set, if question set status is PUBLISHED
        if ($lastInsertedId && $question->set_group == REVISION && $question->status == PUBLISH) {
            $this->addRevisionTask($question);
        }
        return $lastInsertedId;
    }

    /**
     * Create a question.
     *
     * @param  array  $inputs
     */
    public function store($inputs) {
        $question = new $this->question;
        return $this->saveQuestion($question, $inputs);
    }

    /**
     * Update a user.
     *
     * @param  array  $inputs
     * @param  App\Models\User $user
     * @return void
     */
    public function update($inputs, $id) {
        $question = $this->question->where('id', '=', $id)->first();
        $this->saveQuestion($question, $inputs);
    }

    /**
     * Get school detail.
     *
     * @param  integer  $id
     * @return array
     */
    public function getQuestion($id) {
        $question = $this->question->findOrFail($id);
        $questionData = array(
            'id' => $question->id,
            'questionparam' => array(
                'keystage' => (string) $question->key_stage,
                'yeargroup' => (string) $question->year_group,
                'subject' => (string) $question->subject,
                'setgroup' => (string) $question->set_group,
                'setname' => $question->question_set_id,
                'paperset' => (string) $question->paper_id,
                'difficulty' => (string) $question->difficulty,
                'strand' => (int) $question->strands_id,
                'substrand' => (int) $question->substrands_id,
                'questiontype' => (string) $question->question_type_id,
                'populatedID' => (string) $question->question_id_pre,
                'dynamicId1' => (string) $question->question_id_mid,
                'dynamicId2' => (string) $question->question_id_post,
                'quesnote' => (string) $question->quesnote,
            ),
            'description' => $question->description,
            'descvisible' => $question->descvisible,
            'questionValidate' => $this->getQuestionValidateData(array(
                'validate_stage' => $question->validate_stage,
                'validator_user1' => $question->validator_user1,
                'validate_date1' => $question->validate_date1,
                'validate_reason1' => $question->validate_reason1,
                'validator_user2' => $question->validator_user2,
                'validate_date2' => $question->validate_date2,
                'validate_reason2' => $question->validate_reason2
            ))
        );
        $questionDetailRowData = array();
        if (!empty($question->sub_questions) && !empty($question->sub_questions_ans)) {
            $questionDetailRow = unserialize($question->sub_questions);
            $sub_questions_ans = unserialize($question->sub_questions_ans);
           // asd($questionDetailRow);
            
            foreach ($questionDetailRow as $key => $value) {
                $questionDetailRowData[$key] = $value; 
                $questionDetailRowData[$key]['correctAns'] = $sub_questions_ans[$key];
            }
        }
       // asd($questionDetailRowData);
        $questionData['questions'] = $questionDetailRowData;
        return $questionData;
    }

    function getQuestionValidateData($params) {
        $validateReasons = questionValidateReasonList();

        $returnArray = array(
            'validate_stage' => '0',
            'validator_user1' => '',
            'validate_reason1' => '',
            'validate_date1' => '',
            'validator_user2' => '',
            'validate_reason2' => '',
            'validate_date2' => '',
        );
        if ($params['validate_stage'] == '2') {
            $returnArray['validate_stage'] = '2';
            $userids = !empty($params['validator_user1']) && !empty($params['validator_user2']) ? array($params['validator_user1'], $params['validator_user2']) : array();
            //$returnArray['validate_reason1'] = !empty($params['validate_reason1']) ? $validateReasons[$params['validate_reason1']] : '';
            //$returnArray['validate_reason2'] = !empty($params['validate_reason2']) ? $validateReasons[$params['validate_reason2']] : '';
            $returnArray['validate_reason1'] = !empty($params['validate_reason1']) ? $params['validate_reason1'] : '';
            $returnArray['validate_reason2'] = !empty($params['validate_reason2']) ? $params['validate_reason2'] : '';
            $returnArray['validate_date1'] = !empty($params['validate_date1']) ? outputDateFormat($params['validate_date1']) : '';
            $returnArray['validate_date2'] = !empty($params['validate_date2']) ? outputDateFormat($params['validate_date2']) : '';
            $returnArray['validate_user_id'] = $userids;
        } elseif ($params['validate_stage'] == '1') {
            $userids = !empty($params['validator_user1']) ? array($params['validator_user1']) : array();
            //$returnArray['validate_reason1'] = !empty($params['validate_reason1']) ? $validateReasons[$params['validate_reason1']] : '';
            $returnArray['validate_reason1'] = !empty($params['validate_reason1']) ? $params['validate_reason1'] : '';
            $returnArray['validate_date1'] = !empty($params['validate_date1']) ? outputDateFormat($params['validate_date1']) : '';
            $returnArray['validate_user_id'] = $userids;
        }

        if (isset($userids)) {
            $returnArray['validate_stage'] = '1';
            $userResult = User::getQuestionValidatorUsers($userids);

            foreach ($userResult as $key => $val) {
                $questionValidatorUsers[$val->id] = trim($val->first_name . ' ' . $val->last_name);
            }

            $returnArray['validator_user1'] = !empty($params['validator_user1']) ? @$questionValidatorUsers[$params['validator_user1']] : '';
            $returnArray['validator_user2'] = !empty($params['validator_user2']) ? @$questionValidatorUsers[$params['validator_user2']] : '';
        }
        return $returnArray;
    }

    /**
     * Get school detail.
     *
     * @param  integer  $id
     * @return array
     */
    public function getQuestionFromQuestionSet($id) {
        $questionset = Questionset::findOrFail($id)->toArray();
        return array(
            'questionparam' => array(
                'keystage' => (string) $questionset['ks_id'],
                'yeargroup' => (string) $questionset['year_group'],
                'subject' => (string) $questionset['subject'],
                'setgroup' => (string) $questionset['set_group'],
                'setname' => $questionset['id']
            )
        );
    }

    public function getQuestiontList12($params) {
        $result = $this->question
                        ->select(['questions.id','sub_questions','sub_questions_ans'
                        ])->whereIn('questions.id', $params['ids'])->get()->toArray();

            
        foreach($result as $key1 => $row){
            $ques = unserialize($row['sub_questions']);
            $correctAnsTempOuter = array();
            foreach ($ques as $key => $question){ 
                $question['correctmark'] = array(0=>array('marks' => 1, 'val' => 1));
                $correctAnsTempOuter[$key] = $question;
            }
            $question = $this->question->where('id', '=', $row['id'])->first();
            $question->sub_questions = serialize($correctAnsTempOuter);
            $question->save(); 
            $ids[] = $question->id;
        }
        asd($ids);
        
    }
    
    public function getQuestiontList($params) {
        $query = $this->question
                        ->select(['questions.id', 'questions.key_stage', 'questions.year_group',
                            'questions.subject', 'questions.set_group', 'questions.paper_id',
                            'questions.difficulty', 'questionsets.status', 'question_id', 'questions.question_set_id', 'questionsets.set_name AS set_name',
                            'questions.question_type_id AS question_type', 'strands.strand as strands', 'strands.reference_code as strand_reference_code',
                            'substrands.strand as substrands', 'questions.updated_at', 'questions.status', 'questions.created_at', 'user.first_name', 'user.last_name', 'substrands.reference_code as sub_strand_reference_code', 'questions.created_by', 'questions.published_date', 'questions.validate_date1', 'questions.validate_date2', 'questions.validator_user1', 'questions.validator_user2', 'questions.validate_stage', 'validator.first_name as validator_first_name', 'validator.last_name as validator_last_name',
                            'questions.validate_reason1', 'questions.validate_reason2'
                        ])
                        ->join('questionsets', 'questions.question_set_id', '=', 'questionsets.id', 'left')
                        ->join('strands AS strands', 'questions.strands_id', '=', 'strands.id')
                        ->join('strands AS substrands', 'questions.substrands_id', '=', 'substrands.id')
                        ->join('users AS validator', 'validator.id', '=', 'questions.validator_user1', 'left')
                        ->join('users AS user', 'user.id', '=', 'questions.created_by', 'left')->where('questions.status', '!=', DELETED);


        if (isset($params['loggedin_user']) && !empty($params['loggedin_user'])) {
            $query->where('questions.created_by', '=', $params['loggedin_user']);
        }
        if (isset($params['validator_user']) && !empty($params['validator_user'])) {

            $query->where(function ($query) use ($params) {

                /*$query->where('questions.validator_user1', '=', $params['validator_user'])
                        ->orwhere('questions.validator_user2', '=', $params['validator_user'])
                        ->orwhere('questions.validator_user1', '=', '0')
                        ->orwhere('questions.validator_user2', '=', '0');
                 * 
                 */

                $query->where('questions.validator_user1', '=', '0')
                        ->orwhere('questions.validator_user2', '=', '0');
            });
            $query->where('questions.validator_user1', '!=', $params['validator_user']);
            $query->where('questions.validator_user2', '!=', $params['validator_user']);
            $query->where('questions.validate_stage', '!=', '2');
            $query->where('questions.status', '!=', 'Published');
            //$query->where('questions.status', '!=', 'Unpublished');
        }

        return $query;
    }

    public function getQuestionBuilderData() {
        $questionSets = Questionset::getQuestionsetList();
        $arrStrands = $this->strandRepo->getstrandTree();
        $questionBuilderData = array(
            'subject' => questionSetSubjects(),
            'keystage' => questionKeyStage(),
            'yeargroup' => questionYearGroup(),
            'setgroup' => questionSetGroups(),
            'paper' => subjectPapers(),
            'difficulty' => questionDifficulty(),
            'questionSet' => $questionSets,
            'strand' => $arrStrands['strands'],
            'substrands' => $arrStrands['substrands'],
            'questionType' => questionType(),
            'validateReason' => questionValidateReason(),
        );
        return $questionBuilderData;
    }

    /**
     * upload question image
     * @object type $image 
     */
    function questionImageUpload($image, $dimension = NULL) {
        $fileNameTimeStamp = str_random(10) . '_' . time();
        $clientFilename = $image->getClientOriginalName();
        $fileName = $fileNameTimeStamp . '.' . $image->getClientOriginalExtension(); // renameing image 
        $path = public_path('uploads/questionbuilder/' . $fileName);
        $imageObj = Image::make($image->getRealPath());
        //save original image
        $imageObj->save(public_path('uploads/questionbuilder/original/' . $fileNameTimeStamp.'_'.$clientFilename));
        
        $oWidth = $imageObj->width();
        $oHeight = $imageObj->height();
        
        if (!empty($dimension)) {//save image after resizing
            list($width, $height) = explode(",", $dimension);
            if ($oWidth == $oHeight) {
                $imageObj->resize($width, $height)->save($path);
            } else if ($oWidth > $oHeight) {
                $imageObj->resize($width, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);
            } else if ($oWidth < $oHeight) {
                $imageObj->resize($height, null, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($path);
            }
        }else{//save image without resizing
             $imageObj->save($path);
        }
        return $fileName;
    }

    /**
     * delete question image
     * @object type $image 
     */
    function questionDeleteFile($fileName) {
        $path = public_path('uploads/questionbuilder/original/' . $fileName);
        $pathOriginal = public_path('uploads/questionbuilder/' . $fileName);
        if (file_exists($path)) {
            unlink($path);
        }
        if (file_exists($pathOriginal)) {
            unlink($pathOriginal);
        }
    }

    /**
     * upload question image
     * @object type $image 
     */
    function questionAudioUpload($file) {
        /* $fileParam = array(
          'fileType' => 'image',
          'extension' => $file->getClientOriginalExtension(),
          'size' => $file->getClientSize()
          );
          $validate = validateFile($fileParam);
          if(!$validate){
          return $validate;
          } */
        $fileName = str_random(10) . '_' . time() . '.' . $file->getClientOriginalExtension(); // renameing image
        $path = public_path('uploads/questionbuilder/');
        $file->move($path, $fileName);
        return $fileName;
    }

    function getAssignedQuestion($params = array()) {
        $query = $this->question->select(['questions.description', 'question_details.question as question'])
                ->join('question_details', 'question_details.questions_id', '=', 'questions.id')
                ->where('questions.strands_id', '=', $params['strands_id'])
                ->where('questions.substrands_id', '=', $params['substrands_id'])
                ->where('questions.subject', '=', $params['subject'])
                ->where('questions.status', 'Published')
                ->where('questions.set_group', REVISION);
        return $query->get()->toArray();
    }

    /**
     * Update a user status.
     *
     * @param  array  $inputs
     * @return void
     */
    public function updateQuestionStatus($inputs, $id) {
        $question = $this->question->where('id', '=', $id)->first();
        $dateTime = Carbon::now()->toDateTimeString();
        $question->updated_by = $inputs['updated_by'];
        //$question->deleted_at = $dateTime;
        $question->status = $inputs['status'];
        if ($inputs['status'] == PUBLISH)
            $question->published_date = $dateTime;
        if ($inputs['status'] == REJECT) {
            $question->validate_stage = '0';
            $question->validator_user1 = '0';
            $question->validate_date1 = '0';
            $question->validate_reason1 = '0';
            $question->validator_user2 = '0';
            $question->validate_date2 = '0';
            $question->validate_reason2 = '0';
        }
        $question->save();
        //save revision for this question set, if question set status is PUBLISHED
        if ($question->id && $question->set_group == REVISION && $question->status == PUBLISH) {
            $this->addRevisionTask($question);
        }
    }

    public function validateQuestion($inputs, $id) { 
        $dateTime = Carbon::now()->toDateTimeString();
        $question = $this->question->where('id', '=', $id)->first();
       // if($inputs['updated_by'] == 13 && in_array('6', $inputs['reason'])) // Meena = 13 in database, by Default 
       // {
       //     $question->status = 'Published';
       //     $question->published_date = Carbon::now()->toDateTimeString();
       // }        

        if ($question->validate_stage == '0') {
            $question->validate_stage = '1';
            $question->validator_user1 = $inputs['updated_by'];
            $question->validate_date1 = $dateTime;
            $question->validate_reason1 = implode(',', $inputs['reason']);
            
            $question->save();
            DB::update('update validator_question_count set question_validated=question_validated+1 where user_id=' . $inputs['updated_by']);
            
        } elseif ($question->validate_stage == '1') {
            $question->validate_stage = '2';
            $question->validator_user2 = $inputs['updated_by'];
            $question->validate_date2 = $dateTime;
            $question->validate_reason2 = implode(',', $inputs['reason']);

            if($question->validator_user1!=$inputs['updated_by']) {
                $question->save();
                DB::update('update validator_question_count set question_validated=question_validated+1 where user_id=' . $inputs['updated_by']);    
            }
        }

        
    }

    /**
     * Update a question status.
     * @param  array  $inputs
     * @return void
     */
    public function destroyQuestion($inputs, $id) {
        $question = $this->question->where('id', '=', $id)->first();
        $dateTime = Carbon::now()->toDateTimeString();
        $question->updated_by = $inputs['updated_by'];
        $question->deleted_at = $dateTime;
        $question->status = DELETED; // deleted
        $question->save();
    }

    /**
     * upload question editor file
     * @object type $image 
     */
    function questionEditorFileUpload($file) {
        $fileNameTimeStamp = str_random(10) . '_' . time();
        $clientFilename = $file->getClientOriginalName();
        $fileName = $fileNameTimeStamp . '.' . $file->getClientOriginalExtension(); // renameing image
        $imageObj = Image::make($file->getRealPath());
        //save original image
        $imageObj->save(public_path('uploads/questionbuilder/original/' . $fileNameTimeStamp.'_'.$clientFilename));
        $imageObj->save(public_path('uploads/questionbuilder/' . $fileName));
        return $fileName;
    }

    /**
     * this is used to get the auto number for next question
     * @author     Icreon Tech - dev1.
     * @param type $params
     * @return type* 
     */
    function getQuestionCount($params = array()) {
        return $this->question->select(DB::raw('count(id) as cnt'))
                        ->where('questions.strands_id', '=', $params['strands_id'])
                        ->where('questions.substrands_id', '=', $params['substrands_id'])
                        ->where('questions.difficulty', '=', $params['difficulty_id'])->get()->toArray();

//        return $this->question->select(DB::raw('count(id) as cnt'))
//                        ->where('questions.strands_id', '=', $params['strands_id'])
//                        ->where('questions.substrands_id', '=', $params['substrands_id'])
//                        ->where('questions.subject', '=', $params['subject'])
//                        ->where('questions.key_stage', '=', $params['ks_id'])
//                        ->where('questions.year_group', '=', $params['yg_id'])
//                        //->where('questions.set_group', '=', $params['group'])
//                        ->where('questions.difficulty', '=', $params['difficulty_id'])
//                        //->where('questions.question_type_id', '=', $params['question_type_id'])
//                        ->where('questions.status', '!=', DELETED)->get()->toArray();
    }

    public function getQuestionStrandsForCreateRevision($params) {

        $query = $this->question
                ->select([
                    'questions.strands_id', 'questions.substrands_id', DB::raw('count(questions.strands_id) AS question_num'), 's1.strand AS strand', 's2.strand AS substrand'
                ])
                ->join('strands AS s1', 'questions.strands_id', '=', 's1.id')
                ->join('strands AS s2', 'questions.substrands_id', '=', 's2.id')
        ->where(['questions.status' => PUBLISH, 'questions.subject' => $params['subject'], 'questions.key_stage' => $params['key_stage'], 'questions.year_group' => $params['year_group']]);
        if (!empty($params['difficulty'])) {
            $query->whereIn('questions.difficulty', $params['difficulty']);
        }
        $query->havingRaw(DB::raw("question_num >= '" . $params['revisionMinQusLimit'] . "'"));
        $query->groupBy('questions.substrands_id');
        return $query->get()->toArray();
    }

    public function addRevisionTask($question) {
        $revisionArr = [
            'task_type' => REVISION,
            'subject' => $question->subject,
            'key_stage' => $question->key_stage,
            'year_group' => $question->year_group,
            'strand' => $question->strands_id,
            'substrand' => $question->substrands_id
        ];
        //get number of question in this particular set of cretaria,
        $revisionQuestionCount = Question::getRevisionQuestionCount($revisionArr);
        if ($revisionQuestionCount['cnt'] >= REVISION_MIN_QUS_LIMIT) {
            Task::addTask($revisionArr);
        }
    }
    public function getAllQuestionData($params) {
        $query = $this->question
                ->select([
                    'questions.difficulty','s1.reference_code AS strand_reference_code','s2.reference_code AS substrand_reference_code',  'questions.question_id_pre','questions.question_id_mid','questions.question_id_post', 's1.strand AS strand', 's2.strand AS substrand'
                    ,'questions.id', 'questions.question_id',
                ])
                ->join('strands AS s1', 'questions.strands_id', '=', 's1.id')
                ->join('strands AS s2', 'questions.substrands_id', '=', 's2.id')
        ->where(['questions.subject' => $params['subject']])
        //->limit('5000')
        //->offSet('0')
        ->orderBy('id','asc');
        return $query->get()->toArray();
    }
    public function updateAllQuestionIDData($newQuestionId,$newQuestionIdPre, $id){
       // echo "update questions set question_id='".$newQuestionId."', question_id_pre='".$newQuestionIdPre."' where id='" . $id."'";
       // echo "<br>";
        DB::update("update questions set question_id='".$newQuestionId."', question_id_pre='".$newQuestionIdPre."' where id='" . $id."'");
    }
}
