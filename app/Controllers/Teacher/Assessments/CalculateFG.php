<?php


namespace App\Controllers\Teacher\Assessments;


use App\Models\AssessmentResults;
use App\Models\CatExams;
use App\Models\Classes;
use App\Models\ExamResults;
use App\Models\Exams;
use App\Models\FinalGrade;
use App\Models\Sections;
use App\Models\Semesters;

class CalculateFG extends \App\Controllers\TeacherController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Calculate Final Grade";
    }

    public function index()
    {

        return $this->_renderPage('Academic/Assessments/CalculateFG/index', $this->data);
    }

    public function getItems()
    {
        $section = $this->request->getPost('class');
        $class = (new Classes())->find($section);
        if (!$class) {
            return $this->response->setStatusCode(404)->setBody("Class not found");
        }

        $exams = (new Exams())->where('class', $class->id)->orWhere('class', NULL)->orWhere('class', '')->orderBy('id', 'DESC')->findAll();
        $assessments = (new AssessmentResults())->where('class', $class->id)->orderBy('id', 'DESC')->findAll();

        $data = [
            'exams' => $exams,
            'class' => $class,
            'subject' => $this->request->getPost('subject'),
            'semester'  => $this->request->getPost('semester'),
            'assessments'   => $assessments
        ];

        return view('Teacher/Academic/Assessments/CalculateFG/getItems', $data);
    }

    public function calculate()
    {
        //d($this->request->getPost());
        $exams = $this->request->getPost('exams');
        $assessments = $this->request->getPost('assessments');
        $subject = $this->request->getPost('subject');
        $class = $this->request->getPost('class');
        $semester = $this->request->getPost('semester');

        //Config
        //$base_numeral = 100; //Out of

        $semester = (new Semesters())->find($semester);
        if(!$semester) {
            return $this->response->setStatusCode(404)->setBody("Semester not found");
        }

        $class = (new Classes())->find($class);
        if (!$class) {
            return $this->response->setStatusCode(404)->setBody("Class not found");
        }

        $out_of = 0;
        $theExams = [];
        $examsModel = new ExamResults();
        foreach ($exams as $exam) {
            $x = $examsModel->where('exam', $exam)->where('subject', $subject)->get()->getRow(0,'\App\Entities\ExamResult');
            if($x) {
                $theExams[] = $x;
                $out_of += 100;
            }
        }

        $theAss = []; //TODO: Rename this variable
        $assessmentsModel = new AssessmentResults();
        foreach ($assessments as $assessment) {
            $y = $assessmentsModel->where('subject', $subject)->find($assessment);
            if($y) {
                $theAss[] = $y;
                $out_of += $y->out_of;
            }
        }
        //print_r($subject);

        $return = FALSE;
        $msg = "Something went wrong";
        $students = $class->students;
        if($students && count($students) > 0) {
            foreach ($students as $student) {
                //Exams
                $score = 0;
                foreach ($theExams as $theExam) {
                    if($theExam) {
                        $score += $theExam->mark;
                    }
                }
                //Ass
                foreach ($theAss as $theAs) {
                    if($u = $assessmentsModel->where('student', $student->id)->where('id', $theAs->id)->get()->getFirstRow()) {
                        $score += $u->score;
                    }
                }

                if($score > 0 && $out_of > 0) {
                    //$score = ($score/$out_of)*$base_numeral;
                } else {
                    $score = 0;
                }
                $to_db = [
                    'student'   => $student->id,
                    'class'     => $class->id,
                    'section'   => $student->section->id,
                    'score'     => $score,
                    'subject'   => $subject,
                    'semester'  => $semester->id,
                    'out_of'    => $out_of,
                    'session'   => active_session()
                ];
                $fgModel = new FinalGrade();
                try {
                    $existing = $fgModel->where('student', $student->id)->where('subject', $subject)->where('semester', $semester->id)->get()->getRow();
                    if($existing) {
                        $to_db['id'] = $existing->id;
                    }
                    if ($fgModel->save($to_db)) {
                        $return = TRUE;
                        $msg = json_encode([
                            'status'    => 'success',
                            'title'     => 'success',
                            'notifyType' => 'toastr',
                            'message'   => $existing ? 'Calculations updated successfully' : 'Calculations done successfully'
                        ]);
                    } else {
                        $msg="Database error";
                        $return = FALSE;
                    }
                } catch (\ReflectionException $e) {
                    $msg = $e->getMessage();
                    $return = FALSE;
                }
            }
        } else {
            $msg = "No students";
        }

        $code = $return ? 200 : 404;
        return $this->response->setStatusCode($code)
            ->setContentType($code == 200 ? 'application/json' : 'text/plain')
            ->setBody($msg);
    }
}