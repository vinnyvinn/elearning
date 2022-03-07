<?php


namespace App\Controllers\Admin;


use App\Controllers\AdminController;
use CodeIgniter\Session\Session;

class Modules extends AdminController
{
    /** @var Session */
    public $session;

    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        return $this->_renderPage('Admin/Modules/index');
    }

    public function activate($id) {
        if(activateModule($id)) {
            $this->session->setFlashdata('success', 'Module activated');
        } else {
            $this->session->setFlashdata('error', 'Failed to activate module');
        }

        return $this->response->redirect(site_url(route_to('admin.modules.index')));
    }

    public function deactivate($id) {
        if(deactivateModule($id)) {
            $this->session->setFlashdata('success', 'Module deactivated');
        } else {
            $this->session->setFlashdata('error', 'Failed to deactivate module');
        }

        return $this->response->redirect(site_url(route_to('admin.modules.index')));
    }

    public function delete($id) {
        if(deleteModule($id)) {
            $this->session->setFlashdata('success', 'Module deleted');
        } else {
            $this->session->setFlashdata('error', 'Failed to delete module');
        }

        return $this->response->redirect(site_url(route_to('admin.modules.index')));
    }

    public function upload() {
        $res = installPlugin($this->request);
        if($res === true) {
            $this->session->setFlashdata('success', 'Module installed successfully');
        } else {
            $this->session->setFlashdata('error', 'Failed to install module: '.$res);
        }

        return $this->response->redirect(site_url(route_to('admin.modules.index')));
    }
}