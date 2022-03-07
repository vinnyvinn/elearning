<?php


namespace App\Controllers\Student;


use App\Models\Classes;
use App\Models\Students;

class Certificate extends \App\Controllers\StudentController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Yearly Certificate";
    }
    public function index()
    {

        return $this->_renderPage('Certificate/index', $this->data);
    }

    public function certificate()
    {

        $student = (new Students())->find($this->request->getPost('student'));
        $session = $this->request->getPost('year');

        $this->data['session'] = $session;
        $this->data['student'] = $student;
        $class = (new Classes())->find($student->class->id);

        if ($class->type =='kg')
            return view('Student/Certificate/pages/certificate_kg', $this->data);
        if (is_quarter_session($session))
            return view('Student/Certificate/pages/certificate_quarter', $this->data);
        return view('Student/Certificate/pages/certificate', $this->data);
    }
    public function report()
    {

        $student = (new Students())->find($this->request->getPost('student'));
        $session = $this->request->getPost('year');

        $data['session'] = $session;
        $data['student'] = $student;

        return view('Student/Certificate/pages/reportform', $data);
    }

    public function downloadCert($student)
    {
        $student = (new Students())->find($student);

        $this->data['student'] = $student;

        $class = (new Classes())->find($student->class->id);
        if ($class->type == 'kg')
            return view('Student/Certificate/pages/print-pdf-kg', $this->data);
        if (is_quarter_plus_session())
            return view('Student/Certificate/pages/quarters/print-pdf-plus', $this->data);
        if (is_quarter_session())
            return view('Student/Certificate/pages/quarters/print-pdf', $this->data);
        return view('Student/Certificate/pages/print-pdf', $this->data);

    }

    public function evaluation()
    {
        $student = (new Students())->find($this->request->getPost('student'));
        $session = $this->request->getPost('year');

        $this->data['session'] = $session;
        $this->data['student'] = $student;

        $class = (new Classes())->find($student->class->id);
        $this->data['student'] = $student;
        if ($class->type=='kg'){
            return view('Student/Certificate/pages/evaluation_kg', $this->data);
        }
        return view('Student/Certificate/pages/evaluation', $this->data);
    }

    public function evaluation_summary()
    {
        $student = (new Students())->find($this->request->getPost('student'));
        $session = $this->request->getPost('year');

        $this->data['session'] = $session;
        $this->data['student'] = $student;


        return view('Student/Certificate/pages/evaluation_summary', $this->data);
    }
//    public function view()
//    {
//        $session = $this->request->getPost('year');
//        $student = $this->request->getPost('student');
//
//        if(is_numeric($session) && is_numeric($student)) {
//            $data['session'] = $session;
//            $data['student'] = $student;
//
//            return view('Parent/Certificate/view', $data);
//        }
//
//        return "Invalid session or student";
//    }


    public function print($student)
    {
        $student = (new Students())->find($student);

        $this->data['student'] = $student;
        $class = (new Classes())->find($student->class->id);
        if ($class->type =='kg')
            return view('Academic/Certificate/print_kg', $this->data);
        if (is_quarter_plus_session())
            return view('Academic/Certificate/quarters/print_plus', $this->data);

        if (is_quarter_session()){
            return view('Academic/Certificate/quarters/print', $this->data);
        }

       return view('Academic/Certificate/print', $this->data);
    }
}