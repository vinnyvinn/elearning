<?php


namespace App\Controllers\Teacher;


use App\Controllers\TeacherController;
use App\Entities\Message;
use App\Models\Parents;
use App\Models\Students;
use App\Models\Subjectteachers;
use App\Models\Teachers;

class Messages extends TeacherController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        return $this->_renderPage('Messages/index', $this->data);
    }

    public function message($parent, $student)
    {

        // PS: $teacher is from the Subjectteacher object
        $this->data['student'] = (new Students())->find($student);
        $this->data['parent'] = (new Parents())->find($parent);
        return $this->_renderPage('Messages/messages', $this->data);
    }

    public function messageStudent($student)
    {
        // PS: $teacher is from the Subjectteacher object
        $this->data['student'] = (new Students())->find($student);
        return $this->_renderPage('Messages/student_messages', $this->data);
    }

    public function sendMessage()
    {
        if($this->request->getPost()) {
            $student = $this->request->getPost('for_student');
            $teacher = $this->teacher->id;
            $parent = $this->request->getPost('parent');
            if($student && $teacher && $parent) {
                $entity = new Message();
                $entity->teacher = $teacher;
                $entity->parent = $parent;
                $entity->for_student = $student;
                $entity->session = active_session();
                $entity->message = $this->request->getPost('message');
                $entity->direction = 's';
                $model = new \App\Models\Messages();
                try {
                    if ($model->save($entity)) {
                        $return = TRUE;
                        $msg = "Message sent successfully";
                    } else {
                        $return = FALSE;
                        $msg = "Failed to send message";
                    }
                } catch (\ReflectionException $e) {
                    $return = FALSE;
                    $msg = $e->getMessage();
                }
            } else {
                $return = FALSE;
                $msg = "Invalid request";
            }
        } else {
            $return = FALSE;
            $msg = "Invalid request";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {

            $resp = [
                'title'     => $return ? 'Success' : 'Error',
                'message'   => $msg,
                'status'    => $status,
                'notifyType'    => 'toastr',
                'callback'  => 'reloadMessages()'
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    public function sendStudent()
    {
        if($this->request->getPost()) {
            $student = $this->request->getPost('student');
            $teacher = $this->teacher->id;

            if($student && $teacher) {
                $entity = new Message();
                $entity->teacher = $teacher;
                $entity->parent = null;
                $entity->for_student = $student;
                $entity->student = $student;
                $entity->message = $this->request->getPost('message');
                $entity->direction = 'r';
                $model = new \App\Models\Messages();
                try {
                    if ($model->save($entity)) {
                        $return = TRUE;
                        $msg = "Message sent successfully";
                    } else {
                        $return = FALSE;
                        $msg = "Failed to send message";
                    }
                } catch (\ReflectionException $e) {
                    $return = FALSE;
                    $msg = $e->getMessage();
                }
            } else {
                $return = FALSE;
                $msg = "Invalid request";
            }
        } else {
            $return = FALSE;
            $msg = "Invalid request";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {

            $resp = [
                'title'     => $return ? 'Success' : 'Error',
                'message'   => $msg,
                'status'    => $status,
                'notifyType'    => 'toastr',
                'callback'  => 'reloadMessages()'
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    public function ajaxGetMessage()
    {
        $parent = $this->request->getPost('parent');
        $student = $this->request->getPost('student');
        $this->data['parent'] = (new Parents())->find($parent);
        $this->data['student'] = (new Students())->find($student);
        $this->data['teacher'] = $this->teacher;

        return view('Teacher/Messages/list', $this->data);
    }

    public function ajaxGetStudentMessage()
    {
        //$parent = $this->request->getPost('parent');
        $student = $this->request->getPost('student');
        //$this->data['parent'] = (new Parents())->find($parent);
        $this->data['student'] = (new Students())->find($student);
        $this->data['teacher'] = $this->teacher;

        return view('Teacher/Messages/student_list', $this->data);
    }
}