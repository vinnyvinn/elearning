<?php


namespace App\Controllers\Parent;


use App\Controllers\ParentController;
use App\Entities\Message;
use App\Models\Students;
use App\Models\Subjectteachers;
use App\Models\Teachers;

class Messages extends ParentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Messages/index', $this->data);
    }

    public function message($teacher, $student)
    {

        // PS: $teacher is from the Subjectteacher object
        $this->data['teacher'] = (new Teachers())->find($teacher);
        $this->data['student'] = (new Students())->find($student);
        $this->data['parent'] = $this->parent;
        return $this->_renderPage('Messages/messages', $this->data);
    }

    public function sendMessage()
    {
        if($this->request->getPost()) {
            $student = $this->request->getPost('for_student');
            $teacher = $this->request->getPost('teacher');
            $parent = $this->parent->id;
            if($student && $teacher && $parent) {
                $entity = new Message();
                $entity->teacher = $teacher;
                $entity->parent = $parent;
                $entity->for_student = $student;
                $entity->session = active_session();
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
        $teacher = $this->request->getPost('teacher');
        $student = $this->request->getPost('student');
        $this->data['teacher'] = (new Teachers())->find($teacher);
        $this->data['student'] = (new Students())->find($student);
        $this->data['parent'] = $this->parent;

        return view('Parent/Messages/list', $this->data);
    }
}