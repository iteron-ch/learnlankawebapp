<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Request;
use Response;
use Illuminate\Contracts\Auth\Guard;
use App\Events\UserLoggedIn;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Repositories\UserRepository;
use App\Repositories\RegisterRepository;
use App\Services\MaxValueDelay;
use App\Jobs\SendMail;
use DB;

class AuthController extends Controller {

    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    protected $userRepo;

    public function __construct(UserRepository $userRepo, RegisterRepository $registerRepo) {
        $this->userRepo = $userRepo;
        $this->registerRepo = $registerRepo;
        $this->middleware('guest', ['except' => 'getLogout']);
    }

    /**
     * Handle a login request to the application.
     *
     * @param  App\Http\Requests\LoginRequest  $request
     * @param  App\Services\MaxValueDelay  $maxValueDelay
     * @param  Guard  $auth
     * @return Response
     */
    public function postLogin(
    LoginRequest $request, MaxValueDelay $maxValueDelay, Guard $auth) {
        $logValue = $request->input('log');

        if ($maxValueDelay->check($logValue)) {
            if (Request::ajax()) {
                return Response::json(array(
                            'success' => FALSE,
                            'message' => trans('front/login.maxattempt')
                ));
            } else {
                return redirect('/login')
                                ->with('error', trans('front/login.maxattempt'));
            }
        }

        //$logAccess = filter_var($logValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        $logAccess = 'username';
        $credentials = [
            $logAccess => $logValue,
            'password' => $request->input('password'),
            'status' => ACTIVE
        ];

        if (!$auth->validate($credentials)) {
            $maxValueDelay->increment($logValue);
            if (Request::ajax()) {
                return Response::json(array(
                            'success' => FALSE,
                            'message' => trans('front/login.credentials')
                ));
            } else {
                return redirect('/login')
                                ->with('error', trans('front/login.credentials'))
                                ->withInput($request->only('log'));
            }
        }

        $user = $auth->getLastAttempted();
        if ($user->confirmed) {
            $userType = $user->user_type;
            if ($user->school_id && ($userType == TEACHER || $userType == STUDENT)) {
                $parentUser = DB::select("SELECT u2.id FROM users AS u1 JOIN users u2 on u1.school_id = u2.id WHERE u2.status = '" . ACTIVE . "' AND u1.id = " . $user->id . " ");
                if (!$parentUser) {
                    $maxValueDelay->increment($logValue);
                    if (Request::ajax()) {
                        return Response::json(array(
                                    'success' => FALSE,
                                    'message' => trans('front/login.credentials')
                        ));
                    } else {
                        return redirect('/login')
                                        ->with('error', trans('front/login.parent_inactive'))
                                        ->withInput($request->only('log'));
                    }
                }
            }
            $auth->login($user, $request->has('memory'));
            event(new UserLoggedIn($user));
            if ($request->session()->has('user_id')) {
                $request->session()->forget('user_id');
            }

            $loggedInUserArray = $request->session()->get('user');
            $action = $loggedInUserArray['user_type'] == STUDENT ? '/' : '/dashboard';

            //Store Login details 
            //return $user->user_type;
            $this->userRepo->storeLoginDetails($loggedInUserArray['id']);
            
            //assign new tasks
            if($user->user_type == 5){
                $this->registerRepo->assignNewTasks($user->id);
            }

            if (Request::ajax()) {
                return Response::json(array(
                            'success' => TRUE,
                            'action' => $action,
                            'message' => ''
                ));
            } else {
                return redirect($action);
            }
        }
        $request->session()->put('user_id', $user->id);
        if (Request::ajax()) {
            return Response::json(array(
                        'success' => FALSE,
                        'message' => trans('front/login.again')
            ));
            //return trans('front/login.again');
        } else {
            return redirect('/login')->with('error', trans('front/verify.again'));
        }
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  App\Http\Requests\RegisterRequest  $request
     * @param  App\Repositories\UserRepository $user_gestion
     * @return Response
     */
    public function postRegister(
    RegisterRequest $request, UserRepository $user_gestion) {
        $user = $user_gestion->store(
                $request->all(), $confirmation_code = str_random(30)
        );

        $this->dispatch(new SendMail($user));

        return redirect('/')->with('ok', trans('front/verify.message'));
    }

    /**
     * Handle a confirmation request.
     *
     * @param  App\Repositories\UserRepository $user_gestion
     * @param  string  $confirmation_code
     * @return Response
     */
    public function getConfirm(
    UserRepository $user_gestion, $confirmation_code) {
        $user = $user_gestion->confirm($confirmation_code);

        return redirect('/')->with('ok', trans('front/verify.success'));
    }

    /**
     * Handle a resend request.
     *
     * @param  App\Repositories\UserRepository $user_gestion
     * @param  Illuminate\Http\Request $request
     * @return Response
     */
    public function getResend(
    UserRepository $user_gestion, Request $request) {
        if ($request->session()->has('user_id')) {
            $user = $user_gestion->getById($request->session()->get('user_id'));

            $this->dispatch(new SendMail($user));

            return redirect('/')->with('ok', trans('front/verify.resend'));
        }

        return redirect('/');
    }

    public function login() {
        return view('front.auth.login');
    }

}
