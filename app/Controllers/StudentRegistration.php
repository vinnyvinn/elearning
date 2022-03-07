<?php


namespace App\Controllers;


use App\Models\Registrations;

class StudentRegistration extends PublicController
{
    public $data;

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
        $this->data['site_title'] = "Student Registration";

        return $this->_renderPage('Pages/student_registration', $this->data);
    }
    public function pre_index()
    {
        $this->data['site_title'] = "Student Registration";

        return $this->_renderPage('Pages/pre_student_registration', $this->data);
    }
}