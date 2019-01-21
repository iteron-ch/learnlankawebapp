<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Repositories\UserRepository;
use App\Http\Requests\Tutor\FrontTutorCreateRequest;
use App\Http\Requests\Tutor\CreditPaymentRequest;
use App\Http\Requests\Tutor\PaymentRequest;

use App\Models\Country;
use App\Models\User;
use App\Models\Howfind;
use App\Models\County;
use DB;
use Datatables;
use App\Repositories\EmailRepository;
use App\Repositories\MessageRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\FeesRepository;
use App\Repositories\PaymentRepository;
use Carbon\Carbon;
use Braintree_ClientToken;
use Braintree_Transaction;

use Mail;

use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        
        $data['JsValidator'] = 'App\Http\Requests\Tutor\FrontTutorCreateRequest';
        return view('auth.register', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $user = new User;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->user_type = 5;
        $user->confirmation_code = str_random(30);
        $user->save();

        $this->sendMail($user);

        $data['JsValidator'] = 'App\Http\Requests\Tutor\FrontTutorCreateRequest';
        return view('auth.verify', $data)->with('user', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param $userId
     */
    public function verify(User $user)
    {
        $data['JsValidator'] = 'App\Http\Requests\Tutor\FrontTutorCreateRequest';
        return view('auth.verify', $data)->with('user', $user);
    }

    public function sendMail(User $user)
    {
        Mail::send('auth.verification', ['user' => $user], function ($m) use ($user) {
            $m->from('pasanjg@gmail.com', 'LearnLanka');

            $m->to($user->email, $user->username)->subject('Email Verification');
        });
    }

    public function confirm($confirmation_code)
    {
        if(!$confirmation_code)
        {
            //throw new InvalidConfirmationCodeException;
            return redirect('/login');
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();

        if (!$user)
        {
            //throw new InvalidConfirmationCodeException;
            return redirect('/login');
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        return redirect('/login')->with('ok', 'Account verified. Please login');
    }

    public function resend($confirmation_code)
    {
        if(!$confirmation_code)
        {
            //throw new InvalidConfirmationCodeException;
            return redirect('/login');
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();

        if (!$user)
        {
            //throw new InvalidConfirmationCodeException;
            return redirect('/login');
        }

        $user->confirmed = 0;
        $user->confirmation_code = str_random(30);
        $user->save();

        $this->sendMail($user);

        $data['JsValidator'] = 'App\Http\Requests\Tutor\FrontTutorCreateRequest';
        return view('auth.verify', $data)->with('user', $user);
    }
}
