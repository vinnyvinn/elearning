<?php


namespace App\Controllers\Parent;


use App\Models\CatExamItems;
use App\Models\CatExams;
use App\Models\Students;

class Exam extends \App\Controllers\ParentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Exams";
        if (is_array($this->data['parent']->students) && count($this->data['parent']->students) > 1)
        return $this->_renderPage('Assessments/Exam/index_more_students', $this->data);
        return $this->_renderPage('Assessments/Exam/index', $this->data);
    }

    public function view()
    {
        $student = $this->request->getPost('student');
        $semester = $this->request->getPost('semester');
        $student = (new Students())->find($student);
        if(!$student) {
            return $this->response->setStatusCode(404)->setBody("Student not found");
        }

        if(!isset($student->class)) {
            return $this->response->setStatusCode(404)->setBody("{$student->profile->name} has not been assigned a class");
        }

        $quizes = (new CatExamItems())->where('class', $student->class->id)->where('semester', $semester)->orderBy('id', 'DESC')->findAll();
        //dd($quizes);
        $data = [
            'exam' => $quizes,
            'student'   => $student
        ];
        return view('Parent/Assessments/Exam/view', $data);
    }

    public function results($classwork, $student)
    {
        $classworkModel = new \App\Models\CatExamItems();
        $student = (new Students())->find($student);
        if(!$student) {
            return $this->response->setStatusCode(404)->setBody("Student not found");
        }

        $classwork = $classworkModel->find($classwork);
        if(!$classwork) {
            return $this->response->setStatusCode(404)->setBody("Quiz not found");
        }

        $this->data['site_title'] = "Quiz Results";
        $this->data['quiz'] = $classwork;
        $this->data['student'] = $student;

        return $this->_renderPage('Assessments/Exam/results', $this->data);
    }
}