<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\HelpCentre;
use App\Models\HelpCentreFiles;
use DB;

class HelpCentreRepository extends BaseRepository {


    /**
     * Create a new HelpCentreRepository instance.
     *
     * @param  App\Models\HelpCentre $helpcentre
     * @return void
     */
    public function __construct(HelpCentre $model) {
        $this->model = $model;
    }

    /**
     * 
     * @param type $params
     * @return type
     */
    public function getHelpCentreList($params) {
        $query = $this->model
                ->select(['helpcentres.id', 'helpcentres.visible_to', 'helpcentres.title', 'helpcentres.status','helpcentres.category', 'helpcentres.created_at', 'helpcentres.created_by', 'helpcentres.deleted_at', 'helpcentres.updated_by', 'helpcentres.updated_at',  DB::raw('group_concat(distinct(m_strands.strand) SEPARATOR ", ")  as all_strand'), DB::raw('group_concat(distinct(sub_strands.strand) SEPARATOR ", ")  as all_sub_strand'), DB::raw('group_concat(distinct(helpcentre_files.original_file_name) SEPARATOR ", ")  as all_file_name')])
                ->where('helpcentres.status', '!=', DELETED)
                ->join('helpcentre_files', 'helpcentre_files.helpcentres_id', '=', 'helpcentres.id', 'left')
                ->join('strands', 'strands.id', '=', 'helpcentres.strands_id', 'left')
                ->join('helpcentre_categories', 'helpcentre_categories.helpcentre_id', '=', 'helpcentres.id', 'left')
                ->join('helpcentre_strands', 'helpcentre_strands.helpcentre_id', '=', 'helpcentres.id', 'left')
                ->join('helpcentre_substrands', 'helpcentre_substrands.helpcentre_id', '=', 'helpcentres.id', 'left')
                ->join('strands as m_strands', 'm_strands.id', '=', 'helpcentre_strands.strands', 'left')
                ->join('strands as sub_strands', 'sub_strands.id', '=', 'helpcentre_substrands.substrands', 'left')
                ->groupBy('helpcentres.id');
        if ($params['user_type'] != ADMIN)
            $query->whereRaw(DB::raw("FIND_IN_SET(" . $params['user_type'] . ",visible_to)"));
        if(isset($params['id'])){
            $query->where('helpcentres.id',$params['id']);
        }
        return $query;
    }

    /**
     * 
     * @param type $params
     * @return type
     */
    public function searchHelpFrontSubject($params) {
        return $this->model->select([
                            'helpcentres.id', 'helpcentres.title', 'helpcentres.strands_id', 'helpcentre_files.file_name', 'helpcentre_files.media_link', 'helpcentre_files.media_type', 'helpcentre_files.original_file_name', 'helpcentre_files.id as file_id', 'helpcentres.created_at as created_at'])
                        ->join('helpcentre_files', 'helpcentres.id', '=', 'helpcentre_files.helpcentres_id')
                        ->whereRaw('helpcentres.status = "' . ACTIVE . '" AND FIND_IN_SET("'.STUDENT.'",helpcentres.visible_to) AND ((FIND_IN_SET(' . $params['cat'] . ',helpcentres.category) ) OR (helpcentres.strands_id = ""))')
                        ->groupBy('helpcentre_files.id')
                        ->get()->toArray();
    }

