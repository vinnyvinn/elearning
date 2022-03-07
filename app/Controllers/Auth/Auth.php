<?php


namespace App\Controllers\Auth;


use App\Controllers\PublicController;
use App\Libraries\IonAuth;
use CodeIgniter\Exceptions\PageNotFoundException;
use Config\Services;

class Auth extends PublicController
{
    /**
     * @var \CodeIgniter\Session\Session
     */
    public $session;
    private $configIonAuth;
    private $validationListTemplate;
    /**
     * @var \CodeIgniter\Validation\Validation
     */
    private $validation;
    /**
     * @var IonAuth
     */
    private $ionAuth;

    public function __construct()
    {
        $this->ionAuth = new IonAuth();
        $this->validation = Services::validation();

        $this->configIonAuth = config('IonAuth');

        if (!empty($this->configIonAuth->templates['errors']['list'])) {
            $this->validationListTemplate = $this->configIonAuth->templates['errors']['list'];
        }
    }

    public function index()
    {
        return $this->login();
    }

    public function login()
    {

        if ($this->ionAuth->loggedIn()) {
            if($this->ionAuth->inGroup(4)) {
                return redirect()->to(site_url(route_to('parent.index')));
            } elseif($this->ionAuth->inGroup(3)) {
                return redirect()->to(site_url(route_to('student.index')));
            } elseif ($this->ionAuth->inGroup(2)) {
                return redirect()->to(site_url(route_to('teacher.index')));
            } elseif($this->ionAuth->inGroup(1)) {
                return redirect()->to(site_url(route_to('admin.index')));
            } else {
                return $this->logout();
            }
        }
        if ($this->request->getPost()) {
            $this->validation->setRule('identity', 'Username or Email Address', 'required');
            $this->validation->setRule('password', 'Password', 'required');
            if ($this->validation->withRequest($this->request)->run()) {
                $remember = (bool)$this->request->getPost('remember');
                if ($this->ionAuth->login($this->request->getPost('identity'), $this->request->getPost('password'), $remember)) {
                    do_action('user_login_successful');
                    
                    if($this->ionAuth->inGroup(4)) {
                        return redirect()->to(site_url(route_to('parent.index')));
                    } elseif($this->ionAuth->inGroup(3)) {
                        return redirect()->to(site_url(route_to('student.index')));
                    } elseif ($this->ionAuth->inGroup(2)) {
                        return redirect()->to(site_url(route_to('teacher.index')));
                    } elseif($this->ionAuth->inGroup(1)) {
                       return redirect()->to(site_url(route_to('admin.index')));
                    } else {
                        return $this->logout();
                    }
                } else {
                    do_action('user_login_unsuccessful');
                    $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
                    return redirect()->to(site_url(route_to('auth.login')));
                }
            } else {
                $message = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');
                $this->session->setFlashdata('message', $message);

                return redirect()->to(site_url(route_to('auth.login')));
            }
        } else {
            return $this->_AuthRenderPage('Auth/login');
        }
    }

    public function logout()
    {
        $this->data['title'] = 'Logout';

        // log the user out
        do_action('user_logging_out');
        $this->ionAuth->logout();
        do_action('user_logged_out');

        // redirect them to the login page
        return redirect()->to(site_url(route_to('auth.login')));
    }

    public function forgot_password()
    {
        if ($this->request->getPost()) {
            $this->validation->setRule('identity', 'Email Address', 'required|valid_email');
            if ($this->validation->withRequest($this->request)->run()) {
                $identityColumn = $this->configIonAuth->identity;
                $identity = $this->ionAuth->where($identityColumn, $this->request->getPost('identity'))->users()->row();
                if (empty($identity)) {
                    if ($this->configIonAuth->identity !== 'email') {
                        $this->ionAuth->setError('Auth.forgot_password_identity_not_found');
                    } else {
                        $this->ionAuth->setError('Auth.forgot_password_email_not_found');
                    }

                    $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));

                    return $this->response->redirect(previous_url());
                }

                // run the forgotten password method to email an activation code to the user
                $forgotten = $this->ionAuth->forgottenPassword($identity->{$this->configIonAuth->identity});

                if ($forgotten) {
                    // if there were no errors
                    do_action('user_recover_password', $identity);
                    $this->session->setFlashdata('message', $this->ionAuth->messages());
                    return redirect()->to(site_url(route_to('auth.login'))); //we should display a confirmation page here instead of the login page
                } else {
                    $this->session->setFlashdata('error', 'Password reset E-Mail could not be sent. Please try again');
                    return $this->response->redirect(previous_url());
                }

            } else {
                $message = $this->validation->getErrors() ? $this->validation->listErrors($this->validationListTemplate) : $this->session->getFlashdata('message');
                $this->session->setFlashdata('error', $message);
            }
            return $this->response->redirect(previous_url());
        } else {
            return $this->_AuthRenderPage('Auth/forgot_password');
        }
    }

    public function reset_password($code)
    {
        if (!$code) {
            throw PageNotFoundException::forPageNotFound();
        }
        $user = $this->ionAuth->forgottenPasswordCheck($code);
        if ($user) {
            if ($this->request->getPost()) {
                $this->validation->setRule('new', 'New Password', 'required|min_length[' . $this->configIonAuth->minPasswordLength . ']|matches[new_confirm]');
                $this->validation->setRule('new_confirm', 'Confirm Password', 'required');
                if ($this->validation->withRequest($this->request)->run()) {
                    $identity = $user->{$this->configIonAuth->identity};
                    if ($user->id == $this->request->getPost('user_id')) {

                        if ($this->ionAuth->resetPassword($identity, $this->request->getPost('new'))) {
                            do_action('user_password_reset_successful', $user);
                            $this->session->setFlashdata('success', 'Password reset was successful');
                            //return $this->response->redirect(site_url(route_to('auth.login')));
                        } else {
                            do_action('user_password_reset_failed', $user);
                            $this->session->setFlashdata('error', 'ACL error occured. Please try again');
                            return $this->response->redirect(current_url());
                        }
                    } else {
                        $this->ionAuth->clearForgottenPasswordCode($identity);
                        $this->session->setFlashdata('message', 'SECURITY ERROR! Reset code does not match your profile');
                        return $this->response->redirect(site_url(route_to('auth.forgot_password')));
                    }
                } else {
                    $this->session->setFlashdata('message', 'Passwords do not match');
                    return $this->response->redirect(current_url());
                }
            } else {
                //Show the password reset form
                $data['message'] = $this->session->getFlashdata('message');
                $data['title'] = 'Reset Password';
                $data['user'] = $user;
                return view('Auth/reset_password', $data);
            }
        } else {
            $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
            return $this->response->redirect(site_url(route_to('auth.forgot_password')));
        }

        //return $this->response->setContentType('application/json')->setBody(json_encode($response));
        return $this->response->redirect(site_url(route_to('auth.login')));
    }


    public function activate($id, $code = '')
    {
        $admin = false;
        if ($this->ionAuth->isAdmin()) {
            $activation = $this->ionAuth->activate($id);
            $admin = true;
        } else {
            $activation = $this->ionAuth->activate($id, $code);
        }
        if ($activation) {
            do_action('user_activated_manually', $$id);
            $this->session->setFlashdata('message', $this->ionAuth->messages());
        } else {
            $this->session->setFlashdata('message', $this->ionAuth->errors($this->validationListTemplate));
        }

        $admin ? $this->response->redirect(previous_url()) : $this->response->redirect(site_url(route_to('auth.login')));
    }
}