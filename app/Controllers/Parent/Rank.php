<?php


namespace App\Controllers\Parent;


use App\Controllers\ParentController;
use App\Models\Students;

class Rank extends ParentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Rank/index', $this->data);
    }

    public function rankAjax()
    {
        $student = $this->request->getPost('student');
        $student = (new Students())->find($student);
        if($student && $this->parent->id == $student->parent->id) {
            $this->data['student'] = $student;
            return view('Parent/Rank/rank', $this->data);
        } else {
            $html = "Selected student does not exist";
        }

        return $this->response->setStatusCode(404)->setBody($html);
    }
}