<?php


namespace App\Controllers\Assessments;


use App\Controllers\AdminController;
use App\Models\Classes;
use App\Models\Sections;
use App\Models\Semesters;

class Rank extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Student Ranks";
    }

    public function index()
    {
        return $this->_renderPage('Academic/Assessments/Rank/index', $this->data);
    }

    public function getRank()
    {
        $class = $this->request->getPost('class');
        $semester = $this->request->getPost('semester');
        $section = $this->request->getPost('section');
        $class = (new Classes())->find($class);
        if (!$class) {
            return $this->response->setStatusCode(404)->setBody("Class not found");
        }

        $semester = (new Semesters())->find($semester);
        if(!$semester) {
            return $this->response->setStatusCode(404)->setBody("Class not found");
        }
        if(is_numeric($section)) {
            $section = (new Sections())->find($section);
            if($section) {
                $students = $section->students;
            } else {
                $section = FALSE;
            }
        } else {
            $section = FALSE;
        }

        if(!$section) {
            $students = $class->students;
        } else {
            $students = $section->students;
        }
        $data = [
            'students'   => $students,
            'semester'   => $semester
        ];
        return view('Academic/Assessments/Rank/get', $data);
    }
}