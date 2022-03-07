<?php


namespace App\Controllers\Teacher;


use App\Controllers\AdminController;
use App\Controllers\TeacherController;
use App\Models\Semesters;
use CodeIgniter\Exceptions\PageNotFoundException;

class Cats extends TeacherController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['cats'] = (new \App\Models\Exams())
            ->groupStart()
                ->where('class !=', NULL)
                ->orWhere('class !=', '')
            ->groupEnd()
            ->findAll();
        $this->data['semesters'] = (new Semesters())->where('session', active_session())->orderBy('id', 'DESC')->findAll();
        return $this->_renderPage('Exams/Cats/index', $this->data);
    }

    public function view($id)
    {
        $model = new \App\Models\Exams();
        $cat = $model->find($id);
        if(!$cat) throw new PageNotFoundException("Continuous Assessment test not found");

        $this->data['exam'] = $cat;
        $this->data['page'] = 'overview';
        $this->data['title'] = 'Results';
        return $this->_renderPage('Exams/Cats/view', $this->data);
    }

    public function results($id)
    {
        $model = new \App\Models\Exams();
        $cat = $model->find($id);
        if(!$cat) throw new PageNotFoundException("Continuous Assessment test not found");

        $this->data['exam'] = $cat;
        $this->data['page'] = 'results';
        $this->data['title'] = 'Results';
        return $this->_renderSection('view', $this->data);
    }

    public function _renderSection($view, $data = [])
    {
        $data['html'] = view('Teacher/Exams/Cats/'.$view, $data);
        return $this->_renderPage('Exams/Cats/layout', $data);
    }

    public function delete($id)
    {
        if(!current_user_can('delete_exam')) {
            $resp = [
                'status'    => 'error',
                'message'   => "You are not allowed to perform this action",
                'notifyType'    => 'toastr',
                'title'     => 'Error',
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $model = new \App\Models\Exams();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'window.location.reload()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }
}