<?php

/**
 * This controller is used for Admin.
 * @package    User
 * @author     Icreon Tech  - dev1.
 */

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Http\Requests\Admin\AdminUpdateProfileRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Datatables;

/**
 * This controller is used for Admin.
 * @author     Icreon Tech - dev1.
 */
class AdminController extends Controller {

    /**
     * The UserRepository instance.
     *
     * @var App\Repositories\UserRepository
     */
    protected $userGestion;

    /**
     * Create a new AdminController instance.
     *
     * @param  App\Repositories\UserRepository $user_gestion
     * @return void
     */
    public function __construct(UserRepository $userGestion) {
        $this->userGestion = $userGestion;
    }

    /**
     * 
     * @param type $id
     * @return Response
     */
    public function editProfile() {
        $id = session()->get('user')['id'];
        $user = User::findOrFail($id)->toArray();
        $data['user'] = $user;
        $data['fileinput_preview'] = !empty($user['image']) ? route('userimg', ['file' => $user['image'], 'size' => 'large']) : '';
        return view('admin.admin.editprofile')->with($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  App\requests\SchoolUpdateRequest $request
     * @param  App\Models\User
     * @return Response
     */
    public function updateProfile(AdminUpdateProfileRequest $request) {
        $inputs = $request->all();
        $userDeleteImage = FALSE;
        if (isset($inputs['image']) && empty($inputs['image'])) {
            $userDeleteImage = TRUE;
        }
        if ($request->file('image')) {
            $userDeleteImage = TRUE;
            $inputs['image'] = $this->userGestion->userImageUpload($request->file('image'));
        }
        $id = session()->get('user')['id'];
        $inputs['updated_by'] = $id;
        $inputs['id'] = $id;
        $this->userGestion->updateAdmin($inputs, $id, $userDeleteImage);
        return redirect(route('myprofile'))->with('ok', trans('admin/admin.profile_updated_successfully'));
    }

}
