<?php

use App\Models\Departure;
use App\Models\Students;

function active_session() {
    if($res = \Config\Database::connect()->table('sessions')->where('active', 1)->get()->getRowObject() ) {
        return $res->id;
    }

    return false;
}

function is_quarter_session($session='') {
    if ($session =='')
        $session = active_session();
    if($res = \Config\Database::connect()->table('sessions')->where('id',$session)->get()->getRow() ) {
        if ($res->session_type > 0)
            return true;
        return false;
    }

    return false;
}

function is_quarter_plus_session() {
    if($res = \Config\Database::connect()->table('sessions')->where('id',active_session())->get()->getRow() ) {
        if ($res->session_type == 2)
            return true;
        return false;
    }

    return false;
}
function boysDeparture($class,$sem){
    $counter = 0;
    $students = (new Departure())->where('class', $class)->where("semester",$sem)->where('count',1)->findAll();
    foreach ($students as $departureStudent){
        $student = (new Students())->find($departureStudent->student);
        if ($student->profile->gender =='Male')
            $counter++;
    }
    return $counter;
}

function girlsDeparture($class,$sem){
    $counter = 0;
    $students = (new Departure())->where('class', $class)->where("semester",$sem)->where('count',1)->findAll();
    foreach ($students as $departureStudent){
        $student = (new Students())->find($departureStudent->student);
        if ($student->profile->gender =='Female')
            $counter++;
    }
    return $counter;
}
function is_director_sign($student){
    $sign  = (new \App\Models\DirectorSign())->where('student',$student)->where('session',active_session())->get()->getRow();
    if ($sign)
        return true;
    return false;
}

function is_teacher_sign($student){
    $sign  = (new \App\Models\Homeroom())->where('student',$student)->where('session',active_session())->get()->getRow();
    if ($sign)
        return true;
    return false;
}


function is_homeroom_sign_sem1($student){
    $sign  = (new \App\Models\Homeroom())->where('student',$student)->where('session',active_session())->where("first_sem_sign !=",0)->where("first_sem_sign !=",null)->get()->getRow();
    if ($sign)
        return true;
    return false;
}

function is_homeroom_sign_sem2($student){
    $sign  = (new \App\Models\Homeroom())->where('student',$student)->where('session',active_session())->where("second_sem_sign !=",0)->where("second_sem_sign !=",null)->get()->getRow();
    if ($sign)
        return true;
    return false;
}

function isMobile(){
    $useragent=$_SERVER['HTTP_USER_AGENT'];
    return (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)));
}

function active_session_year() {
    if($res = \Config\Database::connect()->table('sessions')->where('active', 1)->get()->getRowObject() ) {
        return $res;
    }

    return false;
}

function getTeacherStudents($teacher_id){
    $classes = (new \App\Models\Subjectteachers())->groupBy('class_id')->where('teacher_id',$teacher_id)->findAll();
    $class_ids = array();
    foreach ($classes as $class){
        $class_ids[$class->class_id] = $class->class_id;
    }
    return (new \App\Models\Students())->whereIn('class',$class_ids)->findAll();
}

function departedParentIds(){
    $departures = (new \App\Models\Departure())->where('type','departure')->where('session',active_session())->findAll();
    $ids = array();
    foreach ($departures as $d){
        $student = (new \App\Models\Students())->find($d->student);
        $parent = (new \App\Models\Parents())->find($student->parent->id);
        if (count($parent->students) == 1){
          array_push($ids,$parent->id);
        }
    }
    return $ids;

}

function departedIds(){
    $departures = (new \App\Models\Departure())->where('type','departure')->where('session',active_session())->findAll();
    $ids = array_column($departures,'student');
    return $ids;

}
/**
 * Get school session information
 *
 * @param bool $id Session ID, empty or false for current session
 * @return array|object|null|bool
 */
