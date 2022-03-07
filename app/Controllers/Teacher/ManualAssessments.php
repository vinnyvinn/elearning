<?php


namespace App\Controllers\Teacher\Assessments;


use App\Models\Classes;
use App\Models\ClassSubjects;
use App\Models\Semesters;

class ManualAssessments extends \App\Controllers\TeacherController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Assessments";
        $this->data['site_description'] = "Assessments";
    }

    public function index()
    {

        return $this->_renderPage('Assessments/Manual/index', $this->data);
    }

    public function get()
    {
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $semester = $this->request->getPost('semester');

        $class = (new Classes())->find($class);
        if(!$class) {
            return $this->response->setBody("Class not found")->setStatusCode(404);
        }
        $subject = (new ClassSubjects())->find($subject);
        if(!$subject) {
            return $this->response->setBody("Subject not found")->setStatusCode(404);
        }
        $semester = (new Semesters())->find($semester);
        if(!$semester) {
            return $this->response->setBody("Semester not found")->setStatusCode(404);
        }

        $this->data['class'] = $class;
        $this->data['subject'] = $subject;
        $this->data['semester'] = $semester;

        return view('Teacher/Assessments/Manual/get', $this->data);
    }
    public function saveCATotal()
    {
        $return = FALSE;
        $msg = "invalid data submitted";
        $manualModel = new \App\Models\ManualAssessments();
        $session = $this->request->getPost('session');
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $semester = $this->request->getPost('semester');
        $given_total = $this->request->getPost('given_total');
        $desired_total = $this->request->getPost('desired_total');



        $m_ass = (new \App\Models\ManualAssessments())->where('session',active_session())->where('semester',$semester)->where('class',$class)->where('subject',$subject)->findAll();

        foreach ($m_ass as $ass) {
            $converted_total = number_format($ass->total * $desired_total/$given_total,2,'.','');
            $to_db = [
                'given_total' => $given_total,
                'desired_total' => $desired_total,
                'converted_total' => number_format($converted_total,2,'.',''),

            ];

            $db = \Config\Database::connect();
            $builder = $db->table('manual_assessments');
            $builder->where('session', $session)->where('class', $class)
                ->where('student', $ass->student)
                ->where('semester', $semester)
                ->where('subject', $subject);
            $builder->update($to_db);
        }

        $return = TRUE;
        $msg = "Updated successfully";

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'getTheAssessments()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }

    public function saveCA()
    {
        $return = FALSE;
        $msg = "Failed to save assessment fields";

        $session = $this->request->getPost('session');
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $items = $this->request->getPost('item');
        $semester = $this->request->getPost('semester');

        $class = (new Classes())->find($class);
        if(!$class) {
            return $this->response->setBody("Class not found")->setStatusCode(404);
        }
        $subject = (new ClassSubjects())->find($subject);
        if(!$subject) {
            return $this->response->setBody("Subject not found")->setStatusCode(404);
        }
        if(!is_array($items)) {
            return $this->response->setBody("Invalid request")->setStatusCode(501);
        }
        //Reorder items
        $first = $items[0];
        unset($items[0]);
        $items[] = $first;

        $key = 'manual_cats_'.$session.'-'.$semester.'-'.$class->id.'-'.$subject->id;
        if(update_option($key, json_encode($items))) {
            $return = TRUE;
            $msg = "Assessment fields saved successfully";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'getTheAssessments()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }

    public function saveResults()
    {
        $return = FALSE;
        $msg = "invalid data submitted";
        //print_r($_POST);
        $manualModel = new \App\Models\ManualAssessments();
        $session = $this->request->getPost('session');
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $students = $this->request->getPost('student');
        $semester = $this->request->getPost('semester');

        if(is_array($students)) {
            foreach ($students as $student=>$result) {
                $to_db = [
                    'session'   => $session,
                    'class'     => $class,
                    'subject'   => $subject,
                    'student'   => $student,
                    'semester'  => $semester,
                    'results'   => json_encode($result)
                ];
                $res = (new \App\Models\ManualAssessments())->where('session', $session)->where('class', $class)
                    ->where('student', $student)
                    ->where('subject', $subject)->get()->getLastRow();
                if($res) {
                    $manualModel->where('session', $session)->where('class', $class)
                        ->where('student', $student)
                        ->where('subject', $subject)->set($to_db)->update();
                } else {
                    try {
                        $manualModel->save($to_db);
                    } catch (\ReflectionException $e) {
                    }
                }
            }

            $return = TRUE;
            $msg = "Saved successfully";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'getTheAssessments()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }
}