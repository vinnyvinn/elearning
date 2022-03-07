<?php


namespace App\Libraries;


use App\Models\AssessmentResults;
use App\Models\ExamResults;
use App\Models\Exams;
use App\Models\ManualAssessments;
use App\Models\Quarters;
use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Students;
use CodeIgniter\Database\MySQLi\Connection;

class YearlyResults
{
    /**
     * @var array|object|null
     */
    public $semester;
    public $quarter;
    private $student_id;
    /**
     * @var bool|int
     */
    private $session_id;
    /**
     * @var array|object|null
     */
    private $student;
    /**
     * @var array|bool|object|null
     */
    private $session;

    public function __construct($student, $session = false)
    {
        $this->student_id = $student;
        $this->student = (new Students())->find($student);
        $this->session_id = $session ?:active_session();
        $this->session = getSession($this->session_id);
    }

    public function getSemesterContinuousAssessment($semester, $subject = false)
    {
        $this->semester = (new Semesters())->find($semester);
        //Total = All Semester Exams + All CATs
        $model = (new AssessmentResults())
            ->select('*, SUM(score) as total_score, SUM(out_of) as total_out_of')
            ->where('session', $this->session_id)
            ->where('semester', $semester)
            ->where('student', $this->student_id);
        //->groupBy('student')
        if ($subject) {
            $model->where('subject', $subject);

            return $model->first();
        }

        $results = $model->findAll();
        //This semester

        return $results;
        //$results : Assessment results for each student subject
    }

    public function getSemesterRank($semester)
    {
        $subjects = $this->student->class->subjects;
        //$students = $this->student->class->students;
        $students = $this->student->section->students;
        $student_marks = [];
        if ($students && is_array($students) && count($students) > 0) {
            foreach ($students as $student) {
                $total_marks = 0;
                $n = 0;
                $lib = new self($student->id);
                foreach ($subjects as $subject) {
                    $n++;
                    $result = $lib->getSemesterTotalResultsPerSubject($semester, $subject->id);
                    if ($result && !empty($result)) {

                    } else {
                        $result = 0;
                    }
                    $total_marks += $result;
                }
                $student_marks[] = [
                    'student' => $student->id,
                    'marks' => ($n > 0 && $total_marks > 0) ? $total_marks : 0
                ];
            }

            //Sort students by marks
            usort($student_marks, function ($a, $b) {
                return $a['marks'] - $b['marks'];
            });

            $student_marks = array_reverse($student_marks, false);

            $key = array_search($this->student->id, array_column($student_marks, 'student'));

            return $key + 1;
        } else {
            return '1';
        }

        return $student_marks;
    }

    public function getSemesterTotalResultsPerSubject($semester, $subject,$section=null)
    {

        //$ca is a single entry
        $ca = $this->getSemesterManualContinuousAssessment($semester, $subject);
        //$exam total
        $total = $this->getSemesterExams($semester, $subject)?:0;
//        if ($ca->converted_total == 0) {
//            $result = @json_decode($ca->results);
//            $ca_total = 0;
//            if (is_array($result) && count($result) > 0) {
//                foreach ($result as $item) {
//                    $ca_total += $item;
//                }
//            }
//        } else {
//            $ca_total = $ca->converted_total;
     //   }

        $total = $total + $ca;

        return number_format($total, 2);
    }


    public function getSemesterTotalResultsPerSubjectClass($semester,$class)
    {

        //$ca total
        $ca_total = $this->getSemesterManualContinuousAssessmentByClass($semester);

        //$exam total
         $exam_total = $this->getSemesterExamsClass($semester,$class);

        $total = $exam_total + $ca_total;

        return number_format($total, 2);
    }
    public function getQuarterTotalResultsPerSubject($quarter, $subject,$section=null)
    {
        //$ca total
        $ca_total = $this->getQuarterManualContinuousAssessment($quarter, $subject);

        //$exam total
        $exam_total = $this->getQuarterExams($quarter, $subject);

        $total =  $exam_total + $ca_total;

        return number_format($total, 2);
    }

    public function getSemesterManualContinuousAssessment($semester, $subject = false,$section=null)
    {
//        $db = \Config\Database::connect();
//        $builder = $db->query("CALL Passessments($this->session_id,$semester,$this->student_id,$subject)");
//        return isset($builder->getRow()->total_score) ? $builder->getRow()->total_score : 0;
         $result = $this->getSemesterManualAssessment($semester,$subject);
        return $result ? $result->converted : '';
    }

