<?php

class SemesterScores
{
 public $semester;
public function __construct($semester)
{
 $this->semester = $semester;
}

    public function setScores()
    {
     $students = getSession()->students;

     foreach ($students as $student){
         $total = 0;
         $subject_count = 0;
         $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
         foreach ($student->class->subjects as $subject){
             $result = $resultsModel->getSemesterTotalResultsPerSubject($this->semester, $subject->id,$student->section->id);
             if ($subject->optional == 0){
                 $subject_count++;
                 if ($result && !empty($result))
                 $total+=$result;
             }
         }
         $average = number_format($total/$subject_count,2);

         $this->insertScores($student->id,$student->class->id,$student->section->id,$total,$average);

     }

    }

    public function setScoresKG()
    {
        $students = getSession()->students;
        foreach ($students as $student){
            $total = 0;
            $subject_count = 0;
            $resultsModel = new \App\Libraries\YearlyResults($student->id, active_session());
            foreach ($student->class->subjects as $subject){
                $subject_count++;
                $result = $resultsModel->getSemesterTotalResultsPerSubject($this->semester, $subject->id,$student->section->id);
                if ($result && !empty($result))
                    $total+=$result;
                $this->insertSubjectScores($student->id,$student->class->id,$student->section->id,$subject->id,$result);
            }
            $average = number_format($total/$subject_count,2);
            $this->insertScores($student->id,$student->class->id,$student->section->id,$total,$average);
        }

    }

    public function insertScores($student,$class,$section,$total,$average){
      $model = new \App\Models\SemesterAnalysis();
      $to_db = array(
          'student' => $student,
          'class' => $class,
          'section' => $section,
          'session' => active_session(),
          'semester' => $this->semester,
          'total_marks' => $total,
          'average' => $average
      );

      $record = (new \App\Models\SemesterAnalysis())->where('student',$student)->where('session',active_session())->where('class',$class)->where("semester",$this->semester)->get()->getRow();
    if ($record)
        $to_db['id'] = $record->id;

      $model->save($to_db);
}

    public function insertSubjectScores($student,$class,$section,$subject,$result){
        $model = new \App\Models\SubjectAnalysis();

        //perform grading
        $res = (new \App\Models\ClassSubjects())->find($subject);
        $mygrade =  '-';
        if (!empty($res->grading) && $result) {
            $grade = json_decode($res->grading);
            foreach ($grade as $g) {
                $item = explode('-', $g->scale);
                if ($result >= min($item) && $result <= max($item)) {
                    $mygrade = $g->grade.'('.$result.')';
                    break;
                }
            }
        }

        $to_db = array(
            'student' => $student,
            'class' => $class,
            'section' => $section,
            'subject' => $subject,
            'session' => active_session(),
            'semester' => $this->semester,
            'total' => (isset($result) && !empty($result)) ?  $result : 0,
            'grade' =>  $res->optional ==1 ? $mygrade :'-'
        );

        $record = (new \App\Models\SubjectAnalysis())->where('student',$student)->where('session',active_session())->where('class',$class)->where("semester",$this->semester)->where('subject',$subject)->get()->getRow();
        if ($record)
            $to_db['id'] = $record->id;
        $model->save($to_db);
    }

    public function setRanks($class)
    {
     $scores = (new \App\Models\SemesterAnalysis())->where('class',$class)->where('semester',$this->semester)->where("session",active_session())->get()->getResult();
     $students = array();
     foreach ($scores as $score){
         if (!isset($students[$score->student])){
          $students[$score->student] = $score->total_marks;
         }
     }
     $student_ranks = array_rank($students);

     foreach ($student_ranks as $key => $val){
         $this->insertRanks($key,$val);
     }
    }

    public function setYearlyRanking($class){
        $students = (new \App\Models\Students())->where("class",$class)->findAll();
        $scores = array();
        foreach ($students as $student){
            $record = (new \App\Models\SemesterAnalysis())->where("session",active_session())->where("student",$student->id)->where("class",$class)->selectSum("total_marks","total")->get()->getRow();
          if (!isset($scores[$student->id])){
            $scores[$student->id] = $record->total;
          }
        }
        $student_ranks = array_rank($scores);

        foreach ($student_ranks as $key => $val){
            $this->insertRanksYearly($key,$val,$class);
        }

    }

    public function insertRanks($student,$rank)
    {
    $model = new \App\Models\SemesterRanking();
    $student = (new \App\Models\Students())->find($student);
    $to_db = array(
       'student' => $student->id,
       'class' => $student->class->id,
       'section' => $student->section->id,
       'semester' => $this->semester,
       'session'=> active_session(),
       'rank' => $rank
    );
    $record = (new \App\Models\SemesterRanking())->where('student',$student->id)->where('session',active_session())->where("semester",$this->semester)->get()->getRow();
    if($record)
        $to_db['id'] = $record->id;
    $model->save($to_db);
    }
    public function insertRanksYearly($student,$rank,$class)
    {
    $model = new \App\Models\YearlyRanking();
    $student = (new \App\Models\Students())->find($student);
        $res = (new \App\Models\SemesterAnalysis())->where("session",active_session())->where("student",$student->id)->selectSum("total_marks","total")->selectSum("average","avg")->get()->getRow();
     $to_db = array(
       'student' => $student->id,
       'class' => $class,
       'section' => $student->section->id,
       'session'=> active_session(),
       'total' => $res->total,
       'average' => $res->avg/2,
       'rank' => $rank
    );

    $record = (new \App\Models\YearlyRanking())->where('student',$student->id)->where('session',active_session())->get()->getRow();
    if($record)
        $to_db['id'] = $record->id;
    $model->save($to_db);
    }

    public function getSubjectTotal($student,$subject){
        $result = (new \App\Models\SubjectAnalysis())->where("session",active_session())->where("semester",$this->semester)->where("student",$student)->where("subject",$subject)->get()->getRow();
     return $result ? $result->total : '-';
    }
    public function getSubjectGrade($student,$subject){
        $result = (new \App\Models\SubjectAnalysis())->where("session",active_session())->where("semester",$this->semester)->where("student",$student)->where("subject",$subject)->get()->getRow();
        return $result ? $result->grade : '-';
    }

    public function getSemesterTotal($student)
    {
        $result = (new \App\Models\SemesterAnalysis())->where("session",active_session())->where("semester",$this->semester)->where("student",$student)->get()->getRow();
        return $result ? $result->total_marks : '-';
    }

    public function getSemesterAverage($student)
    {
        $result = (new \App\Models\SemesterAnalysis())->where("session",active_session())->where("semester",$this->semester)->where("student",$student)->get()->getRow();
        return $result ? $result->average : '-';
    }

    public function getSemesterRank($student){
        $result = (new \App\Models\SemesterRanking())->where("session",active_session())->where("semester",$this->semester)->where("student",$student)->get()->getRow();
        return $result ? $result->rank : '-';
    }

    public function getYearlyTotal($student)
    {
        $result = (new \App\Models\YearlyRanking())->where("session",active_session())->where("student",$student)->get()->getRow();
        return $result ? $result->total : '-';
    }
    public function getYearlyAverage($student)
    {
        $result = (new \App\Models\YearlyRanking())->where("session",active_session())->where("student",$student)->get()->getRow();
        return $result ? $result->average : '-';
    }
    public function getYearlyRank($student)
    {
        $result = (new \App\Models\YearlyRanking())->where("session",active_session())->where("student",$student)->get()->getRow();
        return $result ? $result->rank : '-';
    }
}