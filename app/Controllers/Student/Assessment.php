<?php


namespace App\Controllers\Student;


use App\Controllers\StudentController;
use App\Models\ClassSubjects;
use App\Models\Students;

class Assessment extends StudentController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Assessments";
    }

    public function index()
    {
        return $this->_renderPage('Assessment/index', $this->data);
    }

    public function getAssessments()
    {
        $student = $this->request->getPost('student');

        $subject = $this->request->getPost('subject');
        $month = $this->request->getPost('month');
        $week = $this->request->getPost('week');
        $data = [
            'subject'   => $subject,
            'month'     => $month,
            'week'      => $week,
            'student'   => $student
        ];

        return view('Parent/Assessments/assessment', $data);
    }

//    public function results()
//    {
//        $semester = $this->request->getGet('semester');
//        $subject = $this->request->getGet('subject');
//
//        if(!empty($semester) && !empty($subject)) {
//            $session = active_session();
//            $key = 'manual_cats_'.$session.'-'.$semester.'-'.$this->student->class->id.'-'.$subject;
//            $keys = json_decode(get_option($key, json_encode([])), true);
//
//            $res = (new \App\Models\ManualAssessments())->where('session', $session)->where('class', $this->student->class->id)
//                ->where('semester', $semester)
//                ->where('student', $this->student->id)
//                ->where('subject', $subject)->get()->getLastRow();
//
//            if($res) {
//                $marks = @json_decode($res->results, true);
//
//                $this->data['results'] = $res;
//                $this->data['marks'] = $marks;
//                $this->data['keys'] = $keys;
//
//                $this->data['available'] = true;
//            } else {
//                $this->data['available'] = false;
//            }
//        } else {
//            $this->data['available'] = false;
//        }
//
//        return $this->_renderPage('Assessment/results', $this->data);
//    }

    public function results()
    {
        if($this->request->getPost()) {
            $this->data['student'] = $this->request->getPost('student');
            $this->data['semester'] = $this->request->getPost('semester');

            return view('Parent/Assessments/get_results', $this->data);
        }
        return $this->_renderPage('Assessments/results');
    }
}