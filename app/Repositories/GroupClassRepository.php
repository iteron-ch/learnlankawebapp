<?php

namespace App\Repositories;

use App\Models\Schoolclass;
use App\Models\ClassStudents;
use App\Models\Group;
use App\Models\GroupStudent;
use Carbon\Carbon;
use DB;

class GroupClassRepository extends BaseRepository {

    protected $schoolclass;
    protected $classstudents;
    protected $group;
    protected $groupstudents;

    /**
     * Create a new GroupClassRepository instance.
     * @author     Icreon Tech  - dev2.
     * return void
     */
    public function __construct(Schoolclass $schoolclass, ClassStudents $classstudents, Group $group, GroupStudent $groupstudents) {
        $this->schoolclass = $schoolclass;
        $this->classStudents = $classstudents;
        $this->group = $group;
        $this->groupstudents = $groupstudents;
    }

    /**
     * Get class list grid.
     * @author     Icreon Tech  - dev2.
     * $parmas type array $parmas
     * @return Response
     */
    public function getSchoolclassesList($parmas) {
        return $this->schoolclass->select(['id', 'class_name', 'year', 'status', 'created_at', 'updated_at', 'class_student.cnt_student', 'class_student.class_id', 'schoolclasses.created_by'])
                        ->leftJoin(DB::raw("(SELECT classstudents.class_id,COUNT(classstudents.class_id) cnt_student 
                                    FROM `classstudents` 
                                    JOIN users ON classstudents.student_id = users.id 
                                    WHERE classstudents.school_id = " . $parmas['created_by'] . " AND users.status != '" . DELETED . "' 
                                    GROUP BY classstudents.class_id) AS class_student"), function($join) {
                            $join->on('schoolclasses.id', '=', 'class_student.class_id');
                        })
                        ->where('schoolclasses.status', '!=', DELETED)
                        ->where('schoolclasses.created_by', '=', $parmas['created_by']);
    }

    /**
     * get class list
     * @author     Icreon Tech - dev2.
     * @param type array $params
     * @return type
     */
    public function getClasses($params) {
        return $this->schoolclass
                        ->where('status', '!=', DELETED)
                        ->where('created_by', '=', $params['created_by'])
                        ->orderBy('class_name')->lists('class_name', 'id')->all();
    }

    /**
     * Save the class
     * @author     Icreon Tech - dev2.
     * @param type $schoolclass
     * @param type $inputs
     * @return type int
     */
    private function saveClass($schoolclass, $inputs) {
        $no_of_students = isset($inputs['selected_students']) ? count($inputs['selected_students']) : 0;
        $schoolclass->class_name = $inputs['class_name'];
        $schoolclass->year = $inputs['year'];
        $schoolclass->status = $inputs['status'];
        if (isset($inputs['id'])) {
            DB::table('classstudents')->where('class_id', '=', $inputs['id'])->delete();
            $schoolclass->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '0';
        } else {
            $schoolclass->created_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
            $schoolclass->updated_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
        }
        $lastId = $schoolclass->save() ? $schoolclass->id : FALSE;
        if ($lastId && $no_of_students) {
            $created_at = Carbon::now()->toDateTimeString();
            foreach ($inputs['selected_students'] as $value) {
                $studentArr = array(
                    'class_id' => $lastId,
                    'student_id' => $value,
                    'created_at' => $created_at,
                    'created_by' => $inputs['updated_by'],
                    'school_id' => session()->get('user')['id'],
                );
                $student[] = $studentArr;
            }
            DB::table('classstudents')->insert($student);
        }
        return $lastId;
    }

    /**
     * store the class
     * @author     Icreon Tech - dev2.
     * @param type $inputs
     * @return void
     */
    public function storeClass($inputs) {
        $schoolclass = new $this->schoolclass;
        $this->saveClass($schoolclass, $inputs);
    }

    /**
     * update the class
     * @author     Icreon Tech - dev2.
     * @param type $inputs
     * @param type $id
     * @return void
     */
    public function updateClass($inputs, $id) {
        $schoolclass = $this->schoolclass->where('id', '=', $id)->first();
        $this->saveClass($schoolclass, $inputs);
    }

    /**
     * Get Group detail.
     * @author     Icreon Tech - dev2.
     * @param  integer  $id
     * @return array
     */
    public function getSchoolClass($id) {
        return $this->schoolclass->findOrFail($id);
    }

    /**
     * Get Group students.
     * @author     Icreon Tech - dev2.
     * @param  integer  $id
     * @return array
     */
    public function getSchoolClassStudents($id) {
        return $this->classStudents
                        ->join('users', 'classstudents.student_id', '=', 'users.id', 'inner')
                        ->select(['users.id', 'users.first_name', 'users.last_name'])
                        ->where("classstudents.class_id", "=", $id)
                        ->where("users.status", "!=", DELETED)
                        ->orderBy('classstudents.created_at')
                        ->get()->toArray();
    }

    /**
     * Get Group students ids list.
     * @author     Icreon Tech - dev2.
     * @param  integer  $id
     * @return array
     */
    public function schoolClassStudentIds($id) {
        $schoolClassStudents = $this->getSchoolClassStudents($id);
        $studentIds = array();
        foreach ($schoolClassStudents as $student) {
            $studentIds[] = $student['id'];
        }
        return $studentIds;
    }

    /**
     * Get student class.
     * @author     Icreon Tech - dev2.
     * @param  integer  $id
     * @return array
     */
    public function getStudentClass($id) {
        return $this->classStudents
                        ->join('schoolclasses', 'classstudents.class_id', '=', 'schoolclasses.id', 'inner')
                        ->select(['schoolclasses.id', 'schoolclasses.class_name'])
                        ->where("classstudents.student_id", "=", $id)
                        ->where("schoolclasses.status", "!=", DELETED)
                        ->orderBy('classstudents.created_at')
                        ->get()->toArray();
    }

    public function classStudentIds($id) {
        $classStudents = $this->getStudentClass($id);
        $studentIds = array();
        foreach ($classStudents as $student) {
            $studentIds[] = $student['id'];
        }
        return $studentIds;
    }

    /**
     * Soft delete a class
     * @author     Icreon Tech - dev2.
     * @param  array  $inputs
     * @param  $id 
     * @return void
     */
    public function destroySchoolClass($inputs, $id) {
        $this->classStudents->where('class_id', '=', $inputs['id'])->delete();
        $this->schoolclass->where('id', '=', $inputs['id'])->delete();
    }

    /**
     * Get teacher class students.
     * @author     Icreon Tech - dev2.
     * @param  integer  $teacher_id
     * @return array
     */
    public function teacherClassStudent($params) {
        $teacherClassStudent = $this->getTeacherClassStudent($params);
        $dataArr = array();
        if (count($teacherClassStudent)) {
            foreach ($teacherClassStudent as $row) {
                $ids = explode(",", $row->studentids);
                $names = explode(",", $row->studentnames);
                if (isset($params['multidata'])) {
                    $dataArr[$row->id]['name'] = $row->class_name;
                    $dataArr[$row->id]['student'] = array_combine($ids, $names);
                }else{
                    $dataArr[$row->class_name] = array_combine($ids, $names);
                }
            }
        }
        return $dataArr;
    }

    /**
     * Get teacher class students.
     * @author     Icreon Tech - dev2.
     * @param  integer  $teacher_id
     * @return array
     */
    public function getTeacherClassStudent($params) {
        $sql = "SELECT classstudents.class_id AS class_id,GROUP_CONCAT(users.id) AS studentids,GROUP_CONCAT(CONCAT(users.first_name, ' ', users.last_name)) AS studentnames 
                                        FROM classstudents JOIN users ON classstudents.student_id = users.id";
        $sql .= " WHERE users.teacher_id = " . $params['teacher_id'] . " AND users.status != '" . DELETED . "'";
        if (!empty($params['key_stage'])) {
            $sql .= " AND users.key_stage = " . $params['key_stage'] . "";
        }
        if (!empty($params['year_group'])) {
            $sql .= " AND users.year_group = " . $params['year_group'] . "";
        }
        $sql .= " GROUP BY classstudents.class_id";
        return $this->schoolclass
                        ->select(['schoolclasses.id', 'schoolclasses.class_name', 'cls_student.studentids', 'cls_student.studentnames'])
                        ->join(DB::raw("($sql) AS cls_student"), function($join) {
                            $join->on('schoolclasses.id', '=', 'cls_student.class_id');
                        })
                        ->where("schoolclasses.status", "=", ACTIVE)
                        ->orderBy('schoolclasses.class_name')
                        ->get();
    }

    /**
     * Get teacher class students.
     * @author     Icreon Tech - dev2.
     * @param  integer  $teacher_id
     * @return array
     */
    public function schoolClassStudent($id) {
        $teacherClassStudent = $this->getSchoolClassStudent($id);
        $dataArr = array();
        if (count($teacherClassStudent)) {
            foreach ($teacherClassStudent as $row) {
                $ids = explode(",", $row->studentids);
                $names = explode(",", $row->studentnames);
                $dataArr[$row->class_name] = array_combine($ids, $names);
            }
        }
        return $dataArr;
    }

    /**
     * Get teacher class students.
     * @author     Icreon Tech - dev2.
     * @param  integer  $teacher_id
     * @return array
     */
    public function getSchoolClassStudent($id) {
        return $this->schoolclass
                        ->select(['schoolclasses.id', 'schoolclasses.class_name', 'cls_student.studentids', 'cls_student.studentnames'])
                        ->join(DB::raw("(SELECT classstudents.class_id AS class_id,GROUP_CONCAT(users.id) AS studentids,GROUP_CONCAT(CONCAT(users.first_name, ' ', users.last_name)) AS studentnames 
                                        FROM classstudents JOIN users ON classstudents.student_id = users.id 
                                        WHERE users.school_id = " . $id . " AND users.status != '" . DELETED . "' 
                                        GROUP BY classstudents.class_id 
                                        ) AS cls_student"), function($join) {
                            $join->on('schoolclasses.id', '=', 'cls_student.class_id');
                        })
                        ->where("schoolclasses.status", "=", ACTIVE)
                        ->orderBy('schoolclasses.class_name')
                        ->get();
    }

    public function getClassesStudentList($params = array()) {

        $commonFieldsArray = array('users.id', 'username', 'first_name', 'last_name', 'email', 'users.created_at', 'users.updated_at', 'users.status');
        $query = $this->classStudents->select($commonFieldsArray)
                ->join('users', 'classstudents.student_id', '=', 'users.id', 'inner')
                ->where('users.status', '!=', DELETED)
                ->where("user_type", "=", $params['user_type']);
        if (isset($params['id']) && !empty($params['id']))
            $query->where('classstudents.class_id', '=', $params['id']);
        return $query;
    }

    public function getGroupStudentList($params = array()) {
        $commonFieldsArray = array('users.id', 'username', 'first_name', 'last_name', 'email', 'users.created_at', 'users.updated_at', 'users.status');
        $query = $this->groupstudents->select($commonFieldsArray)
                ->join('users', 'groupstudents.student_id', '=', 'users.id', 'inner')
                ->where('users.status', '!=', DELETED)
                ->where("user_type", "=", $params['user_type']);
        if (isset($params['id']) && !empty($params['id']))
            $query->where('groupstudents.group_id', '=', $params['id']);


        return $query;
    }

    /**
     * 
     * @param type $params
     * @return type
     */
    public function getGroupList($parmas) {
        return $this->group
                        ->select(['id', 'group_name', 'status', 'created_at', 'updated_at', 'group_student.cnt_student'])
                        ->leftJoin(DB::raw("(SELECT groupstudents.group_id,COUNT(groupstudents.group_id) cnt_student 
                                    FROM `groupstudents` 
                                    JOIN users ON groupstudents.student_id = users.id 
                                    WHERE groupstudents.school_id = " . $parmas['created_by'] . " AND users.status != '" . DELETED . "' 
                                    GROUP BY groupstudents.group_id) AS group_student"), function($join) {
                            $join->on('groups.id', '=', 'group_student.group_id');
                        })
                        ->where('groups.status', '!=', DELETED)
                        ->where('groups.created_by', '=', $parmas['created_by']);
    }

    /**
     * get group list
     * @author     Icreon Tech - dev2.
     * @param type $params
     * @return type
     */
    public function getGroups($params) {
        return $this->group
                        ->where('status', '!=', DELETED)
                        ->where('created_by', '=', $params['created_by'])
                        ->orderBy('group_name')->lists('group_name', 'id')->all();
    }

    /**
     * Save the Group.
     *
     * @param  App\Models\Group 
     * @param  Array  $inputs
     * @return void
     */
    public function saveGroup($inputs, $group) {
        $no_of_students = isset($inputs['selected_students']) ? count($inputs['selected_students']) : 0;
        $group->group_name = $inputs['group_name'];
        $group->status = $inputs['status'];
        if (isset($inputs['id'])) {
            DB::table('groupstudents')->where('group_id', '=', $inputs['id'])->delete();
            $group->updated_by = isset($inputs['updated_by']) ? $inputs['updated_by'] : '0';
        } else {
            $group->created_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
            $group->updated_by = isset($inputs['created_by']) ? $inputs['created_by'] : '0';
        }
        $lastId = $group->save() ? $group->id : FALSE;
        if ($lastId && $no_of_students) {
            $created_at = Carbon::now()->toDateTimeString();
            foreach ($inputs['selected_students'] as $value) {
                $studentArr = array(
                    'group_id' => $lastId,
                    'student_id' => $value,
                    'created_at' => $created_at,
                    'created_by' => $inputs['updated_by'],
                    'school_id' => session()->get('user')['id'],
                );
                $student[] = $studentArr;
            }
            DB::table('groupstudents')->insert($student);
        }
        return $lastId;
    }

    /**
     * Create a Group.
     *
     * @param  array  $inputs
     * @return App\Models\Group 
     */
    public function storeGroup($inputs) {
        //dd($inputs);
        $group = new $this->group;
        $this->saveGroup($inputs, $group);
    }

    /**
     * Update a Group.
     *
     * @param  array  $inputs
     * @param  App\Models\Group 
     * @return void
     */
    public function updateGroup($inputs, $id) {
        $group = $this->group->where('id', '=', $id)->first();
        $this->saveGroup($inputs, $group);
    }

    /**
     * Get Group detail.
     *
     * @param  integer  $id
     * @return array
     */
    public function getGroup($id) {
        return $this->group->findOrFail($id);
    }

    /**
     * Get Group students.
     *
     * @param  integer  $id
     * @return array
     */
    public function getGroupStudents($id) {
        return $this->groupstudents
                        ->join('users', 'groupstudents.student_id', '=', 'users.id', 'inner')
                        ->select(['users.id', 'users.first_name', 'users.last_name'])
                        ->where("groupstudents.group_id", "=", $id)
                        ->where("users.status", "!=", DELETED)
                        ->orderBy('groupstudents.created_at')
                        ->get()->toArray();
    }

    /**
     * Get Group students ids list.
     *
     * @param  integer  $id
     * @return array
     */
    public function groupStudentIds($id) {
        $groupStudents = $this->getGroupStudents($id);
        $studentIds = array();
        foreach ($groupStudents as $student) {
            $studentIds[] = $student['id'];
        }
        return $studentIds;
    }

    /**
     * Get student groups.
     *
     * @param  integer  $id
     * @return array
     */
    public function getStudentGroups($id) {
        return $this->groupstudents
                        ->join('groups', 'groupstudents.group_id', '=', 'groups.id', 'inner')
                        ->select(['groups.id', 'groups.group_name'])
                        ->where("groupstudents.student_id", "=", $id)
                        ->where("groups.status", "!=", DELETED)
                        ->orderBy('groupstudents.created_at')
                        ->get()->toArray();
    }

    /**
     * Get student groups ids list.
     *
     * @param  integer  $id
     * @return array
     */
    public function studentGroupIds($id) {
        $studentGroups = $this->getStudentGroups($id);
        $groupIds = array();
        foreach ($studentGroups as $group) {
            $groupIds[] = $group['id'];
        }
        return $groupIds;
    }

    /**
     * Soft delete a group
     * @author     Icreon Tech - dev2.
     * @param  array  $inputs
     * @param  $id 
     * @return void
     */
    public function destroyGroup($inputs, $id) {
        $this->groupstudents->where('group_id', '=', $inputs['id'])->delete();
        $this->group->where('id', '=', $inputs['id'])->delete();
    }

    /**
     * Get teacher group students.
     * @author     Icreon Tech - dev2.
     * @param  integer  $teacher_id
     * @return array
     */
    public function teacherGroupStudent($params) {
        $teacherGroupStudent = $this->getTeacherGroupStudent($params);
        $dataArr = array();
        if (count($teacherGroupStudent)) {
            foreach ($teacherGroupStudent as $row) {
                $ids = explode(",", $row->studentids);
                $names = explode(",", $row->studentnames);
                if (isset($params['multidata'])) {
                    $dataArr[$row->id]['name'] = $row->group_name;
                    $dataArr[$row->id]['student'] = array_combine($ids, $names);
                }else{
                    $dataArr[$row->group_name] = array_combine($ids, $names);
                }
            }
        }
        return $dataArr;
    }

    /**
     * Get teacher group students.
     * @author     Icreon Tech - dev2.
     * @param  integer  $teacher_id
     * @return array
     */
    public function getTeacherGroupStudent($params) {
        $sql = "SELECT groupstudents.group_id AS group_id,GROUP_CONCAT(users.id) AS studentids,GROUP_CONCAT(CONCAT(users.first_name, ' ', users.last_name)) AS studentnames 
                                        FROM groupstudents JOIN users ON groupstudents.student_id = users.id";
        $sql .= " WHERE users.teacher_id = " . $params['teacher_id'] . " AND users.status != '" . DELETED . "'";
        if (!empty($params['key_stage'])) {
            $sql .= " AND users.key_stage = " . $params['key_stage'] . "";
        }
        if (!empty($params['year_group'])) {
            $sql .= " AND users.year_group = " . $params['year_group'] . "";
        }
        $sql .= " GROUP BY groupstudents.group_id";
        return $this->group
                        ->select(['groups.id', 'groups.group_name', 'cls_student.studentids', 'cls_student.studentnames'])
                        ->join(DB::raw("($sql) AS cls_student"), function($join) {
                            $join->on('groups.id', '=', 'cls_student.group_id');
                        })
                        ->where("groups.status", "=", ACTIVE)
                        ->orderBy('groups.group_name')
                        ->get();
    }

    /**
     * Get teacher group students.
     * @author     Icreon Tech - dev2.
     * @param  integer  $teacher_id
     * @return array
     */
    public function schoolGroupStudent($id) {
        $teacherGroupStudent = $this->getSchoolGroupStudent($id);
        $dataArr = array();
        if (count($teacherGroupStudent)) {
            foreach ($teacherGroupStudent as $row) {
                $ids = explode(",", $row->studentids);
                $names = explode(",", $row->studentnames);
                $dataArr[$row->group_name] = array_combine($ids, $names);
            }
        }
        return $dataArr;
    }

    /**
     * Get teacher group students.
     * @author     Icreon Tech - dev2.
     * @param  integer  $teacher_id
     * @return array
     */
    public function getSchoolGroupStudent($id) {
        return $this->group
                        ->select(['groups.id', 'groups.group_name', 'cls_student.studentids', 'cls_student.studentnames'])
                        ->join(DB::raw("(SELECT groupstudents.group_id AS group_id,GROUP_CONCAT(users.id) AS studentids,GROUP_CONCAT(CONCAT(users.first_name, ' ', users.last_name)) AS studentnames 
                                        FROM groupstudents JOIN users ON groupstudents.student_id = users.id 
                                        WHERE users.school_id = " . $id . " AND users.status != '" . DELETED . "' 
                                        GROUP BY groupstudents.group_id 
                                        ) AS cls_student"), function($join) {
                            $join->on('groups.id', '=', 'cls_student.group_id');
                        })
                        ->where("groups.status", "=", ACTIVE)
                        ->orderBy('groups.group_name')
                        ->get();
    }

}
