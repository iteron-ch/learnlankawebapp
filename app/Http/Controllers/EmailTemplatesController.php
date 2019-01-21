<?php

namespace App\Http\Controllers;

use App\Repositories\EmailTemplateRepository;
use App\Http\Requests\EmailTemplateCreateRequest;
use App\Models\Emailtemplate;
use Datatables;
use Illuminate\Http\Request;

class EmailTemplatesController extends Controller {

    /**
     * The EmailTemplate instance.
     *
     * @var App\Repositories\EmailTemplate
     */
    protected $emailTemplateRepository;

    /**
     * Create a new EmailTemplate instance.
     *
     * @param  App\Repositories\RoleRepository $role_gestion
     * @return void
     */
    public function __construct(EmailTemplateRepository $emailTemplateRepository) {
        $this->emailTemplateRepository = $emailTemplateRepository;
    }

    /**
     * Display a listing of the templates.
     *
     * @return Response
     */
    public function index() {
        return view('admin.emailtemplate.emailtemplatelist');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function listRecord(Request $request) {
        $templates = $this->emailTemplateRepository->getEmailTemplateListData();
        return Datatables::of($templates)
                        ->addColumn('action', function ($template) {
                            return '<a href="' . route('emailtemplate.edit', $template->id) . '" ><i class="glyphicon glyphicon-edit"></i></a>';
                                    //<a href="javascript:void(0);" data-id="' . $template->id . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $data['status'] = statusArray();
        $data['page_heading'] = trans('admin/emailtemplate.manage_email_template');
        $data['page_title'] = trans('admin/emailtemplate.add_template');
        $data['trait'] = array('trait_1' => trans('admin/emailtemplate.template_heading'), 'trait_1_link' => route('emailtemplate.index'), 'trait_2' => trans('admin/emailtemplate.add_template'));
        $data['JsValidator'] = 'App\Http\Requests\EmailTemplateCreateRequest';
        return view('admin.emailtemplate.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(EmailTemplateCreateRequest $request) {
        $inputs = $request->all();
        $inputs['created_by'] = session()->get('user')['id'];
        $inputs['updated_by'] = session()->get('user')['id'];
        $this->emailTemplateRepository->store($inputs);
        return redirect(route('emailtemplate.index'))->with('ok', trans('admin/emailtemplate.created'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  App\Repositories\UserRepository $user_gestion
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $id = $id;
        $emailTemplate = Emailtemplate::findOrFail($id)->toArray();
        $data['status'] = statusArray();
        $data['emailTemplate'] = $emailTemplate;
        $data['page_heading'] = trans('admin/emailtemplate.manage_email_template');
        $data['page_title'] = trans('admin/emailtemplate.edit_template');
        $data['trait'] = array('trait_1' => trans('admin/emailtemplate.template_heading'), 'trait_1_link' => route('emailtemplate.index'), 'trait_2' => trans('admin/emailtemplate.edit_template'));
        $data['JsValidator'] = 'App\Http\Requests\EmailTemplateCreateRequest';
        return view('admin.emailtemplate.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\Http\Requests\PostUpdateRequest $request
     * @param  int  $id
     * @return Response
     */
    public function update(EmailTemplateCreateRequest $request, $id) {
        $inputs = $request->all();
        $inputs['created_by'] = session()->get('user')['id'];
        $inputs['updated_by'] = session()->get('user')['id'];
        $this->emailTemplateRepository->update($inputs, $id);
        return redirect(route('emailtemplate.index'))->with('ok', trans('admin/emailtemplate.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy() {
        die('here'); //
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
        $this->emailTemplateRepository->updateStatus($inputs, $id);
        return response()->json();
    }

}