    public function searchHelpCentre($params) {
        $cat1 = $cat2 = $cat3 = '';
        if (!empty($params['category'])) {
            $categoryArray = explode(',', $params['category']);
        }
        if (in_array('9', $categoryArray)) {
            $cat2 = 9;
            if (($key = array_search('9', $categoryArray)) !== false) {
                unset($categoryArray[$key]);
            }
        }
        if (in_array('10', $categoryArray)) {
            $cat3 = 10;
            if (($key = array_search('10', $categoryArray)) !== false) {
                unset($categoryArray[$key]);
            }
        }
        if (!empty($categoryArray)) {
            $cat1 = implode(',', $categoryArray);
        }
        $englishStrands = array();
        $mathStrands = array();
        if (!empty($params['strands'])) {
            $standArray = explode(",", $params['strands']);
            foreach ($standArray as $k => $v) {
                $valueArray = explode("-", $v);
                if ($valueArray[1] == 9) {
                    $englishStrands[] = $valueArray[0];
                } else if ($valueArray[1] == 10) {
                    $mathStrands[] = $valueArray[0];
                }
            }
        }
        $allCatDetailArray = array();
        $engDetailArray = array();
        $mathDetailArray = array();
        $responseArray = array();
        if (!empty($cat1)) {
            $sqlCat = "select h.id,h.strands_id, h.title, h.category,h.created_at,hf.id as file_id,hf.file_name ,hf.media_link,hf.media_type 
                        from helpcentre_files as hf
                        join helpcentres as h on h.id = hf.helpcentres_id
                        join helpcentre_categories as hc on hc.helpcentre_id = h.id
                        where h.status = '" . ACTIVE . "' AND FIND_IN_SET(".$params['user_type'].",h.visible_to) AND hc.categories in( $cat1 ) group  by hf.id";
            $allCatDetailArray = DB::select($sqlCat);
        }
        if (!empty($cat2)) {
            if (empty($englishStrands)) {
                $sqlEng = "select h.id,h.strands_id, h.title, h.category,h.created_at,hf.id as file_id,hf.file_name ,hf.media_link,hf.media_type
                            from helpcentre_files as hf
                            join helpcentres as h on h.id = hf.helpcentres_id
                            join helpcentre_categories as hc on hc.helpcentre_id = h.id
                            where h.status = '" . ACTIVE . "' AND FIND_IN_SET(".$params['user_type'].",h.visible_to) AND hc.categories in($cat2) group  by hf.id";
            } else {
                $englishStrandsList = implode(",", $englishStrands);
                $sqlEng = "select h.id,h.strands_id, h.title, h.category,h.created_at,hf.id as file_id,hf.file_name ,hf.media_link,hf.media_type
                            from helpcentre_files as hf
                            join helpcentres as h on h.id = hf.helpcentres_id
                            join helpcentre_categories as hc on hc.helpcentre_id = h.id
                            left join helpcentre_strands as hs on hs.helpcentre_id = h.id
                            where h.status = '" . ACTIVE . "' AND FIND_IN_SET(".$params['user_type'].",h.visible_to) AND hc.categories in( $cat2 ) AND (hs.strands in( $englishStrandsList) OR (h.strands_id = '')) group  by hf.id";
            }
            $engDetailArray = DB::select($sqlEng);
        }
        if (!empty($cat3)) {
            if (empty($mathStrands)) {
                $sqlMath = "select h.id,h.strands_id, h.title, h.category,h.created_at,hf.id as file_id,hf.file_name ,hf.media_link,hf.media_type
                            from helpcentre_files as hf
                            join helpcentres as h on h.id = hf.helpcentres_id
                            join helpcentre_categories as hc on hc.helpcentre_id = h.id
                            where h.status = '" . ACTIVE . "' AND FIND_IN_SET(".$params['user_type'].",h.visible_to) AND hc.categories in($cat3) group  by hf.id";
            } else {
                $mathStarndsList = implode(",", $mathStrands);
                $sqlMath = "select h.id,h.strands_id, h.title, h.category,h.created_at,hf.id as file_id,hf.file_name ,hf.media_link,hf.media_type
                            from helpcentre_files as hf
                            join helpcentres as h on h.id = hf.helpcentres_id
                            join helpcentre_categories as hc on hc.helpcentre_id = h.id
                            left join helpcentre_strands as hs on hs.helpcentre_id = h.id
                            where h.status = '" . ACTIVE . "' AND FIND_IN_SET(".$params['user_type'].",h.visible_to) AND hc.categories in( $cat3 ) AND (hs.strands in( $mathStarndsList) OR (h.strands_id = '')) group  by hf.id";
            }
            $mathDetailArray = DB::select($sqlMath);
        }
        $responseArray['system'] = array('cat' => $categoryArray, 'result' => $allCatDetailArray);
        $responseArray['math'] = array('strands' => $mathStrands, 'result' => $mathDetailArray);
        $responseArray['english'] = array('strands' => $englishStrands, 'result' => $engDetailArray);
        return $responseArray;
    }

    public function getHelpCentreRecord($id) {
        //asd($id);
        return $this->model->select(['helpcentres.id', 'helpcentres.visible_to', 'helpcentres.title', 'helpcentres.category', 'helpcentres.subject', 'helpcentres.strands_id', 'helpcentres.substrands_id', 'helpcentres.status', 'helpcentres.created_at', 'helpcentres.created_by', 'helpcentres.deleted_at', 'helpcentres.updated_by'])
                        ->where('helpcentres.id', '=', $id)
                        ->where('helpcentres.status', '!=', DELETED)
                        ->get();
    }

    public function getHelpCentreData($params) {
        $query = $this->model->select(['helpcentres.id', 'visible_to', 'title', 'subject', 'strands_id', 'substrands_id', 'status', 'created_at', 'created_by', 'deleted_at', 'updated_by', 'helpcentre_files.file_name', 'helpcentre_files.id as file_id', 'helpcentre_files.original_file_name'])
                ->join('helpcentre_files', 'helpcentre_files.helpcentres_id', '=', 'helpcentres.id', 'left')
                ->where('status', '!=', DELETED);
        if (isset($params['id'])) {
            $query->where('helpcentres.id', '=', $params['id']);
        }
        if (isset($params['strands_id'])) {
            $query->where('helpcentres.strands_id', '=', $params['strands_id']);
        }
        if (isset($params['substrands_id'])) {
            $query->where('helpcentres.substrands_id', '=', $params['substrands_id']);
        }
        return $query->get()->toArray();
    }

