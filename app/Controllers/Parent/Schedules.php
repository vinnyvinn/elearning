<?php


namespace App\Controllers\Parent;


class Schedules extends \App\Controllers\ParentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->regular();
    }

    public function regular()
    {
        if (is_array($this->data['parent']->studentsCurrent) && count($this->data['parent']->studentsCurrent) > 1)
        return $this->_renderPage('Schedules/index_more', $this->data);
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
        return view('Parent/Schedules/regular', $data);
    }

    public function asp()
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

        return view('Parent/Schedules/get_asp', $data);
    }
}