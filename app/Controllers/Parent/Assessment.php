<?php


namespace App\Controllers\Parent;


use App\Controllers\ParentController;
use App\Models\Students;

class Assessment extends ParentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Assessments/index');
    }

    public function getStudentSubjects()
    {
        $student = $this->request->getPost('student');
        $student = (new Students())->find($student);
        $html = '';
        if($student) {
            $subjects = $student->class->subjects;
            if($subjects && count($subjects) > 0) {
                $html .= '<option value="">Select Subject</option>';
                foreach ($subjects as $subject) {
                    $html .= '<option value="'.$subject->id.'">'.$subject->name.'</option>';
                }
            }
        } else {
            $html .= '<option value="">No Subjects</option>';
        }

        return $html;
    }

    public function getAssessments()
    {
        $student = $this->request->getPost('student');

        $subject = $this->request->getPost('subject');
        $month = $this->request->getPost('month');
        $week = $this->request->getPost('week');
        $data = [
            'subject'   => $subject,
            'month'     => $month,
            'week'      => $week,
            'student'   => $student
        ];

        return view('Parent/Assessments/assessment', $data);
    }

    public function results()
    {
        if($this->request->getPost()) {
            $this->data['student'] = $this->request->getPost('student');
            $this->data['semester'] = $this->request->getPost('semester');

            return view('Parent/Assessments/get_results', $this->data);
        }
        if (is_array($this->data['parent']->studentsCurrent) && count($this->data['parent']->studentsCurrent) > 1)
        return $this->_renderPage('Assessments/results_more_students');
        return $this->_renderPage('Assessments/results');
    }
}