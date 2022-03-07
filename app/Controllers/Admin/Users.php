<?php


namespace App\Controllers\Admin;


use App\Controllers\AdminController;

class Users extends AdminController
{
    /**
     * @var \App\Models\User
     */
    private $userModel;
    /**
     * @var \CodeIgniter\Session\Session
     */
    public $session;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new \App\Models\User();
    }

    public function index() {
        return $this->_renderPage('Admin/Users/index');
    }

    public function view($id)
    {

        $data['user'] = $this->userModel->find($id);
        return $this->_renderPage('Admin/Users/view', $data);
    }

    public function create()
    {
        if($this->request->getPost()) {
            $validation = \Config\Services::validation();
            $validation->reset();
            $validation->setRule('email', 'Email Address', 'trim|required|valid_email|is_unique[users.email]');
            $validation->setRule('username', 'Username', 'trim|required|is_unique[users.username]');
            $validation->setRule('surname', 'Surname', 'trim|required');
            $validation->setRule('first_name', 'First Name', 'trim|required');
            $validation->setRule('last_name', 'Last Name', 'trim|required');
            $validation->setRule('phone', 'Phone Number', 'trim|required');
            $validation->setRule('company', 'Company', 'trim|required');
            $validation->setRule('password', 'Password', 'required|min_length[6]');
            $validation->setRule('pass', 'Confirm Password', 'required|matches[password]');
            if($validation->withRequest($this->request)->run()) {
                $ionAuth = new \App\Libraries\IonAuth();
                $identity = $this->request->getPost('username');
                $password = $this->request->getPost('password');
                $email = $this->request->getPost('email');
                $additional_data = [
                    'first_name'        => $this->request->getPost('first_name'),
                    'last_name'         => $this->request->getPost('last_name'),
                    'surname'           => $this->request->getPost('surname'),
                    'phone'             => $this->request->getPost('phone'),
                    'company'           => $this->request->getPost('company'),
                ];
                $validation->reset();
                $validation->setRule('profile_pic', 'Profile Image', 'uploaded[profile_pic]|is_image[profile_pic]');
                if($validation->withRequest($this->request)->run()) {
                    $file = $this->request->getFile('profile_pic');
                    $newName = $file->getRandomName();
                    if($file->move(FCPATH.'uploads/avatars/', $newName)) {
                        $additional_data['avatar'] = $newName;
                    }
                }
                if($userid = $ionAuth->register($identity, $password, $email, $additional_data)) {
                    do_action('user_registered', $userid);
                    $this->session->setFlashData('success', 'User created successfully');
                    return $this->response->redirect(site_url(route_to('admin.users.profile', $userid)));
                } else {
                    $this->session->setFlashData('error', $ionAuth->errors('list'));
                    return $this->response->redirect(previous_url())->withInput();
                }
            } else {
                $this->session->setFlashData('error', implode('<br/>', $validation->getErrors()));
                return $this->response->redirect(previous_url())->withInput();
            }
        } else {
            return $this->_renderPage('Admin/Users/create');
        }
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if($this->request->getPost()) {
            $validation = \Config\Services::validation();
            $validation->reset();
            //$validation->setRule('email', 'Email Address', 'trim|required|valid_email|is_unique[users.email]');
            //$validation->setRule('surname', 'Surname', 'trim|required');
            $validation->setRule('first_name', 'First Name', 'trim|required');
            $validation->setRule('last_name', 'Last Name', 'trim|required');
            $validation->setRule('phone', 'Phone Number', 'trim|required');
            $validation->setRule('company', 'Company', 'trim|required');
            if($user && $validation->withRequest($this->request)->run()) {
                $ionAuth = new \App\Libraries\IonAuth();
                //$identity = $this->request->getPost('username');
                //$password = $this->request->getPost('password');
                //$email = $this->request->getPost('email');
                $additional_data = [
                    'first_name'        => $this->request->getPost('first_name'),
                    'last_name'         => $this->request->getPost('last_name'),
                    'surname'           => $this->request->getPost('surname'),
                    'phone'             => $this->request->getPost('phone'),
                    'company'           => $this->request->getPost('company'),
                ];
                $validation->reset();
                $validation->setRule('profile_pic', 'Profile Image', 'uploaded[profile_pic]|is_image[profile_pic]');
                if($validation->withRequest($this->request)->run()) {
                    $file = $this->request->getFile('profile_pic');
                    $newName = $file->getRandomName();
                    if($file->move(FCPATH.'uploads/avatars/', $newName)) {
                        $additional_data['avatar'] = $newName;

                    }
                }
                if($ionAuth->update($id, $additional_data)) {
                    do_action('user_updated', $id);
                    @unlink(FCPATH.'uploads/avatars/'.$user->avatar);
                    $this->session->setFlashData('success', 'User updated successfully');
                    return $this->response->redirect(site_url(route_to('admin.users.profile', $id)));
                } else {
                    $this->session->setFlashData('error', $ionAuth->errors('list'));
                    return $this->response->redirect(previous_url())->withInput();
                }
            } else {
                $this->session->setFlashData('error', implode('<br/>', $validation->getErrors()));
                return $this->response->redirect(previous_url())->withInput();
            }
        } else {
            $data['user'] = $user;
            return $this->_renderPage('Admin/Users/edit', $data);
        }
    }

    public function delete($id)
    {
        $ionAuth = new \App\Libraries\IonAuth();
        if($ionAuth->isAdmin($id)) {
            $this->session->setFlashData('error', "No no no, this is the original user and cannot be deleted");
            return $this->response->redirect(previous_url());
            exit;
        }
        if($this->userModel->delete($id)) {
            do_action('user_deleted', $id);
            $this->session->setFlashData('success', "User deleted successfully");
        } else {
            $this->session->setFlashData('error', "Failed to delete user");
        }

        return $this->response->redirect(previous_url());
    }
}