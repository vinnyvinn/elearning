<?php


namespace App\Controllers\Student\Assessments;


use App\Models\Semesters;

class FinalGrade extends \App\Controllers\StudentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Final Grade";

        return $this->_renderPage('Assessments/FinalGrade/index', $this->data);
    }

    public function getResults()
    {
        if($this->request->getPost()) {
            $sem = $this->request->getPost('semester');

            $this->data['semester'] = (new Semesters())->find($sem);
            $this->data['session'] = getSession();
            return view('Student/Assessments/FinalGrade/results', $this->data);
        }

        return $this->response->setStatusCode(404)->setBody("Invalid request");
    }
}