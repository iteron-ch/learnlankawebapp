<?php
/**
 * This controller is used for Tutor.
 * @package    User
 * @author     Icreon Tech  - dev2.
 */

namespace App\Http\Controllers;

use App\Repositories\UserRepository;
use App\Http\Requests\UserPasswordUpdateRequest;
use App\Models\User;
use Hash;

/**
 * This controller is used for user.
 * @author     Icreon Tech - dev2.
 */
class UserController extends Controller {

    /**
     * The UserRepository instance.
     * @var App\Repositories\UserRepository
     */
    protected $userRepo;

    /**
     * Create a new TutorController instance.
     * @param  App\Repositories\UserRepository $userRepo
     * @return void
     */
    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
    }

    /**
     * Update user password.
     * @author     Icreon Tech - dev2.
     * @param \App\Models\User $user
     * @param \App\Http\Requests\UserPasswordUpdateRequest $request
     * @return type
     */
    public function updatePassword(User $user, UserPasswordUpdateRequest $request) {
        $id = session()->get('user')['id'];
        $user = $user->where('id', '=', $id)->first();
        $redirect = $this->userRepo->getUpdatePasswordRedirect($user['user_type']);
        $inputs = $request->all();
        $old_password = $inputs['old_password'];
        if (!Hash::check($old_password, $user->password)) {
            return redirect(route($redirect,['action' => 'changepassword']))->with('error', trans('admin/admin.update_password_mismatch'));
        } else {
            $inputs['password'] = Hash::make($inputs['password']);
            $inputs['updated_by'] = $id;
            $inputs['id'] = $id;
            $this->userRepo->updatePassword($user, $inputs);
            return redirect(route($redirect,['action' => 'changepassword']))->with('ok', trans('admin/admin.password_updated'));
        }
    }

}
