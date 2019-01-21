<?php

/**
 * This controller is used for CMS.
 * @package    CMS
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\CmspageRepository;
use App\Http\Requests\CmsPageCreateRequest;
use App\Models\Cmspage;
use Datatables;
use Illuminate\Http\Request;

/**
 * This controller is used for CMS.
 * @author     Icreon Tech - dev1.
 */
class CmsPagesController extends Controller {

    /**
     * The CmspageRepository instance.
     *
     * @var App\Repositories\CmspageRepository
     */
    protected $cmspageRepository;

    /**
     * Create a new CmsPagesController instance.
     *
     * @param  App\Repositories\CmspageRepository $cmspageRepository
     * @return void
     */
    public function __construct(CmspageRepository $cmspageRepository) {
        $this->cmspageRepository = $cmspageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        return view('admin.cmspage.cmspagelist');
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function listRecord(Request $request) {
        $cmspages = $this->cmspageRepository->getCmspageListData();
        return Datatables::of($cmspages)
                        ->addColumn('action', function ($cmspage) {
                            return '<a href="' . route('cmspage.edit', $cmspage->id) . '" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i> Edit</a>
                                    <a href="javascript:void(0);" data-id="' . $cmspage->id . '" class="delete_row btn default btn-xs red"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
                        })
                        ->editColumn('status', function ($cmspage) {
                            if ($cmspage->status == 1) {
                                return "Active";
                            }
                            if ($cmspage->status == 0) {
                                return "In Active";
                            }
                            if ($cmspage->status == 2) {
                                return "Deleted";
                            }
                        })
                        ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        $data['page_title'] = trans('admin/cmspage.add_template');
        $data['trait'] = array('trait_1' => trans('admin/cmspage.template_heading'), 'trait_1_link' => route('cmspage.index'), 'trait_2' => trans('admin/cmspage.add_template'));
        $data['JsValidator'] = 'App\Http\Requests\CmsPageCreateRequest';
        return view('admin.cmspage.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(CmsPageCreateRequest $request) {
        $inputs = $request->all();
        $inputs['created_by'] = session()->get('user')['id'];
        $inputs['updated_by'] = session()->get('user')['id'];
        $this->cmspageRepository->store($inputs);
        return redirect(route('cmspage.index'))->with('ok', trans('admin/cmspage.created'));
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
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $id = $id;
        $cmsPage = Cmspage::findOrFail($id)->toArray();
        $data['cmsPage'] = $cmsPage;
        $data['page_title'] = trans('admin/cmspage.edit_template');
        $data['trait'] = array('trait_1' => trans('admin/cmspage.template_heading'), 'trait_1_link' => route('cmspage.index'), 'trait_2' => trans('admin/cmspage.edit_template'));
        $data['JsValidator'] = 'App\Http\Requests\CmsPageCreateRequest';
        return view('admin.cmspage.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(CmsPageCreateRequest $request, $id) {
        $inputs = $request->all();
        $inputs['created_by'] = session()->get('user')['id'];
        $inputs['updated_by'] = session()->get('user')['id'];
        $this->cmspageRepository->update($inputs, $id);
        return redirect(route('cmspage.index'))->with('ok', trans('admin/cmspage.updated'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
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
        $this->cmspageRepository->updateStatus($inputs, $id);
        return response()->json();
    }

}
