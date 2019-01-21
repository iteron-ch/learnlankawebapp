<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\View\Factory;
use Illuminate\Support\Facades\Password;
use Illuminate\Mail\Message;
use App\Http\Requests\Auth\EmailPasswordLinkRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Repositories\UserRepository;
use App\Repositories\EmailRepository;

class PasswordController extends Controller {
    /*
      |--------------------------------------------------------------------------
      | Password Reset Controller
      |--------------------------------------------------------------------------
      |
      | This controller is responsible for handling password reset requests
      | and uses a simple trait to include this behavior. You're free to
      | explore this trait and override any methods you wish to tweak.
      |
     */

use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepo) {
        $this->userRepo = $userRepo;
        $this->middleware('guest');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  EmailPasswordLinkRequest  $request
     * @param  Illuminate\View\Factory $view
     * @return Response
     */
    public function postEmail(
    EmailPasswordLinkRequest $request, Factory $view) {
        $view->composer('emails.auth.password', function($view) {
            $view->with([
                'enquery_url' => LIVE_WP_URL.'/send-enquiry',
                'site_url' => LIVE_WP_URL,
            ]);
        });

        $response = Password::sendResetLink($request->only('email'), function (Message $message) {
                    $message->subject(trans('front/password.reset'));
                });

        switch ($response) {
            case Password::RESET_LINK_SENT:
                return redirect()->back()->with('ok', trans($response));

            case Password::INVALID_USER:
                return redirect()->back()->with('error', trans($response));
        }
    }

    /**
     * Reset the given user's password.
     * 
     * @param  ResetPasswordRequest  $request
     * @return Response
     */
    public function postReset(ResetPasswordRequest $request) {
        $credentials = $request->only(
                'email', 'password', 'password_confirmation', 'token'
        );

        $response = Password::reset($credentials, function($user, $password) {
                    $this->resetPassword($user, $password);
                });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return redirect()->to('/login')->with('ok', trans('admin/auth/forgotpassword.reset_success'));

            default:
                return redirect()->back()
                                ->with('error', trans($response))
                                ->withInput($request->only('email'));
        }
    }

    public function getUsername() {
        $data = array();
        return view('admin.auth.forgotusername', $data);
    }

    public function sendUsername(EmailPasswordLinkRequest $request, EmailRepository $emailRepo) {
        $emailId = trim($request->get('email'));
        $params['email'] = $emailId;
        $userArray = $this->userRepo->getUser($params)->get()->first();
        if (!empty($userArray))
            $userArray = $userArray->toArray();
        if (empty($userArray)) {
            return redirect(route('forgotusername'))->with('error', "We can't find a user with that e-mail address.");
        } else {
            $emailTemplateId = '20';
            if ($userArray['user_type'] == 2) {
                $emailParam = array(
                    'addressData' => array(
                        'to_email' => $userArray['email'],
                        'to_name' => $userArray['username'],
                    ),
                    'userData' => array(
                        'school_name' => $userArray['school_name'],
                        'username' => $userArray['username'],
                        'name' => $userArray['username'],
                    )
                );
            } else {
                $emailParam = array(
                    'addressData' => array(
                        'to_email' => $userArray['email'],
                        'to_name' => $userArray['username'],
                    ),
                    'userData' => array(
                        'school_name' => $userArray['school_name'],
                        'username' => $userArray['username'],
                        'name' => $userArray['first_name'] . ' ' . $userArray['last_name'],
                    )
                );
            }
            $emailRepo->sendEmail($emailParam, $emailTemplateId);
            return redirect(route('forgotusername'))->with('ok', "Your username has been emailed to your email id.");
        }


        $data = array();
        return view('admin.auth.forgotusername', $data);
    }

}
