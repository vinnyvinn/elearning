<?php


namespace App\Controllers\Assessments;


use App\Models\AssessmentResults;
use App\Models\Assignments;
use App\Models\Classes;
use App\Models\ClassSubjects;
use App\Models\Semesters;

class Results extends \App\Controllers\AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        return $this->_renderPage('Academic/Assessments/Results/index', $this->data);
    }

    public function getCats()
    {
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $semester = $this->request->getPost('semester');

        $delete = $this->request->getPost('delete');
        $class = (new Classes())->find($class);
        if (!$class) {
            return $this->response->setStatusCode(404)->setBody("Class not found");
        }
        if($subject !='all') {
            $subject = (new ClassSubjects())->find($subject);
            if (!$subject) {
                return $this->response->setStatusCode(404)->setBody("Subject not found");
            }
        }
        $semester = (new Semesters())->find($semester);
        if(!$semester) {
            return $this->response->setStatusCode(404)->setBody("Semester not found");
        }

        $resModel = new AssessmentResults();
        if ($this->request->getPost('subject') !='all')
            $resModel->where('subject',$subject->id);
        $existing = $resModel->where('session', active_session())->where('class', $class->id)
            ->where('semester', $semester->id)->findAll();

        if(isset($delete) && $delete == 1) {
            if ($this->request->getPost('subject') !='all' )
                $resModel->where('subject',$subject->id);
            $existing = $resModel->where('session', active_session())->where('class', $class->id)
                 ->where('semester', $semester->id)->delete();
            $existing = FALSE;
        }
        if(!empty($existing)) {
            $data = [
                'class' => $class,
                'semester'  => $semester,
                'subject' => $this->request->getPost('subject'),
                'existing'  => $existing
            ];

            return view('Academic/Assessments/Results/existing', $data);
        }

        $classworks = (new \App\Models\ClassWorkItems());
        if ($this->request->getPost('subject') !='all')
            $classworks->where('subject',$subject->id);
        $classworks->where('semester', $semester->id)
            ->where('class', $class->id)->orderBy('id', 'DESC')->findAll();
        $quizes = (new \App\Models\QuizItems());
        if ($this->request->getPost('subject') !='all')
            $quizes->where('subject',$subject->id);
            $quizes->where('semester', $semester->id)
            ->where('class', $class->id)->orderBy('id', 'DESC')->findAll();
//        $exams = (new CatExamItems())
//            ->where('semester', $semester->id)
//            ->where('subject', $subject->id)
//            ->where('class', $class->id)->orderBy('id', 'DESC')->findAll();
        $assignments = (new Assignments());
        if ($this->request->getPost('subject') !='all')
            $assignments->where('subject',$subject->id);
        $assignments->where('semester', $semester->id)
            ->where('class', $class->id)->orderBy('id', 'DESC')->findAll();

        $data = [
            'classwork' => $classworks,
            'quizes' => $quizes,
            //'exams' => $exams,
            'assignments'   => $assignments,
            'class' => $class,
            'semester'  => $semester,
            'subject' => $this->request->getPost('subject')
        ];

        return view('Academic/Assessments/Results/getCats', $data);
    }
}