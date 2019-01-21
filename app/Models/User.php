<?php

namespace App\Models;

use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

    use Authenticatable,
        CanResetPassword,
        EntrustUserTrait,Messagable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function country() {
        return $this->belongsTo('App\Models\Country');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function county() {
        return $this->belongsTo('App\Models\County');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function classlevel() {
        return $this->belongsTo('App\Models\Classlevel');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function howfind() {
        return $this->belongsTo('App\Models\Howfind');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function schooltype() {
        return $this->belongsTo('App\Models\Schooltype', 'school_type');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function studentclass() {
        return $this->belongsTo('App\Models\Studentclass');
    }

    /**
     * One to Many relation
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function whoyou() {
        return $this->belongsTo('App\Models\Whoyou');
    }

    /**
     * Check admin role
     *
     * @return bool
     */
    public function isAdmin() {
        echo $this->user_type;
        die;
        return $this->role->name == 'admin';
    }

    protected $fillable = [
        'school_name',
        'username', 'password'
    ];

    
    /* protected function getUsers($param = array()) {
       return User::where('status', "=", '1')
     */
    /* function to return the student Lists */

    protected function getUsers($params) {
        $school_id = session()->get('user')['id'];
        return User::where('status', '!=', DELETED)
                        ->where('school_id', '=', $school_id)
                        ->where("user_type", "=", $params['user_type'])->orderBy('username')->lists('username', 'id')->all();
    }

    protected function getGroups($params) {
        return User::where('status', '!=', DELETED)
                        ->where('teacher_id', '=', $teacher_id)
                        ->where("user_type", "=", $params['user_type'])->orderBy('username')->lists('username', 'id')->all();
    }

    /* function to return the student Lists */

    protected function getStudentNames($current_id) {
        $user_type = session()->get('user')['user_type'];
        $query = $this->select('username', 'id');
        return $query->where(function ($query) use ($current_id) {
                            $query->where('teacher_id', '=', $current_id)
                            ->orwhere('tutor_id', '=', $current_id);
                        })
                        ->orderBy('username')->lists('username', 'id')->all();
    }

    protected function getStudents() {
        $current_id = session()->get('user')['id'];
        return User::where('status', '!=', DELETED)
                        ->where("teacher_id", "=", $current_id)
                        ->orderBy('username')
                        ->lists('username', 'id')
                        ->all();
    }

    protected function getSchools($user_type) {
        return User::where('status', '!=', DELETED)
                        ->where("user_type", "=", $user_type)
                        ->orderBy('username')
                        ->lists('username', 'id')
                        ->all();
    }
    
    protected function getQuestionBuilderUsers() {
        return  User::whereIn('user_type', [ADMIN,QUESTIONADMIN])->select('first_name','last_name','id')->orderBy('first_name')->get();
    }
    
    protected function getquestionvalidatorusers($params) {
        $query = User::whereIn('user_type', [QUESTIONVALIDATOR])->select('first_name','last_name','id')->orderBy('first_name');
        if(isset($params['userids'])){
            $query->whereIn('id',$params['userids']);
        }
        return $query->get();
    }
    
    protected function studentTestReport(){
        dd("dunctioncalled");
    }   

}
