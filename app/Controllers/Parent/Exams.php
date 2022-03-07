<?php


namespace App\Controllers\Parent;


use App\Controllers\ParentController;
use App\Libraries\YearlyResults;
use App\Models\ExamResults;
use App\Models\Students;

class Exams extends ParentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (is_array($this->data['parent']->studentsCurrent) && count($this->data['parent']->studentsCurrent) > 1)
        return $this->_renderPage('Exams/index_more_students', $this->data);
        return $this->_renderPage('Exams/index', $this->data);
    }

    public function results()
    {

//        if (is_array($this->data['parent']->studentsCurrent) && count($this->data['parent']->studentsCurrent) > 1)
//        return $this->_renderPage('Exams/results_more_students', $this->data);
        return $this->_renderPage('Exams/results', $this->data);
    }

    public function ajaxMainResults()
    {
        $student  = $this->request->getPost('student');
        $exam = $this->request->getPost('exam');

        $this->data = [
            'student'   => $student,
            'exam'      => $exam,
            'parent'    => $this->parent
        ];
        return view('Parent/Exams/results_ajax', $this->data);

    }

    public function ajaxExam()
    {
        $exam = $this->request->getPost('exam');
        echo json_encode((new \App\Models\Exams())->find($exam)->name);
    }
    public function ajaxStudent()
    {
        $student  = (new Students())->find($this->request->getPost('student'));
        echo json_encode(['name' => $student->profile->name,'number'=>count($student->section->students),'class'=>$student->class->name.','.$student->section->name,'admission'=>$student->admission_number]);
    }

    public function studentExamResults()
    {
        if (is_array($this->data['parent']->studentsCurrent) && count($this->data['parent']->studentsCurrent) > 1)
      return $this->_renderPage('ExamResult/index_more_students', $this->data);
      return $this->_renderPage('ExamResult/index', $this->data);
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

    public function continuousAssessment()
    {
        return $this->_renderPage('Exams/Cats/index', $this->data);
    }

//    The idea for this was to show results and rank for a particular
//    student exam through the \App\Libraries\YearlyResults() class but shit is complicated
//
//    public function ajaxResultsModified()
//    {
//        $student  = $this->request->getPost('student');
//        $exam = $this->request->getPost('exam');
//        $student = (new Students())->find($student);
//
//        if($student && $student->parent->id == $this->parent->id) {
//            $yearlyResultsObject = new YearlyResults($student->id);
//            $subjects = $student->class->subjects;
//            $n = 0;
//            foreach ($subjects as $subject) {
//                $n++;
//
//            }
//        }
//    }

    public function ajaxResults()
    {
        //echo "Simething funny";
        $student  = $this->request->getPost('student');
        $exam = $this->request->getPost('exam');
        $student = (new Students())->find($student);
        if($student && $student->parent->id == $this->parent->id) {
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

//        $results = [
//            'labels'    => ["English", "Math", "Physics"],
//            'marks'     => [45,66,76],
//        ];
//        return $this->response->setContentType('application/json')->setBody(json_encode($results));
    }

    public function ajaxExams()
    {

        $student  = $this->request->getPost('student');
        $exam = $this->request->getPost('exam');
        $semester = $this->request->getPost('semester');

        $student = (new Students())->find($student);
        $return = TRUE;
        if($student && $student->parent->id == $this->parent->id) {
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
}