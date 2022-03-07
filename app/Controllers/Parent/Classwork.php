<?php


namespace App\Controllers\Parent;


use App\Models\Students;

class Classwork extends \App\Controllers\ParentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Classwork";

        return $this->_renderPage('Assessments/Classwork/index', $this->data);
    }

    public function view()
    {
        $student = $this->request->getPost('student');
        $semester = $this->request->getPost('semester');
        $student = (new Students())->find($student);
        if(!$student) {
            return $this->response->setStatusCode(404)->setBody("Student not found");
        }

        $class = @$student->class;
        if(!$class) {
            return $this->response->setStatusCode(404)->setBody("{$student->profile->name} has not been assigned a class");
        }

        $classworkModel = new \App\Models\ClassWorkItems();
        $classworks = $classworkModel->where('class', $class->id)->where('semester', $semester)->orderBy('id', 'DESC')->findAll();

        $data = [
            'classwork' => $classworks,
            'student'   => $student
        ];
        return view('Parent/Assessments/Classwork/view', $data);
    }

    public function results($classwork, $student)
    {
        $classworkModel = new \App\Models\ClassWorkItems();
        $student = (new Students())->find($student);
        if(!$student) {
            return $this->response->setStatusCode(404)->setBody("Student not found");
        }

        $classwork = $classworkModel->find($classwork);
        if(!$classwork) {
            return $this->response->setStatusCode(404)->setBody("Classwork not found");
        }

        $this->data['site_title'] = "Classwork Results";
        $this->data['classwork'] = $classwork;
        $this->data['student'] = $student;

        return $this->_renderPage('Assessments/Classwork/results', $this->data);
    }
}