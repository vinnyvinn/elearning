<?php


namespace App\Controllers\Assessments;


use App\Models\AssessmentResults;
use App\Models\CatExamItems;
use App\Models\CatExams;
use App\Models\Classes;
use App\Models\ExamResults;
use App\Models\Exams;
use App\Models\FinalGrade;
use App\Models\Sections;
use App\Models\Semesters;

class CalculateFG extends \App\Controllers\AdminController
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
        $semester = $this->request->getPost('semester');
        $subject = $this->request->getPost('subject');
        $class = (new Classes())->find($section);
        if (!$class) {
            return $this->response->setStatusCode(404)->setBody("Class not found");
        }

        $exams = (new CatExamItems())
            ->where('semester', $semester)
            ->where('subject', $subject)
            ->where('class', $class->id)->orWhere('class', NULL)->orWhere('class', '')->orderBy('id', 'DESC')->findAll();
        //$assessments = (new AssessmentResults())->where('class', $class->id)->orderBy('id', 'DESC')->findAll();

        $data = [
            'exams' => $exams,
            'class' => $class,
            'subject' => $this->request->getPost('subject'),
            'semester'  => $this->request->getPost('semester'),
            //'assessments'   => $assessments
        ];

        return view('Academic/Assessments/CalculateFG/getItems', $data);
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
        $base_numeral = 100; //Out of

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
        $examsModel = new CatExamItems();
        if($exams && count($exams)) {
            foreach ($exams as $exam) {
                $x = $examsModel->find($exam);
                if($x) {
                    $theExams[] = $x;
                    $out_of += $x->out_of;
                }
            }
        }

        $theAss = []; //TODO: Rename this variable
        $assessmentsModel = new AssessmentResults();
        if($assessments && count($assessments)) {
            foreach ($assessments as $assessment) {
                $y = $assessmentsModel->where('subject', $subject)->where('name', $assessment)->get()->getFirstRow();
                if($y) {
                    $theAss[] = $y;
                    $out_of += $y->out_of;
                }
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
                    $tScore = @$theExam->getSubmission($student->id);
                    //print_r($theExam->id);
                    if($tScore) {
                        $score += $tScore->score;
                    }
                }
                //Ass
                foreach ($theAss as $theAs) {
                    if($u = $assessmentsModel->where('student', $student->id)->where('name', $theAs->name)->get()->getFirstRow()) {
                        $score += $u->score;
                    }
                }


                if($score > 0) {
                    //$score = ($score/$out_of)*$base_numeral;
                } else {
                    $score = 0;
                }
                //Fix division by zero
                if ( $out_of > 0) {

                } else {
                    $out_of = 1;
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
                            'callback'  => '',
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