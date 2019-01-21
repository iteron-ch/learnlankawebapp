<?php

namespace Cmgmyr\Messenger\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class Thread extends Eloquent {

    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'threads';

    /**
     * The attributes that can be set with Mass Assignment.
     *
     * @var array
     */
    protected $fillable = ['subject','created_by','updated_by','updated_onetoone_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'deleted_at','updated_onetoone_at'];

    /**
     * Messages relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages() {
        return $this->hasMany('Cmgmyr\Messenger\Models\Message');
    }
    
    /**
     * User relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Config::get('messenger.user_model'));
    }

    /**
     * Returns the latest message from a thread
     *
     * @return \Cmgmyr\Messenger\Models\Message
     */
    public function getLatestMessageAttribute() {
        return $this->messages()->latest()->first();
    }

    /**
     * Participants relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function participants() {
        return $this->hasMany('Cmgmyr\Messenger\Models\Participant');
    }

    /**
     * Returns the user object that created the thread
     *
     * @return mixed
     */
    public function creator()
    {
        return $this->messages()->oldest()->first()->user;
    }
    
    /**
     * Returns all of the latest threads by updated_at date
     *
     * @return mixed
     */
    public static function getAllLatest() {
        return self::latest('updated_at');
    }

    /**
     * Returns an array of user ids that are associated with the thread
     *
     * @param null $userId
     * @return array
     */
    public function participantsUserIds($userId = null) {
        $users = $this->participants()->withTrashed()->lists('user_id');

        if ($userId) {
            $users[] = $userId;
        }

        return $users;
    }

    /**
     * Returns threads that the user is associated with
     *
     * @param $query
     * @param $userId
     * @return mixed
     */
    public function scopeForUser($query, $userId) {
        return $query->join('participants', 'threads.id', '=', 'participants.thread_id')
                        ->where('participants.user_id', $userId)
                        ->where('participants.deleted_at', null)
                        ->select('threads.*');
    }

    /**
     * Returns threads that the user is associated with
     *
     * @param $query
     * @param $userId
     * @return mixed
     */
    public function inboxThread($userId) {
        return Thread::join('participants', 'threads.id', '=', 'participants.thread_id')
                        ->where('participants.user_id', $userId)
                        ->where('participants.deleted_at', null)
                        ->whereRaw(DB::raw("`threads`.`id` NOT IN (SELECT temp.thread_id FROM (SELECT thread_id,COUNT(thread_id) AS cnt,user_id FROM messages GROUP BY thread_id HAVING cnt = 1 AND user_id = " . $userId . ") AS temp)"))
                        ->select('threads.*');
    }

    /**
     * Returns threads that the user is associated with
     *
     * @param $query
     * @param $userId
     * @return mixed
     */
    public function sentThread($userId) {
        return Thread::join('participants', 'threads.id', '=', 'participants.thread_id')
                        ->where('participants.user_id', $userId)
                        ->where('participants.deleted_at', null)
                        ->whereRaw(DB::raw("`threads`.`id` IN (SELECT thread_id FROM messages WHERE user_id = " . $userId . " GROUP BY thread_id)"))
                        ->select('threads.*');
    }

    /**
     * Returns threads with new messages that the user is associated with
     *
     * @param $query
     * @param $userId
     * @return mixed
     */
    public function scopeForUserWithNewMessages($query, $userId) {
        return $query->join('participants', 'threads.id', '=', 'participants.thread_id')
                        ->where('participants.user_id', $userId)
                        ->whereNull('participants.deleted_at')
                        ->where(function ($query) {
                            $query->where('threads.updated_at', '>', $this->getConnection()->raw($this->getConnection()->getTablePrefix() . 'participants.last_read'))
                            ->orWhereNull('participants.last_read');
                        })
                        ->select('threads.*');
    }

    /**
     * Adds users to this thread
     *
     * @param array $participants list of all participants
     * @return void
     */
    public function addParticipants(array $participants) {
        if (count($participants)) {
            foreach ($participants as $user_id) {
                Participant::firstOrCreate([
                    'user_id' => $user_id,
                    'thread_id' => $this->id,
                ]);
            }
        }
    }

    public function oneToOneMessages(array $participants) {
        return $this->messages()->whereIn('user_id', $participants)->get();
    }

    /**
     * Mark a thread as read for a user
     *
     * @param integer $userId
     */
    public function markAsRead($userId) {
        try {
            $participant = $this->getParticipantFromUser($userId);
            $participant->last_read = new Carbon;
            $participant->save();
        } catch (ModelNotFoundException $e) {
            // do nothing
        }
    }

    /**
     * See if the current thread is unread by the user
     *
     * @param integer $userId
     * @return bool
     */
    public function isUnread($userId) {
        try {
            $participant = $this->getParticipantFromUser($userId);
            if ($userId == $this->creator()->id) { 
                if ($this->updated_at > $participant->last_read) {
                    return true;
                }
            } else { 
                if ($this->creator()->id == $this->updated_by && $this->updated_at > $participant->last_read) {
                    return true;
                }
            }
        } catch (ModelNotFoundException $e) {
            // do nothing
        }

        return false;
    }

    /**
     * Finds the participant record from a user id
     *
     * @param $userId
     * @return mixed
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getParticipantFromUser($userId) {
        return $this->participants()->where('user_id', $userId)->firstOrFail();
    }

    /**
     * Restores all participants within a thread that has a new message
     */
    public function activateAllParticipants() {
        $participants = $this->participants()->withTrashed()->get();
        foreach ($participants as $participant) {
            $participant->restore();
        }
    }

    /**
     * Generates a string of participant information
     *
     * @param null $userId
     * @param array $columns
     * @return string
     */
    public function participantsString($userId = null, $columns = ['name']) {
        $selectString = $this->createSelectString($columns);
        $participantNames = $this->getConnection()->table('users')
                ->join('participants', 'users.id', '=', 'participants.user_id')
                ->where('participants.thread_id', $this->id)
                ->select($this->getConnection()->raw($selectString));

        if ($userId !== null) {
            $participantNames->where('users.id', '!=', $userId);
        }

        $userNames = $participantNames->lists('users.name');

        return implode(', ', $userNames);
    }

    /**
     * Generates a string of active participant information
     *
     * @param null $userId
     * @param array $columns
     * @return string
     */
    public function ActiveParticipantsString($userId = null, $columns = ['name']) {
        $selectString = $this->createSelectString($columns);
        $participantNames = $this->getConnection()->table('users')
                ->join('messages', 'users.id', '=', 'messages.user_id')
                ->where('messages.thread_id', $this->id)
                ->groupBy('messages.user_id')
                ->select($this->getConnection()->raw($selectString));

        if ($userId !== null) {
            $participantNames->where('users.id', '!=', $userId);
        }

        $userNames = $participantNames->lists('users.name');

        return implode(', ', $userNames);
    }
    
    /**
     * Generates a select string used in participantsString()
     *
     * @param $columns
     * @return string
     */
    private function createSelectString($columns) {
        $dbDriver = $this->getConnection()->getDriverName();

        switch ($dbDriver) {
            case 'pgsql':
            case 'sqlite':
                $columnString = implode(" || ' ' || " . $this->getConnection()->getTablePrefix() . "users.", $columns);
                $selectString = "(" . $this->getConnection()->getTablePrefix() . "users." . $columnString . ") as name";
                break;
            case 'sqlsrv':
                $columnString = implode(" + ' ' + " . $this->getConnection()->getTablePrefix() . "users.", $columns);
                $selectString = "(" . $this->getConnection()->getTablePrefix() . "users." . $columnString . ") as name";
                break;
            default:
                $columnString = implode(", ' ', " . $this->getConnection()->getTablePrefix() . "users.", $columns);
                $selectString = "concat(" . $this->getConnection()->getTablePrefix() . "users." . $columnString . ") as name";
        }

        return $selectString;
    }

}