    public function getHelpCentreFiles($params) {
        $query = $this->model->select(['helpcentres.id', 'title', 'helpcentres.subject', 'strands_id', 'substrands_id', 'helpcentre_files.file_name', 'helpcentre_files.id as file_id', 'helpcentre_files.original_file_name', 'strands.strand'])
                ->join('helpcentre_files', 'helpcentre_files.helpcentres_id', '=', 'helpcentres.id')
                ->join('strands', 'strands.id', '=', 'helpcentres.strands_id')
                ->where('helpcentres.status', '!=', DELETED);
        if (isset($params['subject'])) {
            $query->where('helpcentres.subject', '=', $params['subject']);
        }
        $query->whereRaw(DB::raw("FIND_IN_SET(" . $params['visible_to'] . ",visible_to)"));
        return $query->get()->toArray();
    }

    /**
     * Save the HelpCentre record.
     *
     * @param  App\Models\HelpCentre 
     * @param  Array  $inputs
     * @return void
     */
    public function save($inputs, $helpcentre) {
        $helpcentre->status = isset($inputs['status']) ? $inputs['status'] : ACTIVE;
        if (isset($inputs['id'])) {
            $helpcentre->updated_by = $inputs['updated_by'];
        } else {
            $helpcentre->created_by = $inputs['created_by'];
            $helpcentre->created_by = $inputs['created_by'];
        }
        $helpcentre->title = $inputs['title'];
        $helpcentre->strands_id = !empty($inputs['strands_id']) ? implode(",", $inputs['strands_id']) : '';
        $helpcentre->substrands_id = !empty($inputs['sub_strands_id']) ? implode(",", $inputs['sub_strands_id']) : '';
        $helpcentre->category = !empty($inputs['category']) ? implode(",", $inputs['category']) : '';
        $helpcentre->visible_to = !empty($inputs['visible_to']) ? implode(",", $inputs['visible_to']) : '';
        $lastId = $helpcentre->save() ? $helpcentre->id : FALSE;
        if ($lastId) {
            $this->saveCategories($inputs, $lastId);
            $this->saveStrands($inputs, $lastId);
            $this->saveSubStrands($inputs, $lastId);
            $this->saveHelpFiles(array(
                'media_type' => $inputs['media_type'],
                'media_file' => $inputs['media_file'],
                'media_link' => $inputs['media_link'],
                'toRemove' => isset($inputs['toRemove']) ? $inputs['toRemove'] : ''
                    ), $lastId);
        }
    }

    /**
     * Create a HelpCentre Record.
     *
     * @param  array  $inputs
     * @return App\Models\HelpCentre 
     */
    public function store($inputs) {
        $helpcentre = new $this->model;
        $lastId = $this->save($inputs, $helpcentre);
    }

    /**
     * @author     Icreon Tech - dev5.
     * @param type array $input
     * @param type int $user_id
     */
    public function saveCategories($inputs, $lastId) {
        //asd($inputs);
        if (isset($inputs['id'])) {
            DB::table('helpcentre_categories')->where('helpcentre_id', '=', $inputs['id'])->delete();
        }
        if (!empty($inputs['category'])) {
            foreach ($inputs['category'] as $file => $value) {

                $fileArr = array(
                    'helpcentre_id' => $lastId,
                    'categories' => $value,
                );
                $dataParam[] = $fileArr;
                DB::table('helpcentre_categories')->insert($fileArr);
            }
        }
    }

    /**
     * @author     Icreon Tech - dev5.
     * @param type array $input
     * @param type int $user_id
     */
    public function saveStrands($inputs, $lastId) {
        //asd($inputs);
        if (isset($inputs['id'])) {
            DB::table('helpcentre_strands')->where('helpcentre_id', '=', $inputs['id'])->delete();
        }
        if (!empty($inputs['strands_id'])) {
            foreach ($inputs['strands_id'] as $file => $value) {

                $fileArr = array(
                    'helpcentre_id' => $lastId,
                    'strands' => $value,
                );
                $dataParam[] = $fileArr;
                DB::table('helpcentre_strands')->insert($fileArr);
            }
        }
    }