    public function getSemesterManualAssessment($semester,$subject)
    {

        $result = new ManualAssessments();
        $result->select("SUM(converted_total) as converted,SUM(total) as total_score")
        ->where('semester',$semester)
        ->where('subject',$subject)
        ->where('student',$this->student_id);

        return $result->get()->getRow();

    }


    public function getSemesterManualContinuousAssessmentByClass($semester)
    {
        $db = \Config\Database::connect();
        $builder = $db->query("CALL PQuarterassessmentsByClass($this->session_id,$semester,$this->student_id)");
        return isset($builder->getRow()->total_score) ? $builder->getRow()->total_score : 0;
    }

    public function getExamResults($semester)
    {
        $db = \Config\Database::connect();
        $builder = $db->query("CALL PExamResult($this->session_id,$semester,$this->student_id)");
        return isset($builder->getRow()->total) ? $builder->getRow()->total : 0;
    }
    public function getExamSubjectResults($semester,$subject)
    {
        $db = \Config\Database::connect();
        $builder = $db->query("CALL PExamSubjectResult($this->session_id,$semester,$this->student_id,$subject)");
        return isset($builder->getRow()->total) ? $builder->getRow()->total : 0;
    }

    public function getQuarterManualContinuousAssessment($quarter, $subject = false,$section=null)
    {
        $db = \Config\Database::connect();
        $builder = $db->query("CALL PQuarterassessments($this->session_id,$quarter,$this->student_id,$subject)");
        return isset($builder->getRow()->total_score) ? $builder->getRow()->total_score : 0;

    }
    public function getSemesterExams($semester, $subject = false)
    {
        //Get semester exams result score
        $db = \Config\Database::connect();
        $builder = $db->query("CALL Pexamsids($this->session_id,$semester)");
        $res = $builder->getRow()->ids;

        $resultsModel = new ExamResults();
        $ids = explode(',',$res);

        $resultsModel->select("id")
            ->whereIn('exam', $ids)
            ->where('student', $this->student_id)
            ->where('subject', $subject)
            ->groupBy("exam");
        $res = $resultsModel->get()->getResultArray();
        $ids2 = array_column($res,"id");
if ($ids2) {
    $resultsModel = new ExamResults();
    $resultsModel->select("SUM(mark) as mark")
        ->whereIn('id', $ids2)
        ->where('student', $this->student_id)
        ->where('subject', $subject);

    return $resultsModel->get()->getRow()->mark;
}
return 0;
    }
//    public function getSemesterExams($semester, $subject = false)
//    {
//        //Get semester exams
//
//        $exams = (new Exams())->where('session', $this->session_id)
//            ->where('semester', $semester)
//            ->groupStart()
//            ->where('class', $this->student->class->id)
//            ->orWhere('class', NULL)
//            ->orWhere('class', '')
//            ->groupEnd()
//            ->findAll();
//
////        $db = \Config\Database::connect();
////        $builder = $db->query("SELECT * FROM Vsession");
////        $res = $builder->getRow()->ids;
//
//         $db = \Config\Database::connect();
//        $builder = $db->query("CALL Pexamsids($this->session_id,$semester)");
//        $res = $builder->getRow()->ids;
//
//
//        $resultsModel = new ExamResults();
//
//        $mega = [];
//
//        foreach ($exams as $item) {
//            if ($subject) {
//                $resultsModel->where('exam', $item->id)
//                    ->where('student', $this->student->id)
//                    ->where('subject', $subject);
//
//                $res = $resultsModel->first();
//            } else {
//                $res = $resultsModel->findAll();
//            }
//            $mega[] = $res;
//        }
//
//        return $mega;
//    }

    public function getSemesterExamsClass($semester,$class)
    {
        //Get semester exams
        $db = \Config\Database::connect();
        $builder = $db->query("CALL PExamsids($this->session_id,$semester)");
        $res = $builder->getRow()->ids;
        $resultsModel = new ExamResults();

        $ids = explode(',',$res);
        $resultsModel->select("SUM(mark) as mark")
            ->whereIn('exam', $ids)
            ->where('student', $this->student->id);
        return $resultsModel->first()->mark?:0;
    }

    public function getQuarterExams($quarter, $subject = false)
    {
        $db = \Config\Database::connect();
        $builder = $db->query("CALL PQExamsids($this->session_id,$quarter)");
        $res = $builder->getRow()->ids;

        $resultsModel = new ExamResults();

        $ids = explode(',',$res);
        $resultsModel->select("SUM(mark) as mark")
            ->whereIn('exam', $ids)
            ->where('student', $this->student->id)
            ->where('subject', $subject);
        return $resultsModel->first()->mark?:0;
    }
}