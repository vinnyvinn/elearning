<?php


namespace App\Controllers\Admin;


use App\Controllers\AdminController;
use App\Models\Classes;
use App\Models\Parents;
use App\Models\Students;
use App\Models\Teachers;

class SMS extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "SMS";

        return $this->_renderPage('Admin/SMS/index', $this->data);
    }

    public function sendSMS()
    {
        if($this->request->getPost()) {
            //Send to teachers
            $send_to_teachers = (bool) $this->request->getPost('send_to_teachers');
            $send_to_parents = (bool) $this->request->getPost('send_to_parents');
            $message = $this->request->getPost('message');
            $class = $this->request->getPost('class');
            $section = $this->request->getPost('section');
            $session = $this->request->getPost('session');

            $phone_numbers = [];

            if($send_to_teachers) {
                if(is_numeric($class)) {
                    $class = (new Classes())->find($class);
                    if(!$class) {
                        return redirect()->to(previous_url())->withInput()->with('error', "Class not found");
                    }
                    $teachers = $class->teachers;
                    //Get teachers of this class

                } else {
                    $teachers = (new Teachers())->findAll();
                }

//                d($teachers);
                foreach ($teachers as $teacher) {
                    if($teacher->profile->phone) {
                        array_push($phone_numbers, $teacher->profile->phone);
                    }
                }
            }

            if($send_to_parents) {
                if(is_numeric($class)) {
                    $class = (new Classes())->find($class);
                    if(!$class) {
                        return redirect()->to(previous_url())->withInput()->with('error', "Class not found");
                    }
                    $parents = (new Parents())->whereIn('id', function () use ($class) {
                        return (new Students())->select('parent')->where('class', $class->id)->where('session', active_session())->groupBy('parent');
                    })->findAll();
                } else {
                    $parents = (new Parents())->findAll();
                }

//                d($parents);
                foreach ($parents as $parent) {
                    if($parent->profile->phone) {
                        array_push($phone_numbers, $parent->profile->phone);
                    }
                }
            }
            $phone_numbers = array_unique($phone_numbers);
//            d($phone_numbers);
//            dd($this->request->getPost());

            $smser = new \App\Libraries\SMS();
            if($smser->sendSMS($message, implode(', ', $phone_numbers))) {
                return redirect()->to(previous_url())->with('success', "SMSes Sent successfully");
            } else {
                return redirect()->to(previous_url())->withInput()->with('error', $smser->error);
            }
        }

        return redirect()->to(previous_url())->with('error', "Invalid request");
    }
}