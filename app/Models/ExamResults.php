<?php


namespace App\Models;


use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;

class ExamResults extends Model
{
    public $primaryKey = 'id';
    protected $table = 'exam_results';

    protected $returnType = '\App\Entities\ExamResult';

    protected $allowedFields = ['exam', 'student', 'class', 'subject', 'subject_name', 'mark', 'grade', 'remark','not_seated_for_exam'];

    public function getScore($student_id, $exam_id, $class_id)
    {
        $total = $this->selectSum('mark', 'total')->where(['exam' => $exam_id, 'student' => $student_id, 'class' => $class_id])->get()->getLastRow();
        if($total) {
            return $total->total;
        }

        return '-';
    }

    public function getRank($student_id, $exam_id, $class_id)
    {
        $student = (new Students())->find($student_id);

        $total = $this->select('*')->selectSum('mark', 'total')->where(['exam' => $exam_id, 'class' => $class_id])
            ->whereIn('student', function (BaseBuilder $builder) use ($student) {
                return $builder->select('id')
                    ->from('students')
                    ->where('class', $student->class->id)
                    ->where('section', $student->section->id);
            })
            ->groupBy('student')->asArray()->findAll();

        usort($total, function($a, $b) {
            return $a['total'] <=> $b['total'];
        });

        //Change to ASC
        $total = array_reverse($total, FALSE);

        $key = array_search($student_id, array_column($total, 'student'));

        return $key+1;
    }

    public function getResultsAndRank($student_id, $exam)
    {
        $student = (new Students())->find($student_id);
        $subject_ids = array();
        foreach ($student->class->subjects as $sub){
            array_push($subject_ids,$sub->id);
        }

        //Exam exists?
        $exam_exists = (new Exams())->find($exam);

        if(!$exam_exists) return false;

        $studentResults = [];

        $students_arr = array();
        foreach ($student->section->students as $std) {
            foreach ($student->class->subjects as $subject) {
                $result = (new ExamResults())->select('SUM(mark) as subtotal')->where('student', $std->id)->where('class', $std->class->id)
                    ->where('subject', $subject->id)->where('exam', $exam)->get()->getRowObject();

                if (!empty($result)) {
                    if (!isset($students_arr[$std->id])) {
                        $students_arr[$std->id] = $result->subtotal;
                    } else {
                        $students_arr[$std->id] += $result->subtotal;
                    }
                }
            }
        }

        $rank_students = array_rank($students_arr);

        //foreach ($student->class->students as $class_student) {
        foreach ($student->section->students as $class_student) {
           // var_dump($class_student->class->id);
            $other_student_exam_results = $this->where('exam', $exam)->where('student', $class_student->id)->where('class',$class_student->class->id)->whereIn('subject',$subject_ids)->findAll();
         //   echo '<pre>';
           // var_dump($other_student_exam_results);
            if($other_student_exam_results && count($other_student_exam_results) > 0) {
                $ttMarks = 0;
                $labels = [];
                $marks = [];
                foreach ($other_student_exam_results as $exam_result) {
                    $xMark = is_numeric($exam_result->mark) ? $exam_result->mark : 0;
                    $labels[] = $exam_result->subject->name;
                    $marks[] = $xMark;
                    $ttMarks += $xMark;
                }

                $studentResults[] = [
                    'student'   => $class_student->id,
                    'labels'    => $labels,
                    'marks'     => $marks,
                    'total_marks'   => $ttMarks,
                    'average'   => @$ttMarks/(count($labels))
                ];
            }
        }

        //Sort
        usort($studentResults, function ($a, $b) {
            return $a['total_marks'] <=> $b['total_marks'];
        });

        $studentResults = array_reverse($studentResults, FALSE);

        $key = array_search($student->id, array_column($studentResults, 'student'));

        //if(!$key) return false;

        if (!isset($studentResults[$key])) {
            return false;
        }

        $results = $studentResults[$key];
        $results['rank'] = $rank_students[$student_id];
       // $studentResults['rank'] = $rank_students[$student_id];

        return $results;
    }
}