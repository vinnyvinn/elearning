<?php

use Config\Database;

function get_option($key, $default = FALSE)
{

    $db = Database::connect();
    $result = $db->table('options')->getWhere(['meta_key' => $key])->getRow();

    if (isset($result->meta_value)) {
        return $result->meta_value;
    }

    return $default;
}

function get_logo(){
//    $file = get_option( 'website_logo');
//    $path = $file ? 'uploads/files/' . $file : 'assets/images/logo.jpeg';
//    $type = pathinfo($path, PATHINFO_EXTENSION);
//    $data = file_get_contents($path);
//    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
//    return $base64;

    if( isset($file) && @$file != '') {
        $path = 'uploads/avatars/'.$file;
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    } else {
        $path = 'assets/images/logo.jpeg';
        $type = pathinfo($path, PATHINFO_EXTENSION);
        $data = file_get_contents($path);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
        return $base64;
    }
    return null;
}

function getEvaluation($data,$id,$sem,$key,$value){
    if (empty($data))
        return false;

    foreach ($data as $item){
        if ($item->id == $id && $item->sem == $sem && $item->key == $key && $item->value == $value){
            return true;
        }
    }
    return false;
}

function get_time_ago($time)
{
    $time_difference = time() - $time;

    if( $time_difference < 1 ) { return 'less than 1 second ago'; }
    $condition = array( 12 * 30 * 24 * 60 * 60 =>  'year',
        30 * 24 * 60 * 60       =>  'month',
        24 * 60 * 60            =>  'day',
        60 * 60                 =>  'hour',
        60                      =>  'minute',
        1                       =>  'second'
    );

    foreach( $condition as $secs => $str )
    {
        $d = $time_difference / $secs;

        if( $d >= 1 )
        {
            $t = round( $d );
            return 'about ' . $t . ' ' . $str . ( $t > 1 ? 's' : '' ) . ' ago';
        }
    }
}
function getEvaluationItem($data,$id,$sem,$key){
    if (empty($data))
        return 'N/A';

    foreach ($data as $item){
        if ($item->id == $id && $item->sem == $sem && $item->key == $key){
            if ($item->value =='V')
                return 'V.G';
            return $item->value;
        }
    }
    return 'N/A';
}
function getScore($grading,$mark){
 if (empty($grading))
     return 'N/A';
 foreach ($grading as $g){
     $item = explode('-', $g->scale);
     if ($mark >= min($item) && $mark <= max($item)) {
         return $g->grade.'('.$mark.')';
     }
 }
 return $mark;
}

function getScore2($grading,$mark){
    if (empty($grading))
        return 'N/A';
    foreach ($grading as $g){
        $item = explode('-', $g->scale);
        if ($mark >= min($item) && $mark <= max($item)) {
            return $g->grade;
        }
    }
}


function studentTotalScores($student_id,$sem){
    $student = (new \App\Models\Students())->find($student_id);
    $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
    $subjects = $student->class->subjects;
    $total = 0;

    foreach ($subjects as $subj) {
        $res = $resultsModel->getSemesterTotalResultsPerSubject($sem, $subj->id);
        $total +=$res?:0;
    }
    return $total;
}

