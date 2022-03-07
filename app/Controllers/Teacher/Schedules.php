<?php


namespace App\Controllers\Teacher;


use App\Controllers\TeacherController;

class Schedules extends TeacherController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->studentRegular();
    }

    public function studentRegular()
    {
        return $this->_renderPage('Schedules/index', $this->data);
    }
    public function getStudentRegularSchedule()
    {
        $model = new \App\Models\Sections();
        $id = $this->request->getPost('section');
        $section = $model->find($id);
        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');
            return $this->response->redirect(previous_url());
        }
        $data = [
            'section' => $section,
            'page' => 'timetable',
            'title' => 'Timetable'
        ];
        return view('Teacher/Schedules/student-regular', $data);
    }

    // student ASP schedule
    public function studentASP()
    {
        return $this->_renderPage('Schedules/asp', $this->data);
    }

    public function getAspSchedule()
    {
        $model = new \App\Models\Sections();
        $id = $this->request->getPost('section');
        $section = $model->find($id);
        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');
            return $this->response->redirect(previous_url());
        }
        $data = [
            'section' => $section,
            'page' => 'asp_schedule',
            'title' => 'After School Program'
        ];

        return view('Teacher/Schedules/get_asp', $data);
    }
}