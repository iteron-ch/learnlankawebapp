<?php

namespace App\Handlers\Events;
use App\Events\UserLoggedIn;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleUserLoggedIn
{
    /**
     * Create the event handler.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserLoggedIn  $event
     * @return void
     */
    public function handle(UserLoggedIn $event)
    {
        $user = $event->user;
        session()->put('statut', $user->user_type);
        $userData = array(
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'user_type' => $user->user_type,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'school_name' => $user->school_name,
            'school_id' => $user->school_id,
            'image' => $user->image
        );
        if($user->user_type == STUDENT){
            $userData['key_stage'] = $user->key_stage;
            $userData['year_group'] = $user->year_group;
            $userData['tutor_id'] = $user->tutor_id;
            $userData['teacher_id'] = $user->teacher_id;
        }
        session()->put('user', $userData);
    }
}
