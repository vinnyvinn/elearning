<?php


namespace App\Controllers\Academic;


use App\Controllers\AdminController;
use App\Models\ClassSubjects;
use App\Models\Sections;
use CodeIgniter\Exceptions\PageNotFoundException;

class LessonPlan extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Academic/LessonPlan/index', $this->data);
    }

    public function getLessonPlan()
    {
        $section = $this->request->getPost('section');
        $subject = $this->request->getPost('subject');
        $subject = (new ClassSubjects())->find($subject);
        $section = (new Sections())->find($section);
        if(!$section || !$subject) {
            return new PageNotFoundException('Class or Section not found');
        }
        $data = [
            'section' => $section,
            'subject'   => $subject,
            'month'     => $this->request->getPost('month'),
            'week'      => $this->request->getPost('week')
        ];

        return view('Academic/LessonPlan/view', $data);
    }
}