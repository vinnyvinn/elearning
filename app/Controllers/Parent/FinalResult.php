<?php


namespace App\Controllers\Parent;


use App\Models\Semesters;
use App\Models\Students;

class FinalResult extends \App\Controllers\ParentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Final Result";

        return $this->_renderPage('Assessments/FinalResult/index', $this->data);
    }

    public function view()
    {
        if($this->request->getPost()) {
            $sem = $this->request->getPost('semester');
            $student = $this->request->getPost('student');

            $student = (new Students())->find($student);
            if(!$student) {
                return $this->response->setStatusCode(404)->setBody("Student not found");
            }

            $this->data['semester'] = (new Semesters())->find($sem);
            $this->data['session'] = getSession();
            $this->data['student'] = $student;

            return view('Parent/Assessments/FinalResult/view', $this->data);
        }

        return $this->response->setStatusCode(404)->setBody("Invalid request");
    }
}