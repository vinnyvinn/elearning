<?php


namespace App\Controllers\Teacher;


use App\Controllers\TeacherController;
use App\Entities\Assignment;
use App\Entities\Note;
use App\Entities\Subject;
use App\Entities\Subjectteacher;
use App\Models\Assignments;
use App\Models\ClassSubjects;
use App\Models\Notes;
use App\Models\Subjectteachers;
use CodeIgniter\Exceptions\PageNotFoundException;

class Subjects extends TeacherController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function view($subject, $section)
    {
        $section = (new \App\Models\Sections())->find($section);
        $subject = (new \App\Models\ClassSubjects())->find($subject);

        //if(!$subject || !$section || !$class) return new PageNotFoundException();

        $this->data['subject'] = $subject;
        $this->data['section'] = $section;
        $this->data['title'] = 'Overview';
        $this->data['page'] = 'overview';

        return $this->_renderSection('Subjects/view', $this->data);
    }

    public function notes($subject, $section)
    {
        $section = (new \App\Models\Sections())->find($section);
        //$subject = (new \App\Models\ClassSubjects())->find($subject);
        $subject = (new ClassSubjects())->where('class', $section->class->id)->find($subject);

        //Upload notes
        if($data = $this->request->getPost()) {
            $model = new Notes();
            $entity = new Note();
            $file = $this->request->getFile('file');

            if($file && $file->isValid() && !$this->request->getPost('id')) {
                $entity->fill($data);
                $newName = $file->getRandomName();
                $file->move(FCPATH.'uploads/notes', $newName);
                $entity->file = $newName;
                if($model->save($entity)) {
                    $success = TRUE;
                    $msg = "Notes uploaded successfully";
                } else {
                    $success = FALSE;
                    $msg = implode('<br/>', $model->errors());
                }
            } elseif($the_id = $this->request->getPost('id')) {
                if($file && $file->isValid()) {
                    $entity->fill($data);
                    @unlink($model->find($the_id)->path );
                    $newName = $file->getRandomName();
                    $file->move(FCPATH.'uploads/notes', $newName);
                    $entity->file = $newName;
                    if($model->save($entity)) {
                        $success = TRUE;
                        $msg = "Notes updated successfully";
                    } else {
                        $success = FALSE;
                        $msg = implode('<br/>', $model->errors());
                    }
                } else {
                    $entity->fill($data);
                    unset($entity->file);
                    if($model->save($entity)) {
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
            if($this->request->isAJAX()) {
                $resp = [
                    'status'    => $success ? 'success' : 'error',
                    'title'     => 'Notes Upload',
                    'message'   => $msg,
                    'notifyType'    => 'toastr',
                    'callback'  => $success ? 'window.location.reload()' : ''
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $success ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
                return $this->response->redirect(previous_url())->withInput();
            }
        }

        $this->data['subject'] = $subject;
        $this->data['section'] = $section;
        $this->data['title'] = 'Notes';
        $this->data['page'] = 'notes';

        return $this->_renderSection('Subjects/notes', $this->data);
    }

    public function delete_note($id)
    {
        $model = new Notes();
        $note = $model->find($id);
        if($note && $model->delete($note->id)) {
            @unlink($note->path);
            $s = TRUE;
            $msg = "File deleted successfully";
        } else {
            $s = FALSE;
            $msg = "Failed to delete file";
        }

        $s = $s ? 'success' : 'error';
        $this->session->setFlashData($s, $msg);

        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $s,
                'title'     => 'Delete Notes',
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'callback'  => 'window.location.reload()'
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());
    }

    public function assignments($subject, $section)
    {
        $section = (new \App\Models\Sections())->find($section);
        $subject = (new \App\Models\ClassSubjects())->find($subject);
        //dd($subject);

        //Upload assignment
        if($data = $this->request->getPost()) {
            $model = new Assignments();
            $entity = new Assignment();
            $file = $this->request->getFile('file');

            if($file && $file->isValid() && !$this->request->getPost('id')) {
                $entity->fill($data);
                $newName = $file->getRandomName();
                $file->move(FCPATH.'uploads/assignments', $newName);
                $entity->file = $newName;
                if($model->save($entity)) {
                    $success = TRUE;
                    $msg = "Notes uploaded successfully";
                } else {
                    $success = FALSE;
                    $msg = implode('<br/>', $model->errors());
                }
            } elseif($the_id = $this->request->getPost('id')) {
                if($file && $file->isValid()) {
                    $entity->fill($data);
                    @unlink($model->find($the_id)->path );
                    $newName = $file->getRandomName();
                    $file->move(FCPATH.'uploads/assignments', $newName);
                    $entity->file = $newName;
                    if($model->save($entity)) {
                        $success = TRUE;
                        $msg = "Notes updated successfully";
                    } else {
                        $success = FALSE;
                        $msg = implode('<br/>', $model->errors());
                    }
                } else {
                    $entity->fill($data);
                    unset($entity->file);
                    if($model->save($entity)) {
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
            if($this->request->isAJAX()) {
                $resp = [
                    'status'    => $success ? 'success' : 'error',
                    'title'     => 'Notes Upload',
                    'message'   => $msg,
                    'notifyType'    => 'toastr',
                    'callback'  => $success ? 'window.location.reload()' : ''
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $success ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
                return $this->response->redirect(current_url())->withInput();
            }
        }

        $this->data['subject'] = $subject;
        $this->data['section'] = $section;
        $this->data['title'] = 'Assignments';
        $this->data['page'] = 'assignments';

        return $this->_renderSection('Subjects/assignments', $this->data);
    }


    public function delete_assignment($id)
    {
        $model = new Assignments();
        $note = $model->find($id);
        if($note && $model->delete($note->id)) {
            @unlink($note->path);
            $s = TRUE;
            $msg = "Assignment deleted successfully";
        } else {
            $s = FALSE;
            $msg = "Failed to delete assignment";
        }

        $s = $s ? 'success' : 'error';
        $this->session->setFlashData($s, $msg);

        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $s,
                'title'     => 'Delete Assignment',
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'callback'  => 'window.location.reload()'
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());
    }

    /**
     * Render sections of the class view layout
     *
     * @param string $view
     * @param array $data
     * @return string
     */
    public function _renderSection($view, $data = []) {
        $html = view('Teacher/'.$view, $data);
        $dt = [
            'html'  => $html
        ];
        $data = array_merge($data, $dt);
        return $this->_renderPage('Subjects/layout', $data);
    }
}