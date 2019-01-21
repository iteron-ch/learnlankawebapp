<?php

namespace App\Http\Controllers;

use App\Repositories\StudentAwardRepository;
use App\Http\Requests\StudentAwardCreateRequest;
use Datatables;
use Illuminate\Http\Request;
use Image;

class StudentAwardsController extends Controller {

    /**
     * The Studentaward instance.
     *
     * @var App\Repositories\Studentaward
     */
    protected $studentAwardRepo;

    /**
     * Create a new Studentaward instance.
     *
     * @param  App\Repositories\Studentaward 
     * @return void
     */
    public function __construct(StudentAwardRepository $studentAwardRepo) {
        $this->studentAwardRepo = $studentAwardRepo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return view('admin.studentaward.studentawardlist');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function listRecord(Request $request) {
        $studentawards = $this->studentAwardRepo->getSchoolawardListData();
        return Datatables::of($studentawards)
                        ->addColumn('action', function ($studentaward) {
                            return '<a href="javascript:void(0);" data-remote="' . route('studentaward.show', encryptParam($studentaward->id)) . '" class="view_row"><i class="glyphicon glyphicon-eye-open co"></i></a>
                                    <a href="' . route('studentaward.edit', encryptParam($studentaward->id)) . '"><i class="glyphicon glyphicon-edit"></i></a>
                                    <a href="javascript:void(0);" data-id="' . encryptParam($studentaward->id) . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                        })
                        ->make(true);
    }

    public function show($id) {
        $id = decryptParam($id);
        $studentaward = $this->studentAwardRepo->getStudentAward($id);
        $image = Image::make(public_path('uploads/studentawards/' . $studentaward['image']));
        $positions = unserialize($studentaward['position']);
        $namePosition = $positions[0];
        if ($namePosition['visible']) {
            $image->text($namePosition['label'], $namePosition['xPos'], $namePosition['yPos'], function($font) use($namePosition) {
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
            $image->text($datePosition['label'], $datePosition['xPos'], $datePosition['yPos'], function($font) use($datePosition) {
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
            $image->insert(public_path('images/signature.png'), 'top-left', $signaturePosition['xPos'], $signaturePosition['yPos']);
        }
        return $image->response();
        $newFileName = str_random(10) . '_' . time() . '.' . $image->extension;
        $destinationPath = public_path('uploads/myawards/' . $newFileName);
        $image->save($destinationPath);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $data['studentaward'] = '{}';
        $data['studentawardData'] = $this->studentAwardRepo->studentAwardData();
        $data['page_title'] = trans('admin/studentaward.add_template');
        $data['page_heading'] = trans('admin/studentaward.template_heading');
        $data['trait'] = array('trait_1' => trans('admin/studentaward.student_awards'), 'trait_1_link' => route('studentaward.index'), 'trait_2' => trans('admin/studentaward.add_template'));
        return view('admin.studentaward.studentawardform', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(StudentAwardCreateRequest $request) {
        $inputs = $request->all();
        if (isset($inputs['id'])) {
            $id = $this->studentAwardRepo->update($inputs, $inputs['id']);
        } else {
            $id = $this->studentAwardRepo->store($inputs);
        }
        return response()->json(array('studentawardReference' => $id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $id = decryptParam($id);
        $studentaward = $this->studentAwardRepo->getStudentAward($id);
        $studentaward['position'] = unserialize($studentaward['position']);
        $data['studentaward'] = json_encode($studentaward);
        $data['studentawardData'] = $this->studentAwardRepo->studentAwardData();
        $data['page_title'] = trans('admin/studentaward.edit_template');
        $data['page_heading'] = trans('admin/studentaward.template_heading');
        $data['trait'] = array('trait_1' => trans('admin/studentaward.student_awards'), 'trait_1_link' => route('studentaward.index'), 'trait_2' => trans('admin/studentaward.edit_template'));
        return view('admin.studentaward.studentawardform', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(StudentAwardCreateRequest $request, $id) {
        $inputs = $request->all();
        $awardDeleteImage = FALSE;
        if (isset($inputs['image']) && empty($inputs['image'])) {
            $awardDeleteImage = TRUE;
        }
        if ($request->file('image')) {
            $awardDeleteImage = TRUE;
            $inputs['image'] = $this->studentAwardRepo->studenAwardImageUpload($request->file('image'));
        }


        $inputs['created_by'] = session()->get('user')['id'];
        $inputs['updated_by'] = session()->get('user')['id'];
        $this->studentAwardRepo->update($inputs, $id, $awardDeleteImage);
        return redirect(route('studentaward.index'))->with('ok', trans('admin/rewards.save_msg'));
    }

    /**
     * Update the specified resource from storage.
     *
     * @param  App\requests\Request $request
     * @return Response
     */
    public function delete(Request $request) {
        $inputs = $request->all();
        $id = $inputs['id'];
        $inputs['updated_by'] = session()->get('user')['id'];
        $inputs['status'] = DELETED;
        $inputs['id'] = $id;
        $this->studentAwardRepo->updateStatus($inputs, $id);
        return response()->json();
    }

    /**

     * Display a listing of the resource.
     *
     * @return Response
     */
    public function myAwards() {
        return view('front.myawards.myawards');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function myAwardsSubject($subject) {
        $data['subject'] = $subject;
        $subjectVar = getSubject($subject);
        $studentId = session()->get('user')['id'];
        //get test award to studnent
        $data['testAwards'] = $this->studentAwardRepo->getStudentEarnRewards(array(
            'task_type' => TEST,
            'student_id' => $studentId,
            'subject' => $subjectVar
        ));
        //end
        //get revision award to studnent
        $data['revisionAwards'] = $this->studentAwardRepo->getStudentEarnRewards(array(
            'task_type' => REVISION,
            'student_id' => $studentId,
            'subject' => $subjectVar
        ));
        //end
        return view('front.myawards.myawards-subject', $data);
    }

    /**
     * upload image
     * @author     Icreon Tech - dev2.
     * @param \App\Http\Controllers\Request $request
     * @return Response
     */
    public function uploadImage(Request $request) {
        $inputs = $request->all();
        if (isset($inputs['selectedImg']) && !empty($inputs['selectedImg'])) {
            $this->studentAwardRepo->questionDeleteFile($inputs['selectedImg']);
        }
        if (isset($inputs['file']) && !empty($inputs['file'])) {
            $filename = $this->studentAwardRepo->questionImageUpload($inputs['file'], $inputs['dimension']);
            return response()->json(array('filename' => $filename));
        }
    }
    
    public function printawards($filename){
        $data['filename'] = $filename;
        return view('front.myawards.print-award', $data);
    }

}
