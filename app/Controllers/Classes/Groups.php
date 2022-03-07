<?php


namespace App\Controllers\Classes;


use App\Controllers\AdminController;
use App\Entities\ClassGroup;
use App\Models\ClassGroups;
use App\Models\SectionGroups;

class Groups extends Sections
{
    public function __construct()
    {
        parent::__construct();
    }

    public function view($id) //Class Section ID
    {
        $model = new \App\Models\Sections();
        $section = $model->find($id);
        if (!$section) {
            $this->session->setFlashData('error', 'Section not found');
            return $this->response->redirect(previous_url());
        }
        $data = [
            'section' => $section,
            'page' => 'groups',
            'title' => 'Student Groups'
        ];

        $this->_renderSection('Classes/Sections/Groups/view', $data);
    }

    public function createGroup($section_id)
    {
        $model = new \App\Models\Sections();
        $section = $model->find($section_id);
        if (!$section) {
            $return = FALSE;
            $msg = "Section does not exist";
        } else {
            $model = new ClassGroups();
            $entity = new ClassGroup();
            $entity->name = $this->request->getPost('name');
            //$entity->class = $section->class;
            $exist = $this->request->getPost('id');
            if($exist && is_numeric($exist)) {
                $entity->id = $exist;
            }
            $entity->section = $section->id;
            $sectionsModel = new SectionGroups();
            if($model->save($entity)) {
                if(isset($entity->id)) {
                    $id = $entity->id;
                    $sectionsModel->where('group', $id)->delete();
                } else {
                    $id = $model->getInsertID();
                }
                $return = TRUE;
                $msg = "Group created successfully";
                $students = $this->request->getPost('students');
                if($students && is_array($students)) {
                    $to_db = [];
                    foreach ($students as $student) {
                        $to_db[] = [
                            'group'     => $id,
                            'section'   => $section->id,
                            'student'   => $student
                        ];
                    }
                    @$sectionsModel->insertBatch($to_db);
                }
            } else {
                $return = FALSE;
                $msg = "Failed to create group";
            }
        }

        $status = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {

            $resp = [
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'status' => $status,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    public function getMembers()
    {
        $group = $this->request->getPost('id');
        $group = (new ClassGroups())->find($group);
        if(!$group) {
            return "Group does not exist";
        }
        $students = (new SectionGroups())->where('group', $group->id)->findAll();

        //return $this->response->setContentType('application/json')->setBody(json_encode($students));
        $data = [
            'students' => $students,
            'group'    => $group
        ];
        return view('Classes/Sections/Groups/students', $data);
    }

    public function deleteGroup($id)
    {
        $model = new ClassGroups();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Group deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete class group";
        }

        $status = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {

            $resp = [
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'status' => $status,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }
}