<?php


namespace App\Controllers;


use App\Libraries\IonAuth;
use App\Models\Parents;
use App\Models\Students;
use App\Models\Teachers;
use App\Models\User;
use Config\Services;

class Profile extends BaseController
{
    public $data;

    public function __construct()
    {
        $this->data['user'] = (new User())->find((new IonAuth())->user()->row()->id);
        //$this->data['ion'] = (new \App\Libraries\IonAuth())->user()->row();
    }

    public function changePassword()
    {
        $ionAuth = new IonAuth();

        $user = $this->data['user'];
        $data = [
            'user' => $user
        ];
        $html = view('Profile/password', $data);
        $this->data['_content'] = $html;
        $data['_content'] = $html;

        if ($ionAuth->inGroup(1)) { //Super admin

            $html = view('Admin/layout', $this->data);

        } elseif ($ionAuth->inGroup(2)) { //Teachers
            $data['teacher'] = (new Teachers())->where('user_id', $user->id)->get()->getLastRow('App\Entities\Teacher');
            $html = view('Teacher/layout', $data);
        } elseif ($ionAuth->inGroup(3)) { //Student
            $data['student'] = (new Students())->where('user_id', $user->id)->get()->getLastRow('App\Entities\Student');
            $html = view('Student/layout', $data);

        } elseif ($ionAuth->inGroup(4)) { //Parent
            $data['parent'] = (new Parents())->where('id', $user->id)->get()->getLastRow('App\Entities\Parents');
            $html = view('Parent/layout', $data);
        }

        echo $html;
    }

    public function postChangePassword()
    {
        $validation = Services::validation();
        $validation->setRule('new_password', 'Mew Password', 'required|min_length[8]');
        $validation->setRule('confirm_password', 'Mew Password', 'required|matches[new_password]');

        if ($validation->withRequest($this->request)->run()) {

            $ionAuth = new IonAuth();
            $old_pass = $this->request->getPost('password');
            if ($ionAuth->verifyPassword($old_pass, $this->data['user']->password)) {
                $user = new \App\Entities\User();
                $model = new User();
                $user->id = $this->data['user']->id;
                $user->password = $this->request->getPost('new_password');
                if ($model->save($user)) {
                    $user->update_usermeta('password', $this->request->getPost('new_password'));
                    $return = TRUE;
                    $msg = "Password changed successfully";
                    //$msg = $user->password;
                } else {
                    $return = FALSE;
                    $msg = "Failed to change password";
                }
            } else {
                $return = FALSE;
                $msg = "Unauthorized! Incorrect password";
            }
        } else {
            $return = FALSE;
            $msg = implode(', ', $validation->getErrors());
        }

        $status = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $status,
                'message' => $msg,
                'notifyType' => 'toastr',
                'title' => $return ? 'Success' : 'Error',
                'callback' => $return ? 'window.location = "' . previous_url() . '"' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }
}