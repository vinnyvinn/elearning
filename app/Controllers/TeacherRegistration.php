<?php


namespace App\Controllers;


use App\Models\Registrations;

class TeacherRegistration extends PublicController
{
    public function __construct()
    {

    }

    public function index()
    {
        if($this->request->getPost()) {
            //TODO: Something amazing
            $model = new Registrations();
            if($model->saveRecord()){
                return redirect()->to(current_url())->with('success', "Form submitted successfully. Please wait for further communication");
            } else {
                return redirect()->to(previous_url())->withInput()->with('error', $model->error);
            }
        }

        $this->data['site_title'] = "Teacher Recruitment";

        return $this->_renderPage('Pages/teacher_registration', $this->data);
    }
    public function index_admin()
    {
        if($this->request->getPost()) {
            //TODO: Something amazing
            $model = new Registrations();
            if($model->saveRecord()){
                return redirect()->to(current_url())->with('success', "Form submitted successfully. Please wait for further communication");
            } else {
                return redirect()->to(previous_url())->withInput()->with('error', $model->error);
            }
        }

        $this->data['site_title'] = "Administration Recruitment";

        return $this->_renderPage('Pages/admin_registration', $this->data);
    }
    public function information()
    {

        $this->data['site_title'] = "Teacher Information";

        return $this->_renderPage('Pages/information', $this->data);
    }
}