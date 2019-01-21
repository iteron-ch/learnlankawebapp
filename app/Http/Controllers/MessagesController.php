<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use Carbon\Carbon;
use App\Http\Requests\MessagesRequest;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Models\User;

class MessagesController extends Controller {

    /**
     * @var Pusher
     */
    protected $pusher;

    public function __construct() {
        
    }

    /**
     * Show all of the message threads to the user
     *
     * @return mixed
     */
    public function inbox(Thread $thread) {
        $data = array();
        $authUser = session()->get('user');
        if ($authUser['user_type'] == STUDENT) {
            $data['currentUserId'] = $authUser['id'];
            $data['threads'] = $thread->inboxThread($authUser['id'])->orderBy('updated_onetoone_at','DESC')->get();
            $data['message'] = 'inbox';
            return view('front.messages.inboxlist', $data);
        } else {
            return view('admin.messages.inboxlist', $data);
        }
    }

    /**
     * Display a listing of the SchoolClass.
     * @author     Icreon Tech  - dev5.
     * @return Response
     */
    public function listRecordInbox(Request $request, Thread $thread) {
        $currentUserId = session()->get('user')['id'];
        $threads = $thread->inboxThread($currentUserId);
        return Datatables::of($threads)
                        ->editColumn('subject', function ($thread) use ($currentUserId) {
                            if ($thread->isUnread($currentUserId))
                                return '<a href="' . route('messages.show', ['inbox', $thread->id]) . '" ><strong>' . $thread->subject . '&nbsp;<span class="icon-large icon-envelope"></span></strong></a>';
                            else
                                return '<a href="' . route('messages.show', ['inbox', $thread->id]) . '" >' . $thread->subject . '</a>';
                        })
                        ->editColumn('sender_name', function ($thread) use ($currentUserId) {
                            if ($thread->creator()->id == $currentUserId) {
                                $messageSenderAll = $thread->ActiveParticipantsString($currentUserId, ['first_name', 'last_name', 'school_name']);

                                if (strlen($messageSenderAll) > 150) {
                                    $messageSender = substr($messageSenderAll, 0, 150) . "....";
                                    return '<span title="' . $messageSenderAll . '">' . $messageSender . '</span>';
                                } else {
                                    return '<span title="' . $messageSenderAll . '">' . $messageSenderAll . '</span>';
                                }
                            } else {
                                return $thread->creator()->user_type == SCHOOL ? $thread->creator()->school_name : $thread->creator()->first_name;
                            }
                        })
                        ->editColumn('updated_at', function ($thread) {
                            return outputDateTimeFormat($thread->updated_onetoone_at);
                        })
                        ->addColumn('action', function ($thread) {
                            return '<a href="' . route('messages.show', ['inbox', $thread->id]) . '" ><i class="glyphicon glyphicon-eye-open co"></i></a>
                            <a href="javascript:void(0);" data-id="' . $thread->id . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                        })
                        ->make(true);
    }

    /**
     * Show all of the message threads to the user
     *
     * @return mixed
     */
    public function sent(Thread $thread) {
        $data = array();
        $authUser = session()->get('user');
        if ($authUser['user_type'] == STUDENT) {
            $data['currentUserId'] = $authUser['id'];
            $data['threads'] = $thread->sentThread($authUser['id'])->get();
            $data['message'] = 'sent';

            return view('front.messages.sentlist', $data);
        } else {
            return view('admin.messages.sentlist', $data);
        }
    }

    /**
     * Display a listing of the SchoolClass.
     * @author     Icreon Tech  - dev5.
     * @return Response
     */
    public function listRecordSent(Request $request, Thread $thread) {
        $currentUserId = session()->get('user')['id'];
        $threads = $thread->sentThread($currentUserId);
        return Datatables::of($threads)
                        ->editColumn('subject', function ($thread) {
                            return '<a href="' . route('messages.show', ['sent', $thread->id]) . '" >' . $thread->subject . '<a/>';
                        })
                        ->editColumn('receiver_name', function ($thread) use ($currentUserId) {
                            if ($thread->creator()->id == $currentUserId) {
                                $messageSenderAll = $thread->participantsString($currentUserId, ['first_name', 'last_name', 'school_name']);

                                if (strlen($messageSenderAll) > 150) {
                                    $messageSender = substr($messageSenderAll, 0, 150) . "....";
                                    return '<span title="' . $messageSenderAll . '">' . $messageSender . '</span>';
                                } else {
                                    return '<span title="' . $messageSenderAll . '">' . $messageSenderAll . '</span>';
                                }
                            } else {
                                return $thread->creator()->user_type == SCHOOL ? $thread->creator()->school_name : $thread->creator()->first_name;
                            }
                        })
                        ->editColumn('updated_at', function ($thread) {
                            //return $thread->latestMessage->updated_at->diffForHumans();

                            return outputDateTimeFormat($thread->latestMessage->updated_at);
                        })
                        ->addColumn('action', function ($thread) {
                            return '<a href="' . route('messages.show', ['sent', $thread->id]) . '" ><i class="glyphicon glyphicon-eye-open co"></i></a>
                            <a href="javascript:void(0);" data-id="' . $thread->id . '" class="delete_row"><i class="glyphicon glyphicon-trash font-red"></i></a>';
                        })
                        ->make(true);
    }

    /**
     * Show all of the message threads to the user
     *
     * @return mixed
     */
    public function thread() {
        $currentUserId = session()->get('user')['id'];

        // All threads that user is participating in
        $threads = Thread::forUser($currentUserId)->get();

        return view('messenger.index', compact('threads', 'currentUserId'));
    }

    /**
     * Shows a message thread
     *
     * @param $id
     * @return mixed
     */
    public function show($message, $id) {
        try {
            $thread = Thread::findOrFail($id);
            $data['thread'] = $thread;
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');
            return redirect('messages');
        }
        $authUser = session()->get('user');
        // don't show the current user in list
        $userId = session()->get('user')['id'];
        $thread->markAsRead($userId);
        //thread messages 
        $data['messages'] = $thread->creator()->id == $userId ? $thread->messages : $thread->oneToOneMessages([$thread->creator()->id, $userId]);
        $data['users'] = User::whereNotIn('id', $thread->participantsUserIds($userId))->get();
        $data['message'] = $message;
        $data['back_link'] = $message == 'inbox' ? route('messages.inbox') : route('messages.sent');
        if ($authUser['user_type'] == STUDENT) {
            return view('front.messages.show', $data);
        } else {
            return view('admin.messages.show', $data);
        }
    }

    /**
     * Creates a new message thread
     *
     * @return mixed
     */
    public function create() {
        $authUser = session()->get('user');
        $schoolArray = array();
        $teacherArray = array();
        $parentArray = array();
        $studentArray = array();
        if ($authUser['user_type'] == ADMIN) {
            $schoolRecord = User::where('user_type', '=', SCHOOL)->where('status', '!=', DELETED)->select('school_name', 'id', 'email')->get()->toArray();
            $teacherRecord = User::where('user_type', '=', TEACHER)->where('status', '!=', DELETED)->select('first_name', 'last_name', 'id', 'email')->get()->toArray();
            $parentRecord = User::where('user_type', '=', TUTOR)->where('status', '!=', DELETED)->select('first_name', 'last_name', 'id', 'email')->get()->toArray();
            $studentRecord = User::where('user_type', '=', STUDENT)->where('status', '!=', DELETED)->select('first_name', 'last_name', 'id', 'email')->get()->toArray();
            $userTypeEnable = array(
                'school' => TRUE,
                'teacher' => TRUE,
                'parent' => TRUE,
                'student' => TRUE
            );
        } elseif ($authUser['user_type'] == SCHOOL) {
            $teacherRecord = User::where(['user_type' => TEACHER, 'school_id' => $authUser['id']])->where('status', '!=', DELETED)->select('first_name', 'last_name', 'id', 'email')->get()->toArray();
            $studentRecord = User::where(['user_type' => STUDENT, 'school_id' => $authUser['id']])->where('status', '!=', DELETED)->select('first_name', 'last_name', 'id', 'email')->get()->toArray();
            $userTypeEnable = array(
                'teacher' => TRUE,
                'student' => TRUE
            );
        } elseif ($authUser['user_type'] == TEACHER) {
            $studentRecord = User::where(['user_type' => STUDENT, 'teacher_id' => $authUser['id']])->where('status', '!=', DELETED)->select('first_name', 'last_name', 'id', 'email')->get()->toArray();
            $userTypeEnable = array(
                'student' => TRUE
            );
        } elseif ($authUser['user_type'] == TUTOR) {
            $studentRecord = User::where(['user_type' => STUDENT, 'tutor_id' => $authUser['id']])->where('status', '!=', DELETED)->select('first_name', 'last_name', 'id', 'email')->get()->toArray();
            $userTypeEnable = array(
                'student' => TRUE
            );
        }

        if (isset($schoolRecord) && count($schoolRecord) > 0) {
            foreach ($schoolRecord as $key => $val) {
                $schoolArray[$val['id']] = $val['school_name'];
            }
        }

        if (isset($teacherRecord) && count($teacherRecord) > 0) {
            foreach ($teacherRecord as $key => $val) {
                $teacherArray[$val['id']] = trim($val['first_name'] . ' ' . $val['last_name']);
            }
        }
        if (isset($parentRecord) && count($parentRecord) > 0) {
            foreach ($parentRecord as $key => $val) {
                $parentArray[$val['id']] = trim($val['first_name'] . ' ' . $val['last_name']);
            }
        }
        if (isset($studentRecord) && count($studentRecord) > 0) {
            foreach ($studentRecord as $key => $val) {
                $studentArray[$val['id']] = trim($val['first_name'] . ' ' . $val['last_name']);
            }
        }

        $data['schoolArray'] = $schoolArray;
        $data['teacherArray'] = $teacherArray;
        $data['parentArray'] = $parentArray;
        $data['studentArray'] = $studentArray;
        $data['userTypeEnable'] = $userTypeEnable;
        $data['page_heading'] = trans('admin/messages.manage_message_centre');
        $data['page_title'] = trans('admin/messages.create_message');
        $data['trait'] = array('trait_1' => trans('admin/messages.compose'));
        $data['JsValidator'] = 'App\Http\Requests\MessagesRequest';
        return view('admin.messages.create', $data);
    }

    /**
     * Stores a new message thread
     *
     * @return mixed
     */
    public function store(MessagesRequest $request) {
        $input = $request->all();
        $thread = Thread::create(
                        [
                            'subject' => $input['subject'],
                            'created_by' => Auth::user()->id,
                            'updated_by' => Auth::user()->id,
                            'updated_onetoone_at' => new Carbon
                        ]
        );

        // Message
        $message = Message::create(
                        [
                            'thread_id' => $thread->id,
                            'user_id' => Auth::user()->id,
                            'body' => $input['message'],
                        ]
        );

        // Sender
        Participant::create(
                [
                    'thread_id' => $thread->id,
                    'user_id' => Auth::user()->id,
                    'last_read' => new Carbon
                ]
        );

        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipants($input['recipients']);
        }

        //$this->oooPushIt($message);

        return redirect(route('messages.sent'))->with('ok', trans('admin/messages.sent_successfully'));
    }

    /**
     * Adds a new message to a current thread
     *
     * @param $id
     * @return mixed
     */
    public function update($id) {
        try {
            $thread = Thread::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            Session::flash('error_message', 'The thread with ID: ' . $id . ' was not found.');

            return redirect('messages');
        }
        $users = $thread->participantsUserIds()->toArray();
        $cntParticipants = count($users);

        $thread->activateAllParticipants();
        //update thread
        $thread->updated_at = new Carbon;
        $thread->updated_by = Auth::user()->id;
        if(Auth::user()->id == $thread->creator()->id){
            $thread->updated_onetoone_at = new Carbon;
        } elseif($cntParticipants <= 2){
            $thread->updated_onetoone_at = new Carbon;
        }
        $thread->save();
        // Message
        $message = Message::create(
                        [
                            'thread_id' => $thread->id,
                            'user_id' => Auth::id(),
                            'body' => Input::get('message'),
                        ]
        );

        // Add replier as a participant
        $participant = Participant::firstOrCreate(
                        [
                            'thread_id' => $thread->id,
                            'user_id' => session()->get('user')['id']
                        ]
        );
        $participant->last_read = new Carbon;
        $participant->save();

        // Recipients
        if (Input::has('recipients')) {
            $thread->addParticipants(Input::get('recipients'));
        }

        //$this->oooPushIt($message);
        return redirect()->back()->with('ok', trans('admin/messages.sent_successfully'));
    }
    
    /**
     * delete a thread for a user
     * @param type $id
     */
    public function delete(Request $request){
        $inputs = $request->all();
        Participant::where(
            [
                'thread_id' => $inputs['id'],
                'user_id'   => Auth::user()->id
            ]
        )->delete();
        return response()->json();

    }

    /**
     * Send the new message to Pusher in order to notify users
     *
     * @param Message $message
     */
    protected function oooPushIt(Message $message) {
        $thread = $message->thread;
        $sender = $message->user;

        $data = [
            'thread_id' => $thread->id,
            'div_id' => 'thread_' . $thread->id,
            'sender_name' => $sender->first_name,
            'thread_url' => route('messages.show', ['id' => $thread->id]),
            'thread_subject' => $thread->subject,
            'html' => view('messenger.html-message', compact('message'))->render(),
            'text' => str_limit($message->body, 50)
        ];

        $recipients = $thread->participantsUserIds();
        if (count($recipients) > 0) {
            foreach ($recipients as $recipient) {
                if ($recipient == $sender->id) {
                    continue;
                }

                $this->pusher->trigger('for_user_' . $recipient, 'new_message', $data);
            }
        }
    }

    /**
     * Mark a specific thread as read, for ajax use
     *
     * @param $id
     */
    public function read($id) {
        $thread = Thread::find($id);
        if (!$thread) {
            abort(404);
        }

        $thread->markAsRead(Auth::id());
    }

    /**
     * Get the number of unread threads, for ajax use
     *
     * @return array
     */
    public function unread() {
        $count = Auth::user()->newMessagesCount();

        return ['msg_count' => $count];
    }

}
