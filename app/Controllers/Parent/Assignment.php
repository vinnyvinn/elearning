<?php


namespace App\Controllers\Parent;


use App\Models\Assignments;
use App\Models\Students;

class Assignment extends \App\Controllers\ParentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Assignment";

        return $this->_renderPage('Assessments/Assignment/index', $this->data);
    }

    public function view()
    {
        $student = $this->request->getPost('student');
        //$semester = $this->request->getPost('semester');
        $student = (new Students())->find($student);
        if(!$student) {
            return $this->response->setStatusCode(404)->setBody("Student not found");
        }

        if(!isset($student->class)) {
            return $this->response->setStatusCode(404)->setBody("{$student->profile->name} has not been assigned a class");
        }

        $quizes = (new Assignments())->where('class', $student->class->id)->orderBy('id', 'DESC')->findAll();
        //dd($quizes);
        $data = [
            'assignments' => $quizes,
            'student'   => $student
        ];

        return view('Parent/Assessments/Assignment/view', $data);
    }

    public function results($assignment, $student)
    {


    }
}