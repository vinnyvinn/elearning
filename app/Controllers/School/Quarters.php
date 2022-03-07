<?php


namespace App\Controllers\School;


use App\Controllers\AdminController;
use App\Entities\Quarter;
use App\Entities\Semester;

class Quarters extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['sessions'] = (new \App\Models\Sessions())->orderBy('created_at', 'DESC')->findAll();
        $this->data['quarters'] = (new \App\Models\Quarters())->orderBy('created_at', 'DESC')->where('session',active_session())->findAll();
        $this->data['semesters'] = (new \App\Models\Semesters())->orderBy('created_at', 'DESC')->where('session',active_session())->findAll();
        return $this->_renderPage('Admin/Quarters/index', $this->data);
    }

    public function createSemester($id)
    {
        $this->data['session'] = $id;
        $this->data['quarters'] = (new \App\Models\Quarters())->orderBy('id', 'DESC')->findAll();
        $this->data['semesters'] = (new \App\Models\Semesters())->orderBy('id', 'DESC')->where('session',active_session())->findAll();
        return $this->_renderPage('Admin/Quarters/semester', $this->data);
    }

    public function create()
    {
        $entity = new Quarter();
        $entity->fill($this->request->getPost());
        $model = new \App\Models\Quarters();
        if ($model->save($entity)) {
            $return = TRUE;
            $msg = "Quarter created successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to create quarter";
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
            return $this->response->redirect(site_url(route_to('admin.school.quarters.index')));
        }
    }

    public function saveSemester()
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
            $this->session->setFlashData($status, $msg);
            return $this->response->redirect(site_url(route_to('admin.sessions.index')));

    }
    public function delete($id)
    {
        $model = new \App\Models\Quarters();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Quarter deleted successfully";
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
        return $this->response->redirect(site_url(route_to('admin.school.quarters.index')));
    }
}