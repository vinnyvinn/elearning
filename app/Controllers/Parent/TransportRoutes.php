<?php


namespace App\Controllers\Parent;


use App\Controllers\ParentController;
use App\Models\Students;

class TransportRoutes extends ParentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Transport/index', $this->data);
    }

    public function ajaxSetStudentRoute()
    {
        $student = $this->request->getPost('student');
        $student = (new Students())->find($student);
        if(!$student || $student->parent->id != $this->parent->id) {
            return $this->response->setStatusCode(404)->setBody('Student not found');
        }

        $transid = $this->request->getPost('route');
        if($transid && is_numeric($transid)) {
            $student->profile->update_usermeta('transportation_route', $transid);
            $msg = "Transportation Route updated successfully";
            $return = TRUE;
        } else {
            $msg = "Failed to update transportation route";
            $return = FALSE;
        }

        $resp = [
            'status'    => $return ? 'success' : 'error',
            'message'   => $msg,
            'notifyType'    => 'toastr',
            'title'     => $return ? 'Success' : 'Error',
            'callback'  => $return ? 'window.location.reload()' : '',
        ];
        return $this->response->setContentType('application/json')->setBody(json_encode($resp));
    }

    public function ajaxStudentRoute()
    {
        $student = $this->request->getPost('student');
        $student = (new Students())->find($student);
        if(!$student) {
            return $this->response->setStatusCode(404)->setBody('Student not found');
        }
    }
}