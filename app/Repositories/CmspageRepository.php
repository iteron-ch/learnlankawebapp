<?php

namespace App\Repositories;

use App\Models\Cmspage;
use Carbon\Carbon;

class CmspageRepository extends BaseRepository {

    /**
     * The School instance.
     *
     * @var App\Models\Cmspage
     */
    protected $model;

    /**
     * Create a new Cmspage instance.
     *
     * @param  App\Models\Cmspage $cmspage
     * @return void
     */
    public function __construct(Cmspage $cmspage) {
        $this->model = $cmspage;
    }

    /**
     * Get Cmspages collection.
     *
     * @return Response
     */
    public function getCmspageListData() {
        return $this->model->select(['cmspages.id', 'cmspages.title', 'cmspages.sub_title', 'cmspages.description', 'cmspages.meta_title', 'cmspages.meta_keywords', 'cmspages.meta_description', 'cmspages.status'])->where('status', '!=', DELETED);
        ;
    }

    /**
     * Save the Email Template.
     *
     * @param  App\Models\Cmspage
     * @param  Array  $inputs
     * @return void
     */
    private function save($cmspage, $inputs) {
        $cmspage->title = $inputs['title'];
        $cmspage->sub_title = $inputs['sub_title'];
        $cmspage->description = $inputs['description'];
        $cmspage->meta_title = $inputs['meta_title'];
        $cmspage->meta_keywords = $inputs['meta_keywords'];
        $cmspage->meta_description = $inputs['meta_description'];
        $cmspage->status = $inputs['status'];
        $dateTime = Carbon::now()->toDateTimeString();
        $cmspage->created_at = $dateTime;
        $cmspage->updated_at = $dateTime;
        $cmspage->created_by = $inputs['created_by'];
        $cmspage->updated_by = $inputs['updated_by'];
        $cmspage->save();
    }

    /**
     * Create a CMS page.
     *
     * @param  array  $inputs
     * @param  int    $confirmation_code
     * @return App\Models\Emailtemplate 
     */
    public function store($inputs) {
        $cmspage = new $this->model;
        $this->save($cmspage, $inputs);
        return $cmspage;
    }

    /**
     * Update a CMS page.
     *
     * @param  array  $inputs
     * @param  App\Models\Cmspage $cmspage
     * @return void
     */
    public function update($inputs, $id) {
        $cmsPage = $this->getById($id);
        $cmsPage = $this->save($cmsPage, $inputs);
    }

    /**
     * Get post collection.
     *
     * @param  int  $id
     * @return array
     */
    public function edit($id) {
        $cmsPage = $this->model->findOrFail($id);
        return compact('cmsPage');
    }

    /**
     * Update a CMS page status.
     *
     * @param  array  $inputs
     * @param  App\Models\Cmspage
     * @return void
     */
    public function updateStatus($inputs, $id) {
        $cmspage = $this->model->where('id', '=', $id)->first();
        $cmspage->status = $inputs['status'];
        $cmspage->updated_by = $inputs['updated_by'];
        $cmspage->save();
    }

}
