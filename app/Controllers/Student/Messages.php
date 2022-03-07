<?php


namespace App\Controllers\Student;


use App\Entities\Message;
use App\Models\Parents;
use App\Models\Students;
use App\Models\Subjectteachers;
use App\Models\Teachers;

class Messages extends \App\Controllers\StudentController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        return $this->_renderPage('Messages/index', $this->data);
    }

    public function message($teacher)
    {
        // PS: $teacher is from the Subjectteacher object
        $this->data['student'] = $this->student;

        //Teacher is from the Subjecteacher model
        $teacher = (new Subjectteachers())->find($teacher);

        $this->data['teacher'] = $teacher->teacher;

        return $this->_renderPage('Messages/messages', $this->data);
    }

    public function send($teacher)
    {
        if($this->request->getPost()) {
            $student = $this->student->id;
            $teacher = $teacher;
            //$parent = $this->request->getPost('parent');
            if($student && $teacher) {
                $entity = new Message();
                $entity->teacher = $teacher;
                $entity->student = $student;
                $entity->parent = null;
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

    public function ajaxFetch()
    {
        $teacher = $this->request->getPost('teacher');
        $student = $this->request->getPost('student');

        $this->data['teacher'] = (new Teachers())->find($teacher);
        $this->data['student'] = (new Students())->find($student);
        //$this->data['teacher'] = $this->teacher;

        return view('Student/Messages/list', $this->data);
    }
}