    /**
     * @author     Icreon Tech - dev5.
     * @param type array $input
     * @param type int $user_id
     */
    public function saveSubStrands($inputs, $lastId) {
        //asd($inputs);
        if (isset($inputs['id'])) {
            DB::table('helpcentre_substrands')->where('helpcentre_id', '=', $inputs['id'])->delete();
        }
        if (!empty($inputs['sub_strands_id'])) {
            foreach ($inputs['sub_strands_id'] as $file => $value) {

                $fileArr = array(
                    'helpcentre_id' => $lastId,
                    'substrands' => $value,
                );
                $dataParam[] = $fileArr;
                DB::table('helpcentre_substrands')->insert($fileArr);
            }
        }
    }

    /**
     * @author     Icreon Tech - dev2.
     * @param type array $input
     * @param type int $user_id
     */
    public function saveHelpFiles($inputs, $lastId) {
        if (count($inputs['media_type'])) {
            foreach ($inputs['media_type'] as $key => $media_type) {
                if($media_type){
                    $fileName = '';
                    $original_file_name = '';
                    $media_link = '';
                    if (in_array($media_type, array(2, 3, 4))) {
                        $file = $inputs['media_file'][$key];
                        $fileName = str_random(10) . '_' . time() . '.' . $file->getClientOriginalExtension(); // renameing image
                        $original_file_name = $file->getClientOriginalName();
                        $file->move(public_path('uploads/helpdocuments/'), $fileName);
                    } else {
                        $media_link = $inputs['media_link'][$key];
                    }
                    $fileArr[] = array(
                        'helpcentres_id' => $lastId,
                        'file_name' => $fileName,
                        'original_file_name' => $original_file_name,
                        'media_type' => $media_type,
                        'media_link' => $media_link
                    );
                }
            }
            if(!empty($fileArr)){
                DB::table('helpcentre_files')->insert($fileArr);
            }
            
        }
        if (!empty($inputs['toRemove'])) {
            foreach ($inputs['toRemove'] as $key) {
                DB::table('helpcentre_files')->where('id', '=', $key)->delete();
            }
        }
    }

    /**
     * Update a HelpCentre.
     *
     * @param  array  $inputs
     * @param  App\Models\HelpCentre 
     * @return void
     */
    public function update($inputs, $id) {
        $model = $this->model->where('id', '=', $id)->first();
        $this->save($inputs, $model);
    }

    /**
     * Get a 2-dimension list of all user types.
     *
     * @param  array  $inputs
     * @param  App\Models\HelpCentre 
     * @return void
     */
    public function visible_array() {
        $owner_type = visibleToArray();
        $ownerArray = array();
        foreach ($owner_type as $key => $value) {
            $ownerArray['ALL'][$key] = $value;
        }
        return $ownerArray;
    }

    /**
     * Update a helpcentre status.
     * @param  array  $inputs
     * @param  App\Models\helpcentre 
     * @return void
     */
    public function destroyGroup($inputs, $id) {
        $group = $this->model->where('id', '=', $id)->first();
        $dateTime = Carbon::now()->toDateTimeString();
        $group->updated_by = session()->get('user')['user_type'];
        $group->deleted_at = $dateTime;
        $group->status = DELETED; // deleted
        if ($group->save()) {
            DB::table('helpcentre_files')->where('helpcentres_id', '=', $id)->delete();
            DB::table('helpcentre_categories')->where('helpcentre_id', '=', $id)->delete();
            DB::table('helpcentre_strands')->where('helpcentre_id', '=', $id)->delete();
            DB::table('helpcentre_substrands')->where('helpcentre_id', '=', $id)->delete();
        }
    }

    public function getHelpfile($id) {
        return HelpCentreFiles::findOrFail($id);
    }
    
    public function getHelpfileRecord($params) {
        $query = HelpCentreFiles::select('*');
        if(isset($params['helpcentres_id'])){
            $query->where('helpcentres_id',$params['helpcentres_id']);
        }
        return $query->get()->toArray();
        
    }

    public function HelpcentreMediaType() {
        return array(1 => 'Video (Youtube/vimeo)', 2 => 'Video (Upload)', 3 => 'Document (pdf / doc / ppt / .notebook)', 4 => 'image (jpg/png/gif)', 5 => 'Website URL');
    }
    
    public function HelpcentreMediaTypeTypeIcon() {
        return array(
            1 => 'fa fa-youtube-play',
            2 => 'fa fa-file-video-o',
            3 => 'fa fa-file-o',
            4 => 'fa fa-file-image-o',
            5 => 'fa fa-link',
            6 => 'fa fa-file-text-o',
            7 => 'fa fa-file-pdf-o',
            8 => 'fa fa-file-powerpoint-o',
            9 => 'fa fa-file-word-o',
        );
    }
    
    public function updateFileDownloadCount($model){
        $model->download_count = $model->download_count + 1;
        $model->save();
    }

}