function studentRank($students,$student_id,$sem){
    $student_arr_ =  array();
    foreach ($students as $student) {
        $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
        $subjects = $student->class->subjects;

        foreach ($subjects as $subj) {
                $res = $resultsModel->getSemesterTotalResultsPerSubject($sem, $subj->id);
                    if (!isset($student_arr_[$student->id])) {
                        $student_arr_[$student->id] = $res?: 0;
                    } else {
                        $student_arr_[$student->id] += $res?: 0;
                    }
        }
    }

    return array_rank($student_arr_)[$student_id];

}
function studentRankYearly($students,$student_id,$sem1,$sem2){
    $student_arr_ =  array();
    foreach ($students as $student) {
        $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
        $subjects = $student->class->subjects;

        foreach ($subjects as $subj) {
            $res1 = $resultsModel->getSemesterTotalResultsPerSubject($sem1, $subj->id);
            $res2 = $resultsModel->getSemesterTotalResultsPerSubject($sem2, $subj->id);
            if (!isset($student_arr_[$student->id])) {
                $res1 = $res1?:0;
                $res2 = $res2?:0;
                $output = $res1+$res2;
                $student_arr_[$student->id] = $output;
            } else {
                $res1 = $res1?:0;
                $res2 = $res2?:0;
                $output = $res1+$res2;
                $student_arr_[$student->id] += $output;
            }
        }
    }

    return array_rank($student_arr_)[$student_id];

}
function studentResult($students,$sem1=null,$sem2=null){
    $data = array();
    $result=array();
    $sem1_rank = array();
    $sem2_rank = array();
    $yearly_rank = array();

    foreach ($students as $student) {
        $sem1_scores = array();
        $sem2_scores = array();
        $yearly_scores = array();
        $sem1_total = 0;
        $sem2_total = 0;
        $yearly_total = 0;
        $subject_count =0;

        $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
        $subjects = $student->class->subjects;

        foreach ($subjects as $subj) {
            $subject_count++;
            $res1 = $resultsModel->getSemesterTotalResultsPerSubject($sem1, $subj->id);
            $res2 = $resultsModel->getSemesterTotalResultsPerSubject($sem2, $subj->id);
            $res_sem1 = 0;
            $res_sem2 = 0;

            if ($subj->optional == 1) {
                $res = (new \App\Models\ClassSubjects())->find($subj->id);
                if (!empty($res->grading) && $res1) {
                    $grade = json_decode($res->grading);
                    foreach ($grade as $g) {
                        $item = explode('-', $g->scale);
                        if ($res1 >= min($item) && $res1 <= max($item)) {
                            $res_sem1 = $g->grade;
                            break;
                        }

                    }
                }else{
                    $res_sem1 = '-';
                }

                if (!empty($res->grading) && $res2) {
                    $grade = json_decode($res->grading);
                    foreach ($grade as $g) {
                        $item = explode('-', $g->scale);
                        if ($res2 >= min($item) && $res2 <= max($item)) {
                            $res_sem2 = $g->grade;
                            break;
                        }

                    }
                }else{
                    $res_sem2 = '-';
                }
            }else {
                $res_sem1 = $res1;
                $res_sem2 = $res2;
            }
            $yr1 = is_numeric($res_sem1) ? $res_sem1 : '';
            $yr2 = is_numeric($res_sem2) ? $res_sem2 : '';

            array_push($sem1_scores,$res_sem1);
            array_push($sem2_scores,$res_sem2);
            array_push($yearly_scores,($yr1+$yr2)/2);

            $sem1_total += $yr1;
            $sem2_total += $yr2;
            $yearly_total += ($yr1 + $yr2)/2;

            if (!isset($yearly_rank[$student->id])) {
                $output = $yr1+$yr2;
                $yearly_rank[$student->id] = $output;
            } elseif (isset($yearly_rank[$student->id])) {
                $output = $yr1+$yr2;
                $yearly_rank[$student->id] += $output;
            }
            if (!isset($sem1_rank[$student->id])){
                $sem1_rank[$student->id] = $yr1;
            }elseif (isset($sem1_rank[$student->id])) {
              $sem1_rank[$student->id] += $yr1;
            }
            if (!isset($sem2_rank[$student->id])){
                $sem2_rank[$student->id] = $yr2;
            }elseif (isset($sem2_rank[$student->id])) {
                $sem2_rank[$student->id] += $yr2;
            }
        }

         if (!isset($data[$student->id])){
            $data[$student->id] = array(
                'sem1_scores'=>$sem1_scores,'sem2_scores'=>$sem2_scores,'sem1_total'=>$sem1_total,'sem2_total'=>$sem2_total,'yearly_scores'=>$yearly_scores,'yearly_total'=>$yearly_total,
                'sem1_av'=>number_format(($sem1_total/$subject_count),2),'sem2_av'=>number_format(($sem2_total/$subject_count),2),
                'yearly_av'=>number_format((($sem1_total/$subject_count)+($sem2_total/$subject_count))/2,2));
        }
    }
    foreach ($data as $key => $val) {
        if (!isset($result[$key])) {
            $result[$key] = [
                'I' => ['scores' => $val['sem1_scores'], 'total' => $val['sem1_total'], 'average' => $val['sem1_av'], 'rank' => array_rank($sem1_rank)[$key]],
                'II' => ['scores' => $val['sem2_scores'], 'total' => $val['sem2_total'], 'average' => $val['sem2_av'], 'rank' => array_rank($sem2_rank)[$key]],
                'AVE.' => ['scores' => $val['yearly_scores'], 'total' => $val['yearly_total'], 'average' => $val['yearly_av'], 'rank' => array_rank($yearly_rank)[$key]],
            ];
        }
    }
    return $result;
}

