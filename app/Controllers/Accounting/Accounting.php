<?php


namespace App\Controllers\Accounting;


use App\Controllers\AdminController;
use App\Entities\Fee;
use App\Models\FeeCollection;

class Accounting extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Admin/Accounting/index', $this->data);
    }

    public function create()
    {
        if($this->request->getPost()) {
            $entity = new Fee();
            $entity->fill($this->request->getPost());
            $model = new \App\Models\Accounting();
            $entity->semester = $this->request->getPost('semester') ? $this->request->getPost('semester') : NULL;
            $entity->class = $this->request->getPost('class') ? $this->request->getPost('class') : NULL;
            $entity->section = $this->request->getPost('section') ? $this->request->getPost('section') : NULL;
            $entity->student = $this->request->getPost('student') ? $this->request->getPost('student') : NULL;
            if($model->save($entity)) {
                $return = TRUE;
                $msg = "Fee added successfully";
            } else {
                $return = FALSE;
                $msg = "Failed to add fee entry";
            }
        } else {
            $return = FALSE;
            $msg = "Invalid request";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {

            $resp = [
                'title'     => $return ? 'Success' : 'Error',
                'message'   => $msg,
                'status'    => $status,
                'notifyType'    => 'swal',
                'callbackTime' => 'onconfirm',
                'showCancelButton' => false,
                'callback'  => 'window.location.reload()'
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    public function delete($id)
    {
        $model = new \App\Models\Accounting();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Fee entry deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete the entry";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {

            $resp = [
                'title'     => $return ? 'Success' : 'Error',
                'message'   => $msg,
                'status'    => $status,
                'notifyType'    => 'swal',
                'callbackTime' => 'onconfirm',
                'showCancelButton' => false,
                'callback'  => 'window.location.reload()'
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    public function collect()
    {
        return $this->_renderPage('Admin/Accounting/collect', $this->data);
    }

    public function addCollection($student)
    {

        $model = new FeeCollection();
        if($model->save($this->request->getPost())) {
            $return = TRUE;
            $msg = "Fee Recorded successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to record entry";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {

            $resp = [
                'title'     => $return ? 'Success' : 'Error',
                'message'   => $msg,
                'status'    => $status,
                'notifyType'    => 'swal',
                'callbackTime' => 'onconfirm',
                'showCancelButton' => false,
                'callback'  => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    public function history()
    {
        return $this->_renderPage('Admin/Accounting/history', $this->data);
    }
}