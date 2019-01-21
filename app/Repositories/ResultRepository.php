<?php

/**
 * This is used for result 
 * @package    Result
 * @author     Icreon Tech  - dev2.
 */

namespace App\Repositories;

use App\Models\Studentstrandmeta;
use DB;
use Carbon\Carbon;

/**
 * This is used for result 
 * @package    Result
 * @author     Icreon Tech  - dev2.
 */
class ResultRepository extends BaseRepository {

    /**
     * The Studentstrandmeta instance.
     *
     * @var App\Models\Studentstrandmeta
     */
    protected $studentstrandmeta;

    /**
     * Create a new ResultRepository instance.
     * @param \App\Models\Studentstrandmeta $model
     * @author     Icreon Tech  - dev2.
     * @return void
     */
    public function __construct(Studentstrandmeta $model) {
        $this->model = $model;
        $this->currentDateTime = Carbon::now()->toDateTimeString();
    }
       public function studentStrandResultList($params) {
        $strandGoodthreshold = STRAND_GOOD_THRESHOLD;
        $strandPerformanceLimit = STRAND_PERFORMANCE_LIMIT;
        $params['prderIndex'] = 'DESC';
        //$studentStrandResult = $this->getStudentStrandResult($params)->toArray();
        $studentStrandResult = $this->getStudentSubStrandResult($params)->get();
        $strandResultTree = $params['strandResult'];
        $poor = array();
        foreach ($studentStrandResult as $row) {
                $substrandName = $strandResultTree['substrands'][$row->strand_id][$row->substrand_id];
                if(round($row->attempt_per_avg) == 100) {
                    $good[] = $substrandName;
                }
                else {
                    $poor[] = $substrandName;
                }
        }
        if(isset($good)){
            $strandResult['good'] = array_slice($good, 0,$strandPerformanceLimit);
        }
        if(isset($poor)){
            $strandResult['poor'] = array_slice($poor, 0,$strandPerformanceLimit);
        }

       /* die;
        foreach ($studentStrandResult as $row) {
            $strand_mark_obtain_persent = round((($row['strand_mark_obtain'] / $row['strand_total_mark']) * 100), 1);
            if ($strand_mark_obtain_persent > 0) {
                $good[] = $row['strand'];
            }else if($strand_mark_obtain_persent == 0){
                $poor[] = $row['strand'];
            }
        }
        if(count($poor) >= $strandPerformanceLimit){
            $poorTemp =  array_slice($poor, 0,$strandPerformanceLimit);;
        }else if(count($poor) == 0 && count($good) > $strandPerformanceLimit){
            $goodTemp = array_slice($good, $strandPerformanceLimit);
            $poorTemp1 = $goodTemp;
            rsort($poorTemp1);
            $poorTemp = array_slice($poorTemp1, 0,$strandPerformanceLimit);
        }else if(count($poor) > 0 && count($poor) < $strandPerformanceLimit){
            $poorTemp2 = array();
            if(count($good) > $strandPerformanceLimit){
                $poorTemp1 = $good;
                rsort($poorTemp1);
                $lim = $strandPerformanceLimit - count($poor);
                $poorTemp2 = array_slice($poorTemp1, 0,$lim);
            }
            $poorTemp = array_merge($poor,$poorTemp2);
        }
        $params['prderIndex'] = 'ASC';
        $strandResult = array();
        if(isset($good)){
            $strandResult['good'] = array_slice($good, 0,$strandPerformanceLimit);
        }
        if(isset($poorTemp)){
            $strandResult['poor'] = $poorTemp;
        }*/
        return $strandResult;
    }
    
/*
    public function studentStrandResultListOld($params) {
        $strandGoodthreshold = STRAND_GOOD_THRESHOLD;
        $strandPerformanceLimit = STRAND_PERFORMANCE_LIMIT;
        $params['prderIndex'] = 'DESC';
        //$studentStrandResult = $this->getStudentStrandResult($params)->toArray();
        $studentStrandResult = $this->getStudentSubStrandResult($params)->get();
        $poor = array();
        asd($studentStrandResult);
        foreach ($studentStrandResult as $row) {
            $strand_mark_obtain_persent = round((($row['strand_mark_obtain'] / $row['strand_total_mark']) * 100), 1);
            if ($strand_mark_obtain_persent > 0) {
                $good[] = $row['strand'];
            }else if($strand_mark_obtain_persent == 0){
                $poor[] = $row['strand'];
            }
        }
        if(count($poor) >= $strandPerformanceLimit){
            $poorTemp =  array_slice($poor, 0,$strandPerformanceLimit);;
        }else if(count($poor) == 0 && count($good) > $strandPerformanceLimit){
            $goodTemp = array_slice($good, $strandPerformanceLimit);
            $poorTemp1 = $goodTemp;
            rsort($poorTemp1);
            $poorTemp = array_slice($poorTemp1, 0,$strandPerformanceLimit);
        }else if(count($poor) > 0 && count($poor) < $strandPerformanceLimit){
            $poorTemp2 = array();
            if(count($good) > $strandPerformanceLimit){
                $poorTemp1 = $good;
                rsort($poorTemp1);
                $lim = $strandPerformanceLimit - count($poor);
                $poorTemp2 = array_slice($poorTemp1, 0,$lim);
            }
            $poorTemp = array_merge($poor,$poorTemp2);
        }
        
        $params['prderIndex'] = 'ASC';
        //$studentStrandResult = $this->getStudentStrandResult($params)->toArray();
        //foreach ($studentStrandResult as $row) {
         //   $strand_mark_obtain_persent = round((($row['strand_mark_obtain'] / $row['strand_total_mark']) * 100), 1);
          //  if ($strand_mark_obtain_persent < $strandGoodthreshold) {
           //     $poor[] = $row['strand'];
            //}
        //}
        $strandResult = array();
        if(isset($good)){
            $strandResult['good'] = array_slice($good, 0,$strandPerformanceLimit);
        }
        if(isset($poorTemp)){
            $strandResult['poor'] = $poorTemp;
        }
        return $strandResult;
    }*/