function kgStudentResult($students,$sem1=null,$sem2=null){
    $grading = (new \App\Models\Classes())->find($students[0]->class->id);
    $grading = $grading->grading ? json_decode($grading->grading) : [];
    $data = array();
    $result=array();
    $sem1_rank = array();
    $sem2_rank = array();
    $yearly_rank = array();

    foreach ($students as $student) {
        $sem1_scores = array();
        $sem2_scores = array();
        $yearly_scores = array();
        $sem1_total = 0;
        $sem2_total = 0;
        $yearly_total = 0;
        $subject_count =0;

        $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
        $subjects = $student->class->subjects;

        foreach ($subjects as $subj) {
            $subject_count++;
            $res1 = $resultsModel->getSemesterTotalResultsPerSubject($sem1, $subj->id);
            $res2 = $resultsModel->getSemesterTotalResultsPerSubject($sem2, $subj->id);
            $res_sem1 = getScore($grading,$res1);
            $res_sem2 = getScore($grading,$res2);
            $res_yr = getScore($grading,($res1+$res2)/2);

            array_push($sem1_scores,$res_sem1);
            array_push($sem2_scores,$res_sem2);
            array_push($yearly_scores,$res_yr);

            $sem1_total += $res1;
            $sem2_total += $res2;
            $yearly_total += ($res1 + $res2)/2;

            if (!isset($yearly_rank[$student->id])) {
                $output = $res1 + $res2;
                $yearly_rank[$student->id] = $output;
            } elseif (isset($yearly_rank[$student->id])) {
                $output = $res1 + $res2;
                $yearly_rank[$student->id] += $output;
            }
            if (!isset($sem1_rank[$student->id])){
                $sem1_rank[$student->id] = $res1;
            }elseif (isset($sem1_rank[$student->id])) {
                $sem1_rank[$student->id] += $res1;
            }
            if (!isset($sem2_rank[$student->id])){
                $sem2_rank[$student->id] = $res2;
            }elseif (isset($sem2_rank[$student->id])) {
                $sem2_rank[$student->id] += $res2;
            }
        }
        
        if (!isset($data[$student->id])){
            $data[$student->id] = array(
                'sem1_scores'=>$sem1_scores,'sem2_scores'=>$sem2_scores,'sem1_total'=>$sem1_total,'sem2_total'=>$sem2_total,'yearly_scores'=>$yearly_scores,'yearly_total'=>$yearly_total,
                'sem1_av'=>getScore($grading,($sem1_total/$subject_count)),'sem2_av'=>getScore($grading,($sem2_total/$subject_count)),
                'yearly_av'=>getScore($grading,(($sem1_total/$subject_count)+($sem2_total/$subject_count))/2));
        }
    }
    foreach ($data as $key => $val) {
        if (!isset($result[$key])) {
            $result[$key] = [
                'I' => ['scores' => $val['sem1_scores'], 'total' => $val['sem1_total'], 'average' => $val['sem1_av'], 'rank' => array_rank($sem1_rank)[$key]],
                'II' => ['scores' => $val['sem2_scores'], 'total' => $val['sem2_total'], 'average' => $val['sem2_av'], 'rank' => array_rank($sem2_rank)[$key]],
                'AVE.' => ['scores' => $val['yearly_scores'], 'total' => $val['yearly_total'], 'average' => $val['yearly_av'], 'rank' => array_rank($yearly_rank)[$key]],
            ];
        }
    }
    return $result;
}
function studentAverage($student_id,$sem){
    $student = (new \App\Models\Students())->find($student_id);
    $subjects = $student->class->subjects;

    $average = studentTotalScores($student_id,$sem)/count($subjects);
    return number_format($average,2);
}

