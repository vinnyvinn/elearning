<?php
namespace App\Controllers\Academic;

class Schedules extends \App\Controllers\AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function regular()
    {
        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Regular Class Schedule List";
        return $this->_renderPage('Academic/Schedule/index', $this->data);
    }

    public function getRegularSchedule()
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
        return view('Academic/Schedule/regular', $data);
    }

    public function printTable($id)
    {
        $model = new \App\Models\Sections();
        //$id = $this->request->getPost('section');
        $section = $model->find($id);
        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');

            return $this->response->redirect(previous_url());
        }
        $data = [
            'section' => $section,
            'page' => 'timetable',
            'title' => 'Timetable',
            'print' => true
        ];
        return view('Academic/Schedule/regular', $data);
    }
    public function print($id)
    {
        $model = new \App\Models\Sections();
        $section = $model->find($id);
        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');

            return $this->response->redirect(previous_url());
        }
        $data = [
            'section' => $section,
            'page' => 'timetable',
            'title' => 'Timetable',
            'print' => true
        ];
        return view('Academic/Schedule/print', $data);
    }

    public function pdf($id)
    {
        $model = new \App\Models\Sections();
        $section = $model->find($id);
        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');

            return $this->response->redirect(previous_url());
        }
        $data = [
            'section' => $section,
            'page' => 'timetable',
            'title' => 'Timetable',
            'print' => true
        ];
        return view('Academic/Schedule/pdf', $data);
    }

    public function asp()
    {
        return $this->_renderPage('Academic/Schedule/asp', $this->data);
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

        return view('Academic/Schedule/get_asp', $data);
    }

    public function printAspSchedule($id)
    {
        $model = new \App\Models\Sections();
        //$id = $this->request->getPost('section');
        $section = $model->find($id);
        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');
            return $this->response->redirect(previous_url());
        }
        $data = [
            'section' => $section,
            'page' => 'asp_schedule',
            'title' => 'After School Program',
            'print' => true
        ];


        return view('Academic/Schedule/get_asp', $data);
    }
}