function getSession($id = FALSE) {
    if(!$id) $id = active_session();

    if(!$id) return false;

    return (new \App\Models\Sessions())->find($id);
}
function getNextClass($class){
 $class_max = (new \App\Models\Classes())->selectMax("weight")->where("session",active_session())->first();

 $current_class = (new \App\Models\Classes())->find($class);

 if ($current_class->weight < $class_max->weight){
     $weight = $current_class->weight+1;
     for ($i=$weight;$i<20; $i++){
         $record = (new \App\Models\Classes())->where("session",active_session())->where("weight",$i)->get()->getRow();
         if (!empty($record))
             return $record->name;
     }
 }
 return $current_class->name;
}
function getStudent($id) {

    return (new \App\Models\Students())->find($id)->profile->name;
}
function getStudentsResults($semester){
    $classes = @getSession()->classes->findAll();
    $students_arr = array();
    if (count($classes) > 0){
        foreach ($classes as $class){
            foreach ($class->students as $student) {
                if ($student->class) {
                    $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
                    $subjects = $student->class->subjects;
                    if (count($subjects) > 0) {
                        foreach ($subjects as $subject) {
                            $result = $resultsModel->getSemesterTotalResultsPerSubject($semester, $subject->id);
                            //$result = 90;

                            if (!empty($result) && is_numeric($result)) {
                                if (!isset($students_arr[$student->id])) {
                                    $students_arr[$student->id] = $subject->optional == 0 ? $result : 0;
                                } else {
                                    $students_arr[$student->id] += $subject->optional == 0 ? $result : 0;
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    return $students_arr;
}
function getSection($student_id) {

    return (new \App\Models\Students())->find($student_id)->section->name;
}

function getDepartedIds(){
    $ids = [];
    $departed_students = (new \App\Models\Departure())->select("student")->where("session !=",active_session())->groupBy("student")->findAll();

    //var_dump($departed_students);
   // exit();
    foreach ($departed_students as $student){
        $ids[] = $student->student;
    }
    return $ids;
}

function getUserId(){
    return (new \App\Libraries\IonAuth())->getUserId();
}
function isSuperAdmin(){
  //  return getUserId() == get_option('superadmin');
    return getUserId() == 1;
}
function checkSuperadmin($user_id){
//    return $user_id == get_option('superadmin');
    return $user_id == 1;
}
function array_rank( $in ) {
    # Keep input array "x" and replace values with rank.
    # This preserves the order. Working on a copy called $x
    # to set the ranks.
    $x = $in; arsort($x);
    # Initival values
    $rank       = 0;
    $hiddenrank = 0;
    $hold = null;
    foreach ( $x as $key=>$val ) {
        # Always increade hidden rank
        $hiddenrank += 1;
        # If current value is lower than previous:
        # set new hold, and set rank to hiddenrank.
        if ( is_null($hold) || $val < $hold ) {
            $rank = $hiddenrank; $hold = $val;
        }
        # Set rank $rank for $in[$key]
        $in[$key] = $rank;
    }
    return $in;

}

function array_flatten($array) {
    $return = array();
    foreach ($array as $key => $value) {
        if (is_array($value)){
            $return = array_merge($return, array_flatten($value));
        } else {
            $return[$key] = $value;
        }
    }

    return $return;
}

function formatAdmissionNumber($number) {
//    if(strlen($number) < 3) {
//        $t = (3 - strlen($number));
//        $temp = '';
//        for ($i = 0; $i < $t; $i++) {
//            $temp .= '0';
//        }
//    }
    $number = str_pad($number, 3, '0', STR_PAD_LEFT);
   $school = get_option('school_name');

   if ($school =='pannation'){
       $number = 'PN/'.$number;
   }elseif ($school =='aspire'){
       $number = 'AYA/'.$number;
   }else{
       $number = 'AYA/'.$number;
   }

    return $number;
}
function formatTeacherNumber($number) {
//    if(strlen($number) < 3) {
//        $t = (3 - strlen($number));
//        $temp = '';
//        for ($i = 0; $i < $t; $i++) {
//            $temp .= '0';
//        }
//    }
    $number = str_pad($number, 3, '0', STR_PAD_LEFT);
    $school = get_option('school_name');

    if ($school =='pannation'){
        $number = 'PN/TEACHER/'.$number;
    }  elseif ($school =='aspire'){
       $number = 'AYA/TEACHER/'.$number;
    }else{
     $number = 'AYA/TEACHER/'.$number;
    }

    return $number;
}

function fee_currency($amount = 0) {

    return @get_option('currency', 'Kshs').' '.number_format($amount, 2);
}

function email_domain($link = '') {
    //return base_url();
    return 'localhost.com';
}

function getMonthWeekNumber($date, $rollover = 'sunday')
{
//    $cut = substr($date, 0, 8);
//    $daylen = 86400;
//
//    $timestamp = strtotime($date);
//    $first = strtotime($cut . "00");
//    $elapsed = ($timestamp - $first) / $daylen;
//
//    $weeks = 1;
//
//    for ($i = 1; $i <= $elapsed; $i++)
//    {
//        $dayfind = $cut . (strlen($i) < 2 ? '0' . $i : $i);
//        $daytimestamp = strtotime($dayfind);
//
//        $day = strtolower(date("l", $daytimestamp));
//
//        if($day == strtolower($rollover))  $weeks ++;
//    }

    //return $weeks > 4 ? 4 : $weeks;
//    return $weeks;
    $time = strtotime($date); // or whenever
    $week_of_the_month = ceil(date('d', $time)/7);

    return $week_of_the_month;
}

/**
 * @param $student
 * @param $subject
 * @param $semester
 * @return Result analysis
 */
function getSemesterSubjectTotal($student,$subject,$semester){
   $model = new SemesterScores($semester);
   return $model->getSubjectTotal($student,$subject);
}

function getSemesterSubjectGrade($student,$subject,$semester){
   $model = new SemesterScores($semester);
   return $model->getSubjectGrade($student,$subject);
}

function getSemesterTotal($student,$semester){
    $model = new SemesterScores($semester);
    return $model->getSemesterTotal($student);
}

function getSemesterAverage($student,$semester){
    $model = new SemesterScores($semester);
    return $model->getSemesterAverage($student);
}

function getSemesterRank($student,$semester){
    $model = new SemesterScores($semester);
    return $model->getSemesterRank($student);
}
function getYearlyRank($student){
    $model = new SemesterScores(1);
    return $model->getYearlyRank($student);
}

function getYearlyAverage($student){
    $model = new SemesterScores(1);
    return $model->getYearlyAverage($student);
}

function getYearlyTotal($student){
    $model = new SemesterScores(1);
    return $model->getYearlyTotal($student);
}