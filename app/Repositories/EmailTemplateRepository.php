<?php

namespace App\Repositories;

use App\Models\Emailtemplate;
use Carbon\Carbon;

class EmailTemplateRepository extends BaseRepository {

    /**
     * The School instance.
     *
     * @var App\Models\EmailTemplate
     */
    protected $model;

    /**
     * Create a new EmailRepository instance.
     *
     * @param  App\Models\Emailtemplate $emailtemplate
     * @return void
     */
    public function __construct(Emailtemplate $emailtemplate) {
        $this->model = $emailtemplate;
    }

    /**
     * Save the Email Template.
     *
     * @param  App\Models\Emailtemplate
     * @param  Array  $inputs
     * @return void
     */
    private function save($emailtemplate, $inputs) {
        $emailtemplate->title = $inputs['title'];
        $emailtemplate->subject = $inputs['subject'];
        $emailtemplate->message = $inputs['message'];
        $emailtemplate->created_by = $inputs['created_by'];
        $emailtemplate->updated_by = $inputs['updated_by'];
        $emailtemplate->status = $inputs['status'];
        $dateTime = Carbon::now()->toDateTimeString();
        $emailtemplate->created_at = $dateTime;
        $emailtemplate->updated_at = $dateTime;
        $emailtemplate->save();
    }

    /**
     * Get Emailtemplates collection.
     *
     * @return Response
     */
    public function getEmailTemplateListData() {
        return $this->model->select(['emailtemplates.id', 'emailtemplates.title', 'emailtemplates.subject', 'emailtemplates.message', 'emailtemplates.status','emailtemplates.updated_at'])->where('status', '!=', DELETED);
    }

    /**
     * Create a Email Template.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     * @return App\Models\Emailtemplate 
     */
    public function store($inputs) {
        $emailtemplate = new $this->model;
        $this->save($emailtemplate, $inputs);

        return $emailtemplate;
    }

    /**
     * Update a Emailtemplate.
     *
     * @param  array  $inputs
     * @param  App\Models\Emailtemplate $emailtemplate
     * @return void
     */
    public function update($inputs, $id) {
        $emailTemplate = $this->getById($id);
        $emailTemplate = $this->save($emailTemplate, $inputs);
    }

    /**
     * Update a user status.
     *
     * @param  array  $inputs
     * @param  App\Models\User $user
     * @return void
     */
    public function updateStatus($inputs, $id) {
        $template = $this->model->where('id', '=', $id)->first();
        $template->status = $inputs['status'];
        $template->updated_by = $inputs['updated_by'];
        $template->save();
    }

}
