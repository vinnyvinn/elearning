<?php


namespace App\Controllers\School;


use App\Controllers\AdminController;
use App\Entities\Semester;

class Semesters extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['sessions'] = (new \App\Models\Sessions())->orderBy('id', 'DESC')->findAll();
        $this->data['semesters'] = (new \App\Models\Semesters())->orderBy('id', 'DESC')->where('session',active_session())->findAll();
        return $this->_renderPage('Admin/Semesters/index', $this->data);
    }

    public function create()
    {
        $entity = new Semester();
        $entity->fill($this->request->getPost());
        $model = new \App\Models\Semesters();
        if ($model->save($entity)) {
            $return = TRUE;
            $msg = "Semester created successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to create semester";
        }

        $status = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'title'     => $return ? 'Success' : 'Error',
                'notifyType' => 'toastr',
                'callback'  => 'window.location.reload()',
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $this->session->setFlashData($status, $msg);
            return $this->response->redirect(site_url(route_to('admin.school.semesters.index')));
        }
    }

    public function delete($id)
    {
        $model = new \App\Models\Semesters();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Semester deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete entry";
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
        return $this->response->redirect(site_url(route_to('admin.school.semesters.index')));
    }
}