<?php


namespace App\Controllers\Classes;


use App\Entities\Submission;
use App\Models\Submissions;

class Assignments extends Subjects
{
    public function __construct()
    {
        parent::__construct();
    }

    public function view($subject, $assignment)
    {
        $subject = (new \App\Models\ClassSubjects())->find($subject);
        $assignment = (new \App\Models\Assignments())->find($assignment);

        if (!$assignment) return $this->response->redirect(previous_url());

        $this->data['title'] = 'Assignments Submissions';
        $this->data['page'] = 'assignments';
        $this->data['subject'] = $subject;
        $this->data['assignment'] = $assignment;
        $this->data['submissions'] = (new Submissions())->where('assignment_id', $assignment->id)->findAll();
        $this->_renderPage('Classes/Subjects/Assignments/view', $this->data);
    }

    public function award_marks($subject, $assignment)
    {
        //$subject = (new \App\Models\Subjects())->find($subject);
        $assignment = (new \App\Models\Assignments())->find($assignment);

        if (!$assignment) {
            if ($this->request->isAJAX()) {
                $resp = [
                    'title' => 'Error',
                    'message' => 'Assignment not found',
                    'status' => 'error',
                    'notifyType' => 'toastr'
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            }

            return $this->response->redirect(previous_url());
        }
        if ($this->request->getPost()) {
            $model = new Submissions();
            $entity = new Submission();
            $entity->fill($this->request->getPost());
            if ($model->save($entity)) {
                $s = TRUE;
                $m = "Marks awarded successfully";
            } else {
                $s = FALSE;
                $m = "Failed to award marks";
            }
            $status = $s ? 'success' : 'error';

            if ($this->request->isAJAX()) {

                $resp = [
                    'title' => $s ? "Success" : "Error",
                    'message' => $m,
                    'status' => $status,
                    'notifyType' => 'toastr',
                    'callback' => $s ? 'window.location.reload()' : ''
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            }

            $this->session->setFlashData($s, $m);
            return $this->response->redirect(previous_url());
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'title' => 'Error',
                'message' => 'Invalid Request',
                'status' => 'error',
                'notifyType' => 'toastr'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        return $this->response->redirect(previous_url());
    }

    public function delete_submission($subject, $assignment)
    {
        $assignment = (new Submissions())->find($assignment);

        if (!$assignment) {
            if ($this->request->isAJAX()) {
                $resp = [
                    'title' => 'Error',
                    'message' => 'Assignment not found',
                    'status' => 'error',
                    'notifyType' => 'toastr'
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            }

            return $this->response->redirect(previous_url());
        }

        if ((new Submissions())->delete($assignment->id)) {
            @unlink($assignment->path);
            $s = TRUE;
            $m = "Assignment deleted successfully";
        } else {
            $s = FALSE;
            $m = "Failed to delete assignment";
        }
        $status = $s ? 'success' : 'error';
        $this->session->setFlashData($s, $m);
        if ($this->request->isAJAX()) {

            $resp = [
                'title' => $s ? "Success" : "Error",
                'message' => $m,
                'status' => $status,
                'notifyType' => 'toastr',
                'callback' => $s ? 'window.location.reload()' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        return $this->response->redirect(previous_url());
    }
}