<?php


namespace App\Controllers\Teacher;

use App\Controllers\TeacherController;
use App\Entities\Requirement;
use App\Models\Classes;
use App\Models\RequirementSubmission;
use App\Models\Students;

class Requirements extends TeacherController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Requirements";
        return $this->_renderPage('Requirements/index', $this->data);
    }

    public function view($requirement, $class)
    {

        $class = (new Classes())->find($class);
        if(!$class) {
          return redirect()->to(previous_url())->with('error', "Class not found");
        }

        $requirement = (new \App\Models\Requirements())->find($requirement);
        if(!$requirement) {
            return redirect()->to(previous_url())->with('error', "Requirement entry not found");
        }

        $this->data['class'] = $class;
        $this->data['requirement'] = $requirement;
        $this->data['site_title'] = "Requirement for ".$class->name;
        return $this->_renderPage('Requirements/view', $this->data);
    }

    public function viewSection($requirement, $class,$section)
    {

        $class = (new Classes())->find($class);
        $section = (new \App\Models\Sections())->find($section);
        if(!$class) {
            return redirect()->to(previous_url())->with('error', "Class not found");
        }

        $requirement = (new \App\Models\Requirements())->find($requirement);
        if(!$requirement) {
            return redirect()->to(previous_url())->with('error', "Requirement entry not found");
        }

        $this->data['class'] = $class;
        $this->data['requirement'] = $requirement;
        $this->data['site_title'] = "Requirement for ".$class->name.','.$section->name;
        $this->data['students'] = $section->students;
        return $this->_renderPage('Requirements/view', $this->data);
    }

    public function updateTeacherComment()
    {
        $student = (new Students())->find($this->request->getPost('student'));
        $model = new RequirementSubmission();
        $to_db = array(
            'teacher_comment' => $this->request->getPost('teacher_comment'),
            'teacher_check' => 1,
            'student' => $student->id,
            'class' => isset($student->class->id) ? $student->class->id : 0,
            'section' => isset($student->section->id) ? $student->section->id : 0,
            'session' => active_session(),
            'requirement' => $this->request->getPost('requirement')
        );

        $entry = (new RequirementSubmission())->where('student',$student->id)->where('session',active_session())->where('requirement',$this->request->getPost('requirement'))->get()->getRow();
        $db = \Config\Database::connect();
        $builder = $db->table('requirements_submissions');
        if (isset($entry->id)){
            $builder->where('requirement',$this->request->getPost('requirement'));
            $builder->where('student',$student->id);
            $builder->where('session',active_session());
            if ($builder->update($to_db)){
                $return = TRUE;
                $msg = "Entry updated successfully";
            }else {
                $return = FALSE;
                $msg = "An error occurred";
            }
        }else {
            if ($model->save($to_db)) {
                $return = TRUE;
                $msg = "Entry saved successfully";
            } else {
                $return = FALSE;
                $msg = "An error occurred";
            }
        }

        $s = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $s,
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());

    }
    public function save()
    {
        $data = $this->request->getPost();
        $entity = new Requirement();
        $entity->fill($data);
        $entity->session = active_session();
        $model = new \App\Models\Requirements();
        $entity->class = (new \App\Models\Sections())->find($data['section'])->class->id;
        if ($model->save($entity)) {
            $return = TRUE;
            $msg = "Entry saved successfully";
        } else {
            $return = FALSE;
            $msg = "An error occurred";
        }

        $s = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $s,
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());
    }

    public function delete($id)
    {
        $model = new \App\Models\Requirements();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Requirement deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete payment";
        }

        $s = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $s,
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());
    }

    //Mark requirement as checked
    public function mark_checked($id)
    {
            $requirement = (new \App\Models\Requirements())->find($id);
            if (!$requirement) {
                return redirect()->to(previous_url())->with('error', "Requirement does not exist");
            }

            $student = (new Students())->find($this->request->getPost('student'));
            $model = new RequirementSubmission();
            $to_db = array(
                'teacher_comment' => $this->request->getPost('teacher_comment'),
                'teacher_check' => 1,
                'student' => $student->id,
                'class' => isset($student->class->id) ? $student->class->id : 0,
                'section' => isset($student->section->id) ? $student->section->id : 0,
                'session' => active_session(),
                'requirement' => $id
            );
            $entry = (new RequirementSubmission())->where('student',$student->id)->where('requirement',$id)->get()->getRow();

                if (isset($entry->id))
                    $to_db['id'] = $entry->id;
                if ($model->save($to_db)){
                    $return = TRUE;
                    $msg = "Entry saved successfully";
                }else {
                    $return = FALSE;
                    $msg = "An error occurred";
                }

        $s = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $s,
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

          return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());
    }
}