<?php


namespace App\Controllers\Parent;


use App\Models\Quizes;
use App\Models\QuizItems;
use App\Models\Students;

class Quiz extends \App\Controllers\ParentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Quiz";

        return $this->_renderPage('Assessments/Quiz/index', $this->data);
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

        $quizes = (new QuizItems())->where('class', $student->class->id)->where('semester', $semester)->orderBy('id', 'DESC')->findAll();

        $data = [
            'quizes' => $quizes,
            'student'   => $student
        ];
        return view('Parent/Assessments/Quiz/view', $data);
    }

    public function results($classwork, $student)
    {
        $classworkModel = new \App\Models\QuizItems();
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

        return $this->_renderPage('Assessments/Quiz/results', $this->data);
    }
}