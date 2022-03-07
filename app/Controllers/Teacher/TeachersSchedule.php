<?php


namespace App\Controllers\Teacher;


use App\Controllers\TeacherController;
use App\Models\Teachers;
use CodeIgniter\Exceptions\PageNotFoundException;

class TeachersSchedule extends TeacherController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Schedules/Teacher/teacher', $this->data);
    }

    public function getSchedule()
    {
        $teacher = $this->request->getPost('teacher');
        $teacher = (new Teachers())->find($teacher);
        if(!$teacher) {
            throw new PageNotFoundException('Teacher not found');
        }
        $data = [
            'teacher'   => $teacher
        ];

        return view('Teacher/Schedules/Teacher/schedule', $data);
    }

    public function aspSchedule()
    {
        return $this->_renderPage('Schedules/Teacher/teacher_asp', $this->data);
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

        return view('Teacher/Schedules/Teacher/view_asp', $data);
    }
}