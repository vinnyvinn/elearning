<?php


namespace App\Controllers\Student;


use App\Controllers\StudentController;
use App\Models\ExamResults;
use App\Models\Exams;
use App\Models\Students;
use CodeIgniter\Exceptions\PageNotFoundException;

class Exam extends StudentController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Exams";
    }

    public function index()
    {
        $this->_renderPage('Exam/index', $this->data);
    }

    public function view($id)
    {
        $exam = (new Exams())->find($id);
        if(!$exam) throw new PageNotFoundException("Selected exam does not exist");
        $this->data['exam'] = $exam;

        $this->_renderPage('Exam/view', $this->data);
    }

    public function studentExamResults()
    {
       return $this->_renderPage('ExamResult/index', $this->data);
    }

    public function results()
    {

        return $this->_renderPage('Exams/results', $this->data);
    }

    public function ajaxMainResults()
    {
        $student  = $this->request->getPost('student');
        $exam = $this->request->getPost('exam');

        $this->data = [
            'student'   => $student,
            'exam'      => $exam
        ];
        return view('Student/Exams/results_ajax', $this->data);

    }

    public function ajaxResults()
    {
        $student  = $this->request->getPost('student');
        $exam = $this->request->getPost('exam');
        $student = (new Students())->find($student);
        if($student) {
            $examResultsModel = new ExamResults();
            $exam_results = $examResultsModel->getResultsAndRank($student->id, $exam);

            if($exam_results ) {

                return $this->response->setContentType('application/json')->setBody(json_encode($exam_results));
            } else {
                return "Results for ".$student->profile->name." have not been added";
            }
        } else {
            $return = FALSE;
            return "Student does not exist or you are not allowed to view the results";
        }
    }


    public function getExam()
    {
        echo json_encode((new Exams())->find( $this->request->getPost('exam'))->name);
    }
    public function ajaxExams()
    {


        $student  = $this->request->getPost('student');
        $exam = $this->request->getPost('exam');
        $semester = $this->request->getPost('semester');

        $student = (new Students())->find($student);
        $return = TRUE;
        if($student) {
            if($semester) {
                $exams = $student->getExams($semester);
            } else {
                $exams = $student->exams;
            }
            $html = '';
            if($exams && count($exams) > 0) {
                $html .= '<option value="">Select exam</option>';
                foreach ($exams as $exam) {
                    $html .= '<option value="'.$exam->id.'">'.$exam->name.'</option>';
                }
            } else {
                $html .= '<option value="">No exams found</option>';
            }
        } else {
            $return = FALSE;
            $msg = "Student does not exist or you are not allowed to view the results";
        }

        if($return) {
            return $this->response->setBody($html);
        } else {
            return $this->response->setStatusCode(404)->setBody($msg);
        }
    }
    public function viewResults($id)
    {
        $exam = (new Exams())->find($id);
        if(!$exam) throw new PageNotFoundException("Selected exam does not exist");
        $this->data['exam'] = $exam;

        $this->_renderPage('Exam/results', $this->data);
    }

    public function ajaxSchedule()
    {
        $student = $this->request->getPost('student');
        $exam = $this->request->getPost('exam');
        $student = (new Students())->find($student);
        $exam = (new \App\Models\Exams())->find($exam);
        if(!$student || !$exam) {
            return $this->response->setContentType('application/json')->setStatusCode(404)->setBody('Student or Exam was not found');
        }

        $data = [
            'student'   => $student,
            'class'     => $student->class,
            'exam'      => $exam
        ];
        return view('Parent/Exams/schedule', $data);
    }
}