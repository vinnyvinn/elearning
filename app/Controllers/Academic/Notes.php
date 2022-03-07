<?php


namespace App\Controllers\Academic;


use App\Controllers\AdminController;
use App\Entities\Note;
use App\Models\ClassSubjects;
use App\Models\Sections;

class Notes extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function notes()
    {
//        $subject = $this->request->getPost('subject');
//        $section = $this->request->getPost('section');
//        $section = (new Sections())->find($section);
//        //$subject = (new \App\Models\ClassSubjects)->find($subject);
//        $subject = (new ClassSubjects())->where('class', $section->class->id)->find($subject);
//        //dd($subject);
        //Upload notes
        if ($data = $this->request->getPost()) {
            $model = new \App\Models\Notes();
            $entity = new Note();
            $file = $this->request->getFile('file');

            if ($file && $file->isValid() && !$this->request->getPost('id')) {
                $entity->fill($data);
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/notes', $newName);
                $entity->file = $newName;
                if ($model->save($entity)) {
                    $success = TRUE;
                    $msg = "Notes uploaded successfully";
                } else {
                    $success = FALSE;
                    $msg = implode('<br/>', $model->errors());
                }
            } elseif ($the_id = $this->request->getPost('id')) {
                if ($file && $file->isValid()) {
                    $entity->fill($data);
                    @unlink($model->find($the_id)->path);
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/notes', $newName);
                    $entity->file = $newName;
                    if ($model->save($entity)) {
                        $success = TRUE;
                        $msg = "Notes updated successfully";
                    } else {
                        $success = FALSE;
                        $msg = implode('<br/>', $model->errors());
                    }
                } else {
                    $entity->fill($data);
                    unset($entity->file);
                    if ($model->save($entity)) {
                        $success = TRUE;
                        $msg = "Notes updated successfully";
                    } else {
                        $success = FALSE;
                        $msg = implode('<br/>', $model->errors());
                    }
                }
            } else {
                $success = FALSE;
                $msg = $file->getErrorString();
            }
            if ($this->request->isAJAX()) {
                $resp = [
                    'status' => $success ? 'success' : 'error',
                    'title' => 'Notes Upload',
                    'message' => $msg,
                    'notifyType' => 'toastr',
                    'callback' => $success ? 'getNotes()' : ''
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $success ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
                return $this->response->redirect(previous_url())->withInput();
            }
        }

//        $this->data['subject'] = $subject;
//        $this->data['section'] = $section;
        //$this->data['title'] = 'Notes';
        //$this->data['page'] = 'notes';

        return $this->_renderPage('Academic/Notes/index', $this->data);
    }

    public function elibraryNotes()
    {
        if ($data = $this->request->getPost()) {
            $model = new \App\Models\Notes();
            $entity = new Note();
            $file = $this->request->getFile('file');

            if ($file && $file->isValid() && !$this->request->getPost('id')) {
                $entity->fill($data);
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/notes', $newName);
                $entity->file = $newName;
                if ($model->save($entity)) {
                    $success = TRUE;
                    $msg = "Notes uploaded successfully";
                } else {
                    $success = FALSE;
                    $msg = implode('<br/>', $model->errors());
                }
            } elseif ($the_id = $this->request->getPost('id')) {
                if ($file && $file->isValid()) {
                    $entity->fill($data);
                    @unlink($model->find($the_id)->path);
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/notes', $newName);
                    $entity->file = $newName;
                    if ($model->save($entity)) {
                        $success = TRUE;
                        $msg = "Notes updated successfully";
                    } else {
                        $success = FALSE;
                        $msg = implode('<br/>', $model->errors());
                    }
                } else {
                    $entity->fill($data);
                    unset($entity->file);
                    if ($model->save($entity)) {
                        $success = TRUE;
                        $msg = "Notes updated successfully";
                    } else {
                        $success = FALSE;
                        $msg = implode('<br/>', $model->errors());
                    }
                }
            } else {
                $success = FALSE;
                $msg = $file->getErrorString();
            }
            if ($this->request->isAJAX()) {
                $resp = [
                    'status' => $success ? 'success' : 'error',
                    'title' => 'Notes Upload',
                    'message' => $msg,
                    'notifyType' => 'toastr',
                    'callback' => $success ? 'getNotes()' : ''
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $success ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
                return $this->response->redirect(previous_url())->withInput();
            }
        }

       return $this->_renderPage('Academic/Notes/e_index', $this->data);
    }

    public function getNotes()
    {
        $class = $this->request->getPost('class');

        $data = [
            'class' => $class
        ];

        return view('Academic/Notes/notes', $data);
    }

    public function getNotesE()
    {
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $type = $this->request->getPost('book_type');

        $data = [
            'class' => $class,
            'subject' => $subject,
            'book_type' => $type,
        ];


        return view('Academic/Notes/e_notes', $data);
    }
}