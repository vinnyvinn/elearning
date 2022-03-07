<?php


namespace App\Controllers\Admin;


use App\Controllers\AdminController;
use App\Libraries\SMS;

class SmsSettings extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "SMS Settings";
        return $this->_renderPage('Admin/Settings/SMS/index', $this->data);
    }

    public function save()
    {

        $gateway = $this->request->getPost('key');
        if($gateway) {
            $callback = $gateway.'_save_settings';
        } else {
            $callback = 'sms_save_settings';
        }

        $res = do_action($callback, $this->request);
        if($res === TRUE) {
            $msg = "Settings saved successfully";
            $return = true;
        }elseif($res === FALSE){
            $msg = "Unidentified error occured";
            return FALSE;
        } elseif($res) {
            $msg = (string) $res;
            $return = false;
        } else {
            $return = false;
            $msg = "An error occured";
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
        } else {
            $this->session->setFlashData($status, $msg);
            return $this->response->redirect(previous_url());
        }
    }

    public function testGateway($gateway)
    {
        $message = $this->request->getPost('message');
        $recipient = $this->request->getPost('recipient');

        $sms = new SMS();
        $resp = $sms->sendSMS($message, $recipient, $gateway);

        if($resp) {
            $return = TRUE;
            $msg = "SMS sent successfully";
        } else {
            $return = FALSE;
            $msg = $sms->error;
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
        } else {
            $this->session->setFlashData($status, $msg);
            return $this->response->redirect(previous_url());
        }

    }

    public function activateGateway($gateway)
    {
        update_option('active_sms_gateway', $gateway);

        $return = TRUE;
        $msg = "Gateway activated successfully";
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
        } else {
            $this->session->setFlashData($status, $msg);
            return $this->response->redirect(previous_url());
        }
    }
    public function deactivateGateway($gateway)
    {
        update_option('active_sms_gateway', '');

        $return = TRUE;
        $msg = "Gateway deactivated successfully";
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
        } else {
            $this->session->setFlashData($status, $msg);
            return $this->response->redirect(previous_url());
        }
    }
}
