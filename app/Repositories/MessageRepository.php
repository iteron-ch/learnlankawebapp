<?php

/**
 * This is used for message package.
 * @package    Message
 * @author     Icreon Tech  - dev2.
 */

namespace App\Repositories;


use Carbon\Carbon;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Support\Facades\Auth;
/**
 * This is used for eamil send.
 * @package    Email
 * @author     Icreon Tech  - dev2.
 */
class MessageRepository extends BaseRepository {

    /**
     * The Mailer instance.
     */
    protected $mailer;

    /**
     * Create a new EmailRepository instance.
     * @param \Illuminate\Contracts\Mail\Mailer $mailer
     * @return void
     */
    public function __construct() {
        
    }

    public function storeMessage($params){
        $thread = Thread::create(
                        [
                            'subject' => $params['subject'],
                            'created_by' => $params['created_by'],
                            'updated_by' => $params['created_by'],
                            'updated_onetoone_at' => new Carbon
                        ]
        );

        // Message
        $message = Message::create(
                        [
                            'thread_id' => $thread->id,
                            'user_id' => $params['created_by'],
                            'body' => $params['message'],
                        ]
        );

        // Sender
        Participant::create(
                [
                    'thread_id' => $thread->id,
                    'user_id' => $params['created_by'],
                    'last_read' => new Carbon
                ]
        );
        // Recipients
        $thread->addParticipants($params['recipients']);
    }
}
