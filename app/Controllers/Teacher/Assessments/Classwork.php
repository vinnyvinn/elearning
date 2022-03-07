<?php


namespace App\Controllers\Teacher\Assessments;


use App\Models\Classes;
use App\Models\ClassSubjects;
use App\Models\ClassWorkItems;
use App\Models\Sections;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Session\Session;

class Classwork extends \App\Controllers\TeacherController
{
    /** @var Session */
    public $session;

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Class Work";

        return $this->_renderPage('Academic/Assessments/Classwork/index', $this->data);
    }

    public function view($id)
    {
        $classwork = (new \App\Models\ClassWorkItems())->find($id);
        if(!$classwork) {
            return $this->response->setStatusCode(404)->setBody("Class Work not found");
        }
        $this->data['site_title'] = $classwork->name;
        $this->data['classwork'] = $classwork;

        return $this->_renderPage('Academic/Assessments/Classwork/view', $this->data);
    }

    public function results($id)
    {
        $classwork = (new \App\Models\ClassWorkItems())->find($id);
        if(!$classwork) {
            return $this->response->setStatusCode(404)->setBody("Class Work not found");
        }
        $this->data['site_title'] = $classwork->name;
        $this->data['classwork'] = $classwork;

        return $this->_renderPage('Academic/Assessments/Classwork/results', $this->data);
    }

    public function viewClasswork($classwork)
    {
        if($post = $this->request->getPost()) {
            $class = $this->request->getPost('class');
            $subject = $this->request->getPost('subject');
            $class = (new Classes())->find($class);
            $existing = (new \App\Models\ClassWorkItems())
                ->where('id', $classwork)
                ->where('class', $class->id)->where('subject', $subject)->get()->getLastRow('\App\Entities\ClassWorkItems');

            if(!$existing) {
                $resp = [
                    'status' => 'error',
                    'title' => 'Status',
                    'message' => "No classwork found",
                    'notifyType' => 'toastr',
                ];
                return $this->response->setStatusCode(404)->setBody("No classwork found");
            }
            $this->data['classwork'] = $existing;

            return view('Teacher/Academic/Assessments/Classwork/view_quiz', $this->data);
        } else {
            return $this->response->setStatusCode(404)->setBody("Bad request");
        }
    }

    public function printClasswork($classwork)
    {
        $existing = (new \App\Models\ClassWorkItems())->where('id', $classwork)->get()->getFirstRow('\App\Entities\ClassWorkItems');

            if(!$existing) {
                $resp = [
                    'status' => 'error',
                    'title' => 'Status',
                    'message' => "No classwork found",
                    'notifyType' => 'toastr',
                ];
                return $this->response->setStatusCode(404)->setBody("No classwork found");
            }
            $this->data['classwork'] = $existing;

            return view('Teacher/Academic/Assessments/Classwork/print_quiz', $this->data);
    }

    //May not be in use
    public function create()
    {
        $return = FALSE;
        $msg = "Invalid request";
        if($this->request->getPost()) {
            $entity = new \App\Entities\ClassWork();
            $model = new \App\Models\ClassWork();
            $entity->fill($this->request->getPost());

            if($model->save($entity)) {
                $return = true;
                $msg = "Classwork saved successfully";
            } else {
                $return = FALSE;
                $msg = "Failed to save classwork";
                if(is_array($model->errors())) {
                    $msg = implode('<br/', $model->errors());
                }
            }
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Status',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'getClassWork()' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(current_url())->withInput();
        }
    }

    public function get()
    {
        $class = $this->request->getPost('class');
        $semester = $this->request->getPost('semester');
        $subject = $this->request->getPost('subject');
        $class = (new Classes())->find($class);
        if(!$class) {
            return $this->response->setStatusCode(404)->setBody("Class not found");
        }
        $classworks = new \App\Models\ClassWorkItems();
        $works = $classworks->where('subject', $subject)->where('semester', $semester)
            ->where('session', @getSession()->id)
            ->where('class', $class->id)->orderBy('id', 'DESC')->findAll();

        return view('Teacher/Academic/Assessments/Classwork/get', ['classworks' => $works]);
    }

    public function newClasswork()
    {
        $this->data['site_title'] = 'New Classwork';
        //$this->data['classwork'] = $classwork;

        return $this->_renderPage('Academic/Assessments/Classwork/create', $this->data);
    }

    public function editClasswork($classwork)
    {
        $classwork = (new ClassWorkItems())->find($classwork);
        if(!$classwork) {
            return redirect()->back()->with('error', "Classwork not found");
        }

        $this->data['site_title'] = 'Edit '.$classwork->name;
        $this->data['classwork'] = $classwork;

        return $this->_renderPage('Academic/Assessments/Classwork/edit', $this->data);
    }

    //May not be in use
    public function newClassworkCreate($classworkId)
    {
        $classwork = (new \App\Models\ClassWork())->find($classworkId);
        if(!$classwork) {
            return $this->response->setStatusCode(404)->setBody("Class Work not found");
        }
        $subject = (new ClassSubjects())->find($this->request->getPost('subject'));
        $data = [
            'classwork' => $classwork,
            //'section'   => $this->request->getPost('section'),
            'subject'   => $subject
        ];

        return view('Teacher/Academic/Assessments/Classwork/new', $data);
    }

    //Saving new classwork [NEW]
    public function saveNewClasswork()
    {
        $data = $this->request->getJSON();
        $mega = [];
        $msg = "Some questions may not have answers or the entire set is empty";
        $return = FALSE;
        $keys = ['A', 'B', 'C', 'D'];
        //print_r($data->classwork[0]);
        if($data && is_object($data)) {
            $out_of = $data->out_of;
            $cDuration = $data->duration;
            $cName = $data->name;
            $cClass = $data->class;
            $cSemester = $data->semester;
            $cSubject = $data->subject;
            $cGiven = $data->given;
            $cDeadline = $data->deadline;
            //$class = $data->classid;
            $subject = $data->subject;
            $questions = $data->classwork;
            foreach ($questions as $question) {
                $correct_exists = FALSE;
                if($question->question && $question->question != '') {
                    //$corrects = $question->correct;
                    //$answers = $question->answers;
                    foreach ($keys as $key) {
                        $cstr = "correct[".$key."]";
                        $astr = "answers[".$key."]";
                        if((isset($question->$cstr) && $question->$cstr == true && isset($question->$astr) && $question->$astr != '')) {
                            $correct_exists = TRUE;
                        }
                        $corrects[$key] = isset($question->$cstr) && $question->$cstr == true;
                        $answers[$key] = $question->$astr;
                    }
                    if(isset($answers) && is_array($answers) && isset($corrects) && is_array($corrects)) {
                        $data = [
                            'question' => $question->question,
                            'answers'  => $answers,
                            'corrects' => $corrects,
                            'explanation'   => $question->explanation,
                        ];
                        if(isset($question->image) && $question->image != '') {
                            //TODO: Upload this image
                            $data['image'] = $question->image;
                        }
                        if($correct_exists) {
                            array_push($mega, $data);
                            $return = TRUE;
                            $msg = "Classwork saved successfully";
                        } else {
                            $return = FALSE;
                            $msg = "Some questions have no correct answer selected";
                        }
                    } else {
                        $return = FALSE;
                        $msg = "Answers not set";
                    }
                }
            }
        } else {
            $return = FALSE;
            $msg = "Empty or invalid data";
        }

        if($return) {
            //Save $mega to database
            if(count($mega) > 0) {
                $to_db = [
                    'name' => $cName,
                    'semester' => $cSemester,
                    'given' => $cGiven,
                    'deadline' => $cDeadline,
                    'out_of' => $out_of,
                    'duration' => $cDuration,
                    'class' => $cClass,
                    'section' => null,
                    'items' => json_encode($mega),
                    'subject' => $subject,
                    'class_work' => $cName,
                    'session'   => @getSession()->id
                ];
                $model = new ClassWorkItems();
//                if($ext = $model->where('class_work', $classwork->id)->where('class', $classwork->class->id)->where('subject', $subject)->get()->getLastRow()) {
//                    $to_db['id'] = $ext->id;
//                    if($model->save($to_db)) {
//                        $return = TRUE;
//                        $msg = "Classwork updated successfully";
//                    } else {
//                        $return = FALSE;
//                        $msg = "A database error occurred";
//                    }
//                } else {
                if ($model->save($to_db)) {
                    $return = TRUE;
                    $msg = "Classwork saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "A database error occurred";
                }
//                }
            } else {
                $return = FALSE;
                $msg = "Cannot save empty data";
            }
        }


        //print_r($data);
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Status',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? '' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(site_url(route_to('teacher.academic.assessments.class_work.new_classwork_create')))->withInput();
        }
    }

    //Saving edited classwork
    public function saveEditClasswork($id)
    {
        $data = $this->request->getJSON();
        $mega = [];
        $msg = "Some questions may not have answers or the entire set is empty";
        $return = FALSE;
        $keys = ['A', 'B', 'C', 'D'];
        //print_r($data->classwork[0]);
        if($data && is_object($data)) {
            $out_of = $data->out_of;
            $cDuration = $data->duration;
            $cName = $data->name;
            $cClass = $data->class;
            $cSemester = $data->semester;
            $cSubject = $data->subject;
            $cGiven = $data->given;
            $cDeadline = $data->deadline;
            //$class = $data->classid;
            $subject = $data->subject;
            $questions = $data->classwork;
            foreach ($questions as $question) {
                $correct_exists = FALSE;
                if($question->question && $question->question != '') {
                    //$corrects = $question->correct;
                    //$answers = $question->answers;
                    foreach ($keys as $key) {
                        $cstr = "correct[".$key."]";
                        $astr = "answers[".$key."]";
                        if((isset($question->$cstr) && $question->$cstr == true && isset($question->$astr) && $question->$astr != '')) {
                            $correct_exists = TRUE;
                        }
                        $corrects[$key] = isset($question->$cstr) && $question->$cstr == true;
                        $answers[$key] = $question->$astr;
                    }
                    if(isset($answers) && is_array($answers) && isset($corrects) && is_array($corrects)) {
                        $data = [
                            'question' => $question->question,
                            'answers'  => $answers,
                            'corrects' => $corrects,
                            'explanation'   => $question->explanation,
                        ];
                        if(isset($question->image) && $question->image != '') {
                            //TODO: Upload this image
                            $data['image'] = $question->image;
                        }
                        if($correct_exists) {
                            array_push($mega, $data);
                            $return = TRUE;
                            $msg = "Classwork saved successfully";
                        } else {
                            $return = FALSE;
                            $msg = "Some questions have no correct answer selected";
                        }
                    } else {
                        $return = FALSE;
                        $msg = "Answers not set";
                    }
                }
            }
        } else {
            $return = FALSE;
            $msg = "Empty or invalid data";
        }

        if($return) {
            //Save $mega to database
            if(count($mega) > 0) {
                $to_db = [
                    'name' => $cName,
                    'semester' => $cSemester,
                    'given' => $cGiven,
                    'deadline' => $cDeadline,
                    'out_of' => $out_of,
                    'duration' => $cDuration,
                    'class' => $cClass,
                    'section' => null,
                    'items' => json_encode($mega),
                    'subject' => $subject,
                    'class_work' => $cName,
                    'session'   => @getSession()->id
                ];
                $model = new ClassWorkItems();
//                if($ext = $model->where('class_work', $classwork->id)->where('class', $classwork->class->id)->where('subject', $subject)->get()->getLastRow()) {
//                    $to_db['id'] = $ext->id;
//                    if($model->save($to_db)) {
//                        $return = TRUE;
//                        $msg = "Classwork updated successfully";
//                    } else {
//                        $return = FALSE;
//                        $msg = "A database error occurred";
//                    }
//                } else {
                $to_db['id'] = $id;
                try {
                    if ($model->save($to_db)) {
                        $return = TRUE;
                        $msg = "Classwork saved successfully";
                    } else {
                        $return = FALSE;
                        $msg = "A database error occurred";
                    }
                } catch (\ReflectionException $e) {
                    $return = FALSE;
                    $msg = $e->getMessage();
                }
//                }
            } else {
                $return = FALSE;
                $msg = "Cannot save empty data";
            }
        }


        //print_r($data);
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Status',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location = "'.site_url(route_to('teacher.academic.assessments.class_work.view', $id)).'"' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(site_url(route_to('teacher.academic.assessments.class_work.new_classwork_create')))->withInput();
        }
    }

    public function delete($id)
    {
        if((new \App\Models\ClassWorkItems())->delete($id)) {
            $return = TRUE;
            $msg = "Deleted successfully";
        } else{
            $return = FALSE;
            $msg = "Failed to delete the classwork";
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Status',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'getClassWork()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);

            return $this->response->redirect(site_url(route_to('teacher.academic.assessments.class_work.new_classwork_create')))->withInput();
        }
    }
}