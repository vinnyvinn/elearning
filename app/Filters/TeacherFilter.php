<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TeacherFilter implements \CodeIgniter\Filters\FilterInterface
{
    /**
     * @var \App\Libraries\IonAuth
     */
    private $ionAuth;
    private $session;

    /**
     * @inheritDoc
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $this->ionAuth = new \App\Libraries\IonAuth();
        $this->session = \Config\Services::session();
        if(!$this->ionAuth->loggedIn()) {
            $this->session->setFlashdata('message', "You must be logged in to access this page");
            return redirect()->to(site_url(route_to('auth.login')));
        }
        if(!$this->ionAuth->inGroup(2)) {
            $this->session->setFlashdata('message', "You must be a teacher to access this page");
            return redirect()->back();
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // TODO: Implement after() method.
    }
}