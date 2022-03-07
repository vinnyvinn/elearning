<?php


namespace App\Controllers\Academic;


use App\Controllers\AdminController;
use App\Models\Teachers;
use CodeIgniter\Exceptions\PageNotFoundException;

class TeachersSchedule extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Academic/Schedule/teacher', $this->data);
    }

    public function getSchedule()
    {
        $teacher = $this->request->getPost('teacher');
        $class = $this->request->getPost('class');
        $teacher = (new Teachers())->find($teacher);
        if(!$teacher) {
         throw new PageNotFoundException('Teacher not found');
        }
        $data = [
            'teacher'   => $teacher,
            'class'     => $class
        ];

        return view('Academic/Schedule/Teacher/schedule', $data);
    }
  public function pdf($teacher,$class)
    {
        $teacher = (new Teachers())->find($teacher);
        if(!$teacher) {
            throw new PageNotFoundException('Teacher not found');
        }
        $data = [
            'teacher'   => $teacher,
            'class'     => $class
        ];

       return view('Academic/Schedule/Teacher/pdf', $data);
    }
    public function print($teacher,$class)
    {
        $teacher = (new Teachers())->find($teacher);
        if(!$teacher) {
            throw new PageNotFoundException('Teacher not found');
        }
        $data = [
            'teacher'   => $teacher,
            'class'     => $class
        ];

       return view('Academic/Schedule/Teacher/print', $data);
    }

    public function aspSchedule()
    {
        return $this->_renderPage('Academic/Schedule/Teacher/teacher_asp', $this->data);
    }

    public function getAspSchedule()
    {
        $teacher = $this->request->getPost('teacher');
        $teacher = (new Teachers())->find($teacher);
        if(!$teacher) {
            throw new PageNotFoundException('Teacher not found');
        }
        $data = [
            'teacher'   => $teacher
        ];

        return view('Academic/Schedule/Teacher/view_asp', $data);
    }
    public function aspSchedulePdf($id)
    {
        $this->data['teacher'] = (new Teachers())->find($id);
      return view('Academic/Schedule/Teacher/pdf', $this->data);
    }

    public function getSubjects()
    {
        $teacher = $this->request->getPost('teacher');
        $subjects = (new \App\Models\Subjectteachers())->find($teacher);
        if(!$subjects) {
            $return = FALSE;
            $msg = "No subject for this teacher";
        }

        $html = '';
        foreach ($subjects as $subject) {
            $html .= '<option value="'.$subject->subject->id.'">'.$subject->subject->name.'</option>';
        }

        if(!$return) {
            return $this->response->setStatusCode(404)->setBody($msg);
        }

        echo $html;
    }

    public function getTeacherGrades()
    {
        $teacher = $this->request->getPost('teacher');
        $subjects = (new \App\Models\Subjectteachers())->where('teacher_id', $teacher)->groupBy('class_id')->findAll();

        $return = TRUE;
        $msg = 'No teacher found';
        if(!$subjects) {
            $return = FALSE;
            $msg = "No subject for this teacher";
        }

        $html = '';
        foreach ($subjects as $subject) {
            $html .= '<option value="'.$subject->class->id.'">'.$subject->class->name.'</option>';
        }

        if(!$return) {
            return $this->response->setStatusCode(404)->setBody($msg);
        }

        echo $html;
    }


}