function studentAverageYearly($student_id,$sem1,$sem2){
      $average = studentAverage($student_id,$sem1) + studentAverage($student_id,$sem2);
    return $average/2;
}
function newHash($password){
    $opts03 = [ "cost" => 15 ];
    $hashed = password_hash($password, PASSWORD_BCRYPT, $opts03);
    return $hashed;
}

function studentInfo($student,$month,$year){
    $last_day = cal_days_in_month(CAL_GREGORIAN,$month,$year);
    $from = strtotime($month.'/'.'01/'.$year);
    $to = strtotime($month.'/'.$last_day.'/'.$year);

    $att = (new \App\Models\Attendance())
        ->where('timestamp >=',$from)->where('timestamp <=',$to)->where("session",active_session())->where('student', $student)->groupBy("timestamp")->findAll();

    $present =0; $absent=0; $sick=0; $late=0; $perm=0;
    foreach ($att as $item){
        if ($item['status'] == 1)
            $present++;
        if ($item['status'] == 0)
            $absent++;
        if ($item['status'] == 2)
            $perm++;
        if ($item['status'] == 3)
            $sick++;
        if ($item['status'] == 4)
            $late++;
    }

    return array('present'=>$present,'absent'=>$absent,'permission'=>$perm,'sick'=>$sick,'late'=>$late);
}

function teacherInfo($teacher,$month,$year){
    $last_day = cal_days_in_month(CAL_GREGORIAN,$month,$year);
    $from = strtotime($month.'/'.'01/'.$year);
    $to = strtotime($month.'/'.$last_day.'/'.$year);

    $att = (new \App\Models\Attendance())
        ->where('timestamp >=',$from)->where('timestamp <=',$to)->where('teacher', $teacher)->groupBy("timestamp")->findAll();

    $present =0; $absent=0; $sick=0; $late=0; $perm=0;
    foreach ($att as $item){
        if ($item['status'] == 1)
            $present++;
        if ($item['status'] == 0)
            $absent++;
        if ($item['status'] == 2)
            $perm++;
        if ($item['status'] == 3)
            $sick++;
        if ($item['status'] == 4)
            $late++;
    }

    return array('present'=>$present,'absent'=>$absent,'permission'=>$perm,'sick'=>$sick,'late'=>$late);
}

function getMonthName($monthNum){
    $monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
    return $monthName;
}
function key_option_exists($key) {
    $db = Database::connect();
    $result = $db->table('options')->where(['meta_key' => $key])->countAllResults();
    if($result > 0) {
        return true;
    }
    return false;
}

function set_option($key, $value = '')
{
    $db = Database::connect();
    $builder = $db->table('options');
    if (key_option_exists($key)) {
        $builder->where(['meta_key' => $key, 'meta_parent' => NULL])->update(['meta_value' => $value]);
    } else {
        @$builder->insert(['meta_key' => $key, 'meta_value' => $value]);
    }
    return true;
}
function limit_str_by30($ur_str){
   return (strlen($ur_str) > 30) ? substr($ur_str,0,200).'...' :$ur_str;
}
function update_option($key, $value = '')
{
    return set_option($key, $value);
}

function get_parent_option($parent, $key, $default = FALSE)
{
    $db = Database::connect();
    $result = $db->table('options')->getWhere(['meta_parent' => $parent, 'meta_key' => $key])->getRow();
    if (isset($result->meta_value)) {
        return $result->meta_value;
    }

    return $default;
}

function set_parent_option($parent, $key, $value = '')
{
    $db = Database::connect();
    $builder = $db->table('options');
    if (key_option_exists($key)) {
        $builder->where(['meta_key' => $key, 'meta_parent' => $parent])->update(['meta_value' => $value]);
    } else {
        @$builder->insert(['meta_parent' => $parent, 'meta_key' => $key, 'meta_value' => $value]);
    }
    return true;
}

function update_parent_option($parent, $key, $value = '')
{
    return set_parent_option($parent, $key, $value);
}