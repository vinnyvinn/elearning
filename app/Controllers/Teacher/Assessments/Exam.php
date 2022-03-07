<?php


namespace App\Controllers\Teacher\Assessments;


use App\Entities\CatExam;
use App\Models\CatExams;
use App\Models\CatExamItems;
use App\Models\Classes;
use App\Models\ClassSubjects;
use App\Models\Sections;
use CodeIgniter\Services;
use CodeIgniter\Session\Session;

class Exam extends \App\Controllers\TeacherController
{
    /** @var Session */
    public $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = Services::session();
    }

    public function index()
    {
        $this->data['site_title'] = "Exam";

        return $this->_renderPage('Academic/Assessments/Exams/index', $this->data);
    }

    public function results($id)
    {
        $classwork = (new \App\Models\CatExamItems())->find($id);
        if(!$classwork) {
            return $this->response->setStatusCode(404)->setBody("Exam not found");
        }
        $this->data['site_title'] = $classwork->name;
        $this->data['exam'] = $classwork;

        return $this->_renderPage('Academic/Assessments/Exams/results', $this->data);
    }

    public function create()
    {
        $return = FALSE;
        $msg = "Invalid request";
        if ($this->request->getPost()) {
            $entity = new CatExam();
            $model = new \App\Models\CatExams();
            $entity->fill($this->request->getPost());

            try {
                if ($model->save($entity)) {
                    $return = true;
                    $msg = "Exam saved successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to save the exam";
                    if (is_array($model->errors())) {
                        $msg = implode('<br/', $model->errors());
                    }
                }
            } catch (\ReflectionException $e) {
                $return = FALSE;
                $msg = $e->getMessage();
            }
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Status',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'getCATExam()' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(previous_url())->withInput();
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

        $quizes = (new \App\Models\CatExamItems())
            ->where('session', active_session())
            ->where('subject', $subject)
            ->where('semester', $semester)
            ->where('class', $class->id)->orderBy('id', 'DESC')->findAll();
        $data = [
            'exams'   => $quizes
        ];
        return view('Teacher/Academic/Assessments/Exams/get', $data);
    }

    public function delete($id)
    {
        if((new \App\Models\CatExams())->delete($id)) {
            $return = TRUE;
            $msg = "Deleted successfully";
        } else{
            $return = FALSE;
            $msg = "Failed to delete the exam";
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Status',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'getCATExam()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);

            return $this->response->redirect(site_url(route_to('teacher.academic.assessments.exam')))->withInput();
        }
    }

    public function newExam()
    {
        $this->data['site_title'] = "New Exam";
        //$this->data['exam'] = $exam;

        return $this->_renderPage('Academic/Assessments/Exams/create', $this->data);
    }

    public function editExam($id)
    {
        $exam = (new CatExamItems())->find($id);
        if(!$exam) {
            return redirect()->back()->with('error', "Exam not found");
        }

        $this->data['site_title'] = "Edit ".$exam->name;
        $this->data['exam'] = $exam;

        return $this->_renderPage('Academic/Assessments/Exams/edit', $this->data);
    }

    public function createNewExam($id)
    {
        $exam = (new \App\Models\CatExams())->find($id);
        if(!$exam) {
            return $this->response->setStatusCode(404)->setBody("Exam not found");
        }
        $subject = $this->request->getPost('subject');
        $class = $this->request->getPost('class');
        $subject = (new ClassSubjects())->find($subject);
        if(!$subject) {
            return $this->response->setStatusCode(404)->setBody("Subject not found");
        }
        $class = (new Classes())->find($class);
        if(!$class) {
            return $this->response->setStatusCode(404)->setBody("Section not found");
        }
        $data = [
            'exam'      => $exam,
            'subject'   => $subject,
            'class'   => $class
        ];
        return view('Teacher/Academic/Assessments/Exams/new', $data);
    }

    public function saveNewExam()
    {

        $data = $this->request->getJSON();
        $mega = [];
        $msg = "Some questions may not have answers or the entire set is empty";
        $return = FALSE;
        $keys = ['A', 'B', 'C', 'D'];
        //print_r($data->quiz[0]);
        if($data && is_object($data)) {
            $out_of = $data->out_of;
            $duration = $data->duration;
            $cName = $data->name;
            $cClass = $data->class;
            $cSemester = $data->semester;
            $cSubject = $data->subject;
            $cGiven = $data->given;
            $cDeadline = $data->deadline;
            //$class = $data->classid;
            $subject = $data->subject;
            $questions = $data->exam;
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
                            $msg = "Exam saved successfully";
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
                    'class' => $cClass,
                    'out_of'    => $out_of,
                    'duration'  => $duration,
                    //'section'   => $section,
                    'items'     => json_encode($mega),
                    'subject'   => $subject,
                    'cat_exam'  => $cName,
                    'session'   => active_session()
                ];
                $model = new CatExamItems();

                try {
                    if ($model->save($to_db)) {
                        $return = TRUE;
                        $msg = "Exam saved successfully";
                        $primaryID = $model->getInsertID();
                    } else {
                        $return = FALSE;
                        $msg = "A database error occurred";
                    }
                } catch (\ReflectionException $e) {
                    $return = FALSE;
                    $msg = $e->getMessage();
                }

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
                'callback' => $return ? 'window.location = "'.site_url(route_to('teacher.academic.assessments.exams.view', $primaryID)).'"' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(site_url(route_to('teacher.academic.assessments.exams.new_exam')))->withInput();
        }
    }

    public function saveEditExam($id)
    {

        $data = $this->request->getJSON();
        $mega = [];
        $msg = "Some questions may not have answers or the entire set is empty";
        $return = FALSE;
        $keys = ['A', 'B', 'C', 'D'];
        //print_r($data->quiz[0]);
        if($data && is_object($data)) {
            $out_of = $data->out_of;
            $duration = $data->duration;
            $cName = $data->name;
            $cClass = $data->class;
            $cSemester = $data->semester;
            $cSubject = $data->subject;
            $cGiven = $data->given;
            $cDeadline = $data->deadline;
            //$class = $data->classid;
            $subject = $data->subject;
            $questions = $data->exam;
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
                            $msg = "Exam saved successfully";
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
                    'id'    => $id,
                    'name' => $cName,
                    'semester' => $cSemester,
                    'given' => $cGiven,
                    'deadline' => $cDeadline,
                    'class' => $cClass,
                    'out_of'    => $out_of,
                    'duration'  => $duration,
                    //'section'   => $section,
                    'items'     => json_encode($mega),
                    'subject'   => $subject,
                    'cat_exam'  => $cName,
                    'session'   => active_session()
                ];
                $model = new CatExamItems();

                try {
                    if ($model->save($to_db)) {
                        $return = TRUE;
                        $msg = "Exam saved successfully";
                        //$primaryID = $model->getInsertID();
                    } else {
                        $return = FALSE;
                        $msg = "A database error occurred";
                    }
                } catch (\ReflectionException $e) {
                    $return = FALSE;
                    $msg = $e->getMessage();
                }

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
                'callback' => $return ? 'window.location = "'.site_url(route_to('teacher.academic.assessments.exams.view', $id)).'"' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(site_url(route_to('teacher.academic.assessments.exams.view', $id)))->withInput();
        }
    }

    public function view($id)
    {
        $classwork = (new \App\Models\CatExamItems())->find($id);
        if(!$classwork) {
            //return $this->response->setStatusCode(404)->setBody("Class Work not found");
            return redirect()->back()->with('error', "Exam not found");
        }
        $this->data['site_title'] = $classwork->name;
        $this->data['exam'] = $classwork;

        return $this->_renderPage('Academic/Assessments/Exams/view', $this->data);
    }

    public function viewExam($id)
    {
        if($post = $this->request->getPost()) {
            $class = $this->request->getPost('class');
            $subject = $this->request->getPost('subject');
            $class = (new Classes())->find($class);
            $existing = (new \App\Models\CatExamItems())
                ->where('exam', $id)
                ->where('class', $class->id)->where('subject', $subject)->get()->getFirstRow('\App\Entities\CatExamItem');

            if(!$existing) {
                $resp = [
                    'status' => 'error',
                    'title' => 'Status',
                    'message' => "No exam found",
                    'notifyType' => 'toastr',
                ];
                return $this->response->setStatusCode(404)->setBody("No exam found");
            }
            $this->data['exam'] = $existing;

            return view('Teacher/Academic/Assessments/Exams/view_exam', $this->data);
        } else {
            return $this->response->setStatusCode(404)->setBody("Bad request");
        }
    }

    public function printExam($id)
    {

            $existing = (new \App\Models\CatExamItems())->where('id', $id)->get()->getFirstRow('\App\Entities\CatExamItem');

            if(!$existing) {
                $resp = [
                    'status' => 'error',
                    'title' => 'Status',
                    'message' => "No exam found",
                    'notifyType' => 'toastr',
                ];
                return $this->response->setStatusCode(404)->setBody("No exam found");
            }
            $this->data['exam'] = $existing;

            return view('Teacher/Academic/Assessments/Exams/print_exam', $this->data);
    }
}