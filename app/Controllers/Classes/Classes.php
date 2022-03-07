<?php


namespace App\Controllers\Classes;


use App\Controllers\ProfileController;
use App\Entities\ClassSubject;
use App\Models\ClassSubjects;
use CodeIgniter\Exceptions\PageNotFoundException;

class Classes extends ProfileController
{
    /**
     * @var array|bool|object|null
     */
    private $activeSession;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->activeSession = getSession();
        if (!$this->activeSession) throw new PageNotFoundException();
        $data['classes'] = $this->activeSession->classes();
        return $this->_renderPage('Classes/index', $data);
    }

    public function view($id)
    {
        $this->activeSession = getSession();
        if (!$this->activeSession) throw new PageNotFoundException();
        $class = $this->activeSession->classes()->find($id);

        if (!$class) throw new PageNotFoundException();

        $data['class'] = $class;
        $data['page'] = 'overview';
        $data['title'] = 'Overview';

        return $this->_renderSection('Classes/view', $data);
    }

    /**
     * Render sections of the class view layout
     *
     * @param string $view
     * @param array $data
     * @return string
     */
    public function _renderSection($view, $data = [])
    {
        $html = view($view, $data);
        $dt = [
            'html' => $html
        ];
        $data = array_merge($data, $dt);
        return $this->_renderPage('Classes/layout', $dt);
    }

    /**
     * Get subjects of this class ID
     *
     * @param int $id the class ID
     * @return string
     */
    public function subjects($id)
    {
        $this->activeSession = getSession();
        if (!$this->activeSession) throw new PageNotFoundException();
        $class = $this->activeSession->classes()->find($id);
        if (!$class) throw new PageNotFoundException();
        
        $data['class'] = $class;
        $data['page'] = 'subjects';
        $data['title'] = 'Subjects';
        return $this->_renderSection('Classes/subjects', $data);

    }

    public function assignments($id)
    {
        $this->activeSession = getSession();
        if (!$this->activeSession) throw new PageNotFoundException();
        $class = $this->activeSession->classes()->find($id);
        if (!$class) throw new PageNotFoundException();

        $data['class'] = $class;
        $data['page'] = 'assignments';
        $data['title'] = 'Assignments';
        return $this->_renderSection('Classes/assignments', $data);

    }

    public function addSubject($class)
    {
        $return = TRUE;
        $request = $this->request->getPost();
        if ((isset($_POST['optional']) && $_POST['optional'] == 1) || (isset($_POST['kg']) && $_POST['kg'] == 1)) {
            $grading = [];
            foreach ($_POST['grade'] as $k => $g) {
                foreach ($_POST['scale'] as $s => $sk) {
                    if ($k == $s) {
                        if ($g == '') {
                            $return = FALSE;
                            $msg = "Grade field cannot be empty";
                        }
                        $num_arr = explode("-", $sk);
                        if (count($num_arr) != 2) {
                            $return = FALSE;
                            $msg = "The first and the second values in the scale must be numeric in the format '90-100'";
                        }
                        if (!is_numeric($num_arr[0]) || !is_numeric($num_arr[1])) {
                            $return = FALSE;
                            $msg = "The first and the second values in the scale must be numeric in the format '90-100'";
                        }
                        array_push($grading, array('key' => $k, 'grade' => $g, 'scale' => $sk));
                    }
                }
            }
            $request['grading'] = json_encode($grading);
        }

        $request['session'] = active_session();
        $class = (new \App\Models\Classes())->find($class);
        if ($class) {
            $subjects = $class->subjects;
            $existing = [];
            if ($subjects && count($subjects) > 0) {
                foreach ($subjects as $subject) {
                    $existing[] = $subject->subject;
                }
            }
            $newClass = $this->request->getPost('subject');
            if (in_array($newClass, $existing)) {
                $return = FALSE;
                $msg = "Subject is already added to this class";
            } elseif($return==TRUE) {
                $entity = (new ClassSubject())->fill($request);
                $model = new ClassSubjects();
                if ($model->save($entity)) {
                    $return = TRUE;
                    $msg = "Subject added successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to add subject to class";
                }
            }
        } else {
            $return = FALSE;
            $msg = "Class does not exist";
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

    public function editSubject($subject)
    {
        $return = TRUE;
        $request = $this->request->getPost();
        if ((isset($_POST['optional']) && $_POST['optional'] == 1) || (isset($_POST['kg']) && $_POST['kg'] == 1)) {
            $grading = [];
            foreach ($_POST['grade'] as $k => $g) {
                foreach ($_POST['scale'] as $s => $sk) {
                    if ($k == $s) {
                        if ($g == '') {
                            $return = FALSE;
                            $msg = "Grade field cannot be empty";
                        }
                        $num_arr = explode("-", $sk);
                        if (count($num_arr) != 2) {
                            $return = FALSE;
                            $msg = "The first and the second values in the scale must be numeric in the format '90-100'";
                        }
                        if (!is_numeric($num_arr[0]) || !is_numeric($num_arr[1])) {
                            $return = FALSE;
                            $msg = "The first and the second values in the scale must be numeric in the format '90-100'";
                        }
                        array_push($grading, array('key' => $k, 'grade' => $g, 'scale' => $sk));
                    }
                }
            }
            $request['grading'] = json_encode($grading);
        }

        if ($return == FALSE){
            $resp = [
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'status' => 'error',
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            $this->session->setFlashData($status, $msg);
            return $this->response->redirect(previous_url());
        }

         $subject = (new ClassSubjects())->find($subject);
         if ($subject) {
            $entity = (new ClassSubject())->fill($request);
            $entity->optional = $this->request->getPost('optional') ? 1 : 0;
            $entity->kg = $this->request->getPost('kg') ? 1 : 0;
            $entity->session = active_session();
            $model = new ClassSubjects();
            if ($model->save($entity)) {
                $return = TRUE;
                $msg = "Subject updated successfully";
            } else {
                $return = FALSE;
                $msg = "Failed to update subject";
            }

        } else {
            $return = FALSE;
            $msg = "Subject is not registered in this class";
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

    public function deleteSubject($id)
    {
        $subject = (new ClassSubjects())->find($id);
        if ($subject) {

            $model = new ClassSubjects();
            if ($model->delete($subject->id)) {
                $return = TRUE;
                $msg = "Subject deleted successfully";
            } else {
                $return = FALSE;
                $msg = "Failed to delete subject";
            }

        } else {
            $return = FALSE;
            $msg = "Subject is not registered in this class";
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

    /**
     * Get events for the class ID
     *
     * @param int $id Classes Id
     * @return string
     */
    public function events($id)
    {
        $this->activeSession = getSession();
        if (!$this->activeSession) throw new PageNotFoundException();
        $class = $this->activeSession->classes()->find($id);
        if (!$class) throw new PageNotFoundException();

        $data['class'] = $class;
        $data['page'] = 'events';
        $data['title'] = 'Events';
        return $this->_renderSection('Classes/events', $data);

    }

    public function examSchedule($id)
    {
        $this->activeSession = getSession();
        if (!$this->activeSession) throw new PageNotFoundException();
        $class = $this->activeSession->classes()->find($id);
        if (!$class) throw new PageNotFoundException();

        $data['class'] = $class;
        $data['page'] = 'exam-schedule';
        $data['title'] = 'Exam Schedule';
        return $this->_renderSection('Classes/exam-schedule', $data);

    }

    /**
     * Show routines for the class
     *
     * @param int $id the class ID
     * @return string
     */
    public function routines($id)
    {
        $this->activeSession = getSession();
        if (!$this->activeSession) throw new PageNotFoundException();
        $class = $this->activeSession->classes()->find($id);
        if (!$class) throw new PageNotFoundException();

        $data['class'] = $class;
        $data['page'] = 'routines';
        $data['title'] = 'Routines';
        return $this->_renderSection('Classes/routines', $data);

    }

    /**
     * Show payments and requirements for this class ID
     *
     * @param int $id class ID
     * @return string
     */
    public function requirements($id)
    {
        $this->activeSession = getSession();
        if (!$this->activeSession) throw new PageNotFoundException();
        $class = $this->activeSession->classes()->find($id);
        if (!$class) throw new PageNotFoundException();

        $data['class'] = $class;
        $data['page'] = 'requirements';
        $data['title'] = 'Payments and Requirements';
        return $this->_renderSection('Classes/requirements', $data);

    }

    /**
     * Show students for this class ID
     *
     * @param int $id the class ID
     * @return string
     */
    public function students($id)
    {
        $this->activeSession = getSession();
        if (!$this->activeSession) throw new PageNotFoundException();
        $class = $this->activeSession->classes()->find($id);
        if (!$class) throw new PageNotFoundException();

        $data['class'] = $class;
        $data['page'] = 'students';
        $data['title'] = 'Students';
        return $this->_renderSection('Classes/students', $data);

    }

    public function notes($id)
    {
        $this->activeSession = getSession();
        if (!$this->activeSession) throw new PageNotFoundException();
        $class = $this->activeSession->classes()->find($id);
        if (!$class) throw new PageNotFoundException();

        $data['class'] = $class;
        $data['page'] = 'notes';
        $data['title'] = 'Class Notes';
        return $this->_renderSection('Classes/notes', $data);
    }
}