    public function getStudentStrandResult($params) {
        return $this->model
                        ->select(['strand_total_mark', 'strand_mark_obtain', 'strands.strand'])
                        ->join('strands', 'student_strand_meta.strand_id', '=', 'strands.id')
                        ->where(['student_strand_meta.student_id' => $params['student_id'],'student_strand_meta.task_type' => $params['task_type'],'strands.subject' => $params['subject']])
                        ->orderBy('student_strand_meta.strand_mark_obtain', $params['prderIndex'])
                        ->get();
    }
    public function getStudentSubStrandResult($params) {
        return DB::table('student_test_gap_result_data')->select(DB::raw('avg(attempt_per_avg) as attempt_per_avg') ,'substrand_id','strand_id')->where('student_id', '=', $params['student_id'])
                ->where('subject','=',$params['subject'])
                ->groupBy('substrand_id')->orderBy('attempt_per_avg','desc');

    }

    public function lastNattemptAverage($params) {
        if (!empty($params['markobtain_on_each_attempt']) && $params['markattempt_on_each_attempt']) {
            $arr_markobtain_on_each_attempt = explode(",", $params['markobtain_on_each_attempt']);
            $arr_markattempt_on_each_attempt = explode(",", $params['markattempt_on_each_attempt']);
            $cntAttempt = count($arr_markobtain_on_each_attempt);
            $avgFSum = 0;
            if ($cntAttempt == count($arr_markattempt_on_each_attempt)) {
                foreach ($arr_markobtain_on_each_attempt as $key => $value) {
                    $avgFSum = $avgFSum + ($value / $arr_markattempt_on_each_attempt[$key]);
                }
            }
            
            
            
            $avg = round(($avgFSum / $cntAttempt) * 100);
            if ($avg <= 20) {
                $labelClassName = 'qa_red';
            } elseif ($avg > 20 && $avg <= 50) {
                $labelClassName = 'qa_orange';
            } elseif ($avg > 50 && $avg <= 70) {
                $labelClassName = 'qa_light-green';
            } elseif ($avg > 70) {
                $labelClassName = 'qa_blue';
            }
            return array('avg' => $avg, 'avgColor' => $labelClassName);
        }
    }

    public function revisionResultStarRating($params) {
        $per = round(($params['markObtain'] / $params['markAttempt']) * 100, 1);
        if ($per <= 20) {
            $star = 1;
        } elseif ($per > 20 && $per <= 40) {
            $star = 2;
        } elseif ($per > 40 && $per <= 60) {
            $star = 3;
        } elseif ($per > 60 && $per <= 80) {
            $star = 4;
        } elseif ($per > 80) {
           $star = 5;
        }
        return $star;
    }
    
    public function calculateTestAttemptResult($params) {
        $percent = round(((@$params['mark_obtain'] / @$params['total_marks']) * 100), 1);
        $fontClass = $this->getPercentFontCls($percent);
        return array(
            'paper_num' => $params['paper_num'],
            'total_marks' => $params['total_marks'],
            'mark_obtain' => $params['mark_obtain'],
            'percentage_obtained' => $percent,
            'fontCls' => $fontClass
        );
    }
    
     public function getPercentFontCls($percent){
        $cls = '';
        $percent = round($percent);
        if ($percent <= 20) {
            $cls = 'tsRed';
        } elseif ($percent > 20 && $percent <= 50) {
            $cls = 'tsOrange';
        } elseif ($percent > 50 && $percent <= 69) {
            $cls = 'tsGreen';
        } elseif ($percent >= 70) {
            $cls = 'tsBlue';
        }
        return $cls;
    }

}
