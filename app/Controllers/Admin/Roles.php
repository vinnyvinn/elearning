<?php


namespace App\Controllers\Admin;


use App\Controllers\AdminController;

class Roles extends AdminController
{
    /**
     * @var \App\Models\Roles
     */
    public $rolesModel;

    public function __construct()
    {
        parent::__construct();
        $this->rolesModel = new \App\Models\Roles();
    }

    public function index()
    {
        $data['roles'] = $this->rolesModel->findAll();
        $this->_renderPage('Admin/Users/Roles/index', $data);
    }

    public function create()
    {
        if ($this->request->getPost()) {
            $entity = new \App\Entities\Roles();
            $entity->fill($this->request->getPost());
            if ($this->rolesModel->save($entity)) {
                $this->session->setFlashData('success', 'Role added successfully');
            } else {
                $this->session->setFlashData('error', implode('<br/>', $this->rolesModel->errors()));
            }
            return $this->response->redirect(previous_url());
        } else {
            $this->_renderPage('Admin/Users/Roles/create');
        }
    }

    public function edit($id)
    {
        $role = $this->rolesModel->find($id);
        if (!$role) {
            return $this->response->redirect(previous_url());
        }
        if ($this->request->getPost()) {
            $entity = new \App\Entities\Roles();
            $entity->fill($this->request->getPost());
            if ($this->rolesModel->update($id, $entity)) {
                $this->session->setFlashData('success', 'Role updated successfully');
                return $this->response->redirect(site_url(route_to('admin.users.roles.capabilities', $role->id)));
            } else {
                $this->session->setFlashData('error', implode('<br/>', $this->rolesModel->errors()));
                return $this->response->redirect(site_url(route_to('admin.users.roles.capabilities', $role->id)));
            }
        } else {
            $data['role'] = $role;
            $this->_renderPage('Admin/Users/Roles/edit', $data);
        }
    }

    public function capabilities($id)
    {
        $role = $this->rolesModel->find($id);
        if (!$role) {
            return $this->response->redirect(previous_url());
        }
        if ($this->request->getPost()) {

        } else {
            $data['role'] = $role;
            $this->_renderPage('Admin/Users/Roles/view', $data);
        }
    }

    public function delete($id)
    {
        return $this->response->redirect(previous_url());
    }
}