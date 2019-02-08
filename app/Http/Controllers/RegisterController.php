<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Repositories\TaskRepository;
use App\Repositories\RegisterRepository;

class RegisterController extends Controller
{

    public function __construct(User $user, RegisterRepository $registerRepo)
    {
        $this->middleware('guest');
        $this->user = $user;
        $this->registerRepo = $registerRepo;
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
        $user = new $this->user;
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->username = $request->input('username');
        $user->email = $request->input('email');
        $user->password = Hash::make($request->input('password'));
        $user->user_type = 5;
        $user->confirmation_code = str_random(30);

        $user->school_id = 21;
        $user->teacher_id = 23;
        $user->created_by = 23;
        $user->updated_by = 23;
        $user->key_stage = 2;
        $user->year_group = 6;

        $user->save();

        $this->registerRepo->sendMail($user);

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

    /**
     * Confirms the verification code.
     *
     * @param $confirmation_code
     */
    public function confirm($confirmation_code)
    {
        if(!$confirmation_code)
        {
            return redirect('/login');
        }

        $user = User::whereConfirmationCode($confirmation_code)->first();

        if (!$user)
        {
            return redirect('/login');
        }

        $user->confirmed = 1;
        $user->confirmation_code = null;
        $user->save();

        $this->registerRepo->assignTest($user->id);

        return redirect('/login')->with('ok', 'Account verified. Please login');
    }

    /**
     * Resends the verification code.
     *
     * @param $confirmation_code
     */
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

        $this->registerRepo->sendMail($user);

        $data['JsValidator'] = 'App\Http\Requests\Tutor\FrontTutorCreateRequest';
        return view('auth.verify', $data)->with('user', $user);
    }
}
