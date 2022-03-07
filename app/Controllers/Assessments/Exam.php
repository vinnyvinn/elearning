<?php


namespace App\Controllers\Assessments;


use App\Entities\CatExam;
use App\Models\AnswerOption;
use App\Models\CatExams;
use App\Models\CatExamItems;
use App\Models\Classes;
use App\Models\ClassSubjects;
use App\Models\QuizItems;
use App\Models\Sections;
use CodeIgniter\Services;
use CodeIgniter\Session\Session;

class Exam extends \App\Controllers\AdminController
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


        $model = (new \App\Models\CatExamItems());
        if ($subject !=='all')
            $model->where('subject',$subject);
        $quizes = $model->where('session', active_session())
            ->where('semester', $semester)
            ->where('class', $class->id)->orderBy('id', 'DESC')->findAll();
        $data = [
            'exams'   => $quizes
        ];
        return view('Academic/Assessments/Exams/get', $data);
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

    public function delete($id)
    {
        $return = false;
        $msg = "This Exam cannot be deleted";
//        if((new \App\Models\CatExams())->delete($id)) {
//            $return = TRUE;
//            $msg = "Deleted successfully";
//        } else{
//            $return = FALSE;
//            $msg = "Failed to delete the exam";
//        }

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

            return $this->response->redirect(site_url(route_to('admin.academic.assessments.exam')))->withInput();
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
        return view('Academic/Assessments/Exams/new', $data);
    }

    public function saveNewExam()
    {
        $requests = $this->request->getPost();
        $questions_data = array();
        $mega = [];
        $msg = "Exam saved successfully";
        $return = true;
//
//        $msg = "Assignment saved successfully";
//        $return = true;
//        $data = $this->request->getJSON();
//        $mega = [];
//        $msg = "Some questions may not have answers or the entire set is empty";
////       $return = FALSE;
        $options = (new AnswerOption())->findAll();
        //$keys = ['A', 'B', 'C', 'D'];
        $keys = [];
        foreach ($options as $option){
            array_push($keys,$option['name']);
        }
        //print_r($data->classwork[0]);
        if($requests) {
            $out_of = $requests['out_of'];
            $cDuration = $requests['duration'];
            $cName = $requests['name'];
            $cClass = $requests['class'];
            $cSemester = $requests['semester'];
            $cSubject = $requests['subject'];
            $cGiven = $requests['given'];
            $cDeadline = $requests['deadline'];
            //$class = $data->classid;

            foreach ($requests['question'] as $k1 => $quiz){
                if (!isset($questions_data[$k1])){
                    $arr = array('question' => $quiz);
                    $questions_data[$k1] = array();
                    array_push( $questions_data[$k1],$arr);
                    if ($quiz ==''){
                        $return = false;
                        $msg = "Question cannot be empty.";
                    }
                    if (!isset($requests['correct'][$k1])){
                        $return = false;
                        $msg = "Correct Answer cannot be empty.";
                    }else{

                        $arr = array('corrects' => json_encode(array_keys(array_flatten($requests['correct'][$k1]))));
                        array_push( $questions_data[$k1],$arr);
                    }
                }
            }

            $qn = array();
            foreach ($requests['question_number'] as $k2 => $value){
                $arr = array('question_number' => $value);
                array_push($questions_data[$k2],$arr);
                if (isset($qn[$value])){
                    $return = false;
                    $msg = "Duplicates Question numbers not allowed.";
                }
                if ($value ==''){
                    $return = false;
                    $msg = "Question number cannot be empty.";
                }

            }

            foreach ($requests['instructions'] as $k3 => $n){
                $arr = array('instructions' => $n);
                array_push($questions_data[$k3],$arr);
            }

            foreach ($requests['precautions'] as $k4 => $ins){
                $arr = array('precautions' => $ins);
                array_push($questions_data[$k4],$arr);
            }

            foreach ($requests['explanation'] as $k5 => $exp){
                $arr = array('explanation' => $exp);
                array_push($questions_data[$k5],$arr);
                if ($exp ==''){
                    $return = false;
                    $msg = "Answer Explanation cannot be empty.";
                }
            }


            // $answers  =  array_splice($requests['answers'],0,count($options));
            // var_dump($requests);
            $answers = $requests['answers'];
            $chunked = array_chunk($answers,count($options));
            foreach ($chunked as $k8 => $item){
                $arr = array('answers' => json_encode($item));
                array_push($questions_data[$k8],$arr);
            }

            foreach ($questions_data as $dt){
                array_push($mega,array_flatten($dt));
            }
        } else {
            $return = FALSE;
            $msg = "Empty or invalid data";
        }
        $primaryID = '';
        if($return) {
            //Save $mega to database
            if(count($mega) > 0) {
                $to_db = [
                    'cat_exam' => $cName,
                    'name' => $cName,
                    'semester' => $cSemester,
                    'given' => $cGiven,
                    'deadline' => $cDeadline,
                    'out_of' => $out_of,
                    'duration' => $cDuration,
                    'class' => $cClass,
                    'section' => null,
                    'items' => json_encode($mega),
                    'subject' => $cSubject,
                    'class_work' => $cName,
                    'session'   => @getSession()->id,
                    'published' => (bool) $this->request->getPost('published') == 1 ? '1' : '0'
                ];
                $model = new CatExamItems();
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
                    $primaryID = $model->getInsertID();
                    $return = TRUE;
                    $msg = "Exam saved successfully";
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


        $t = $return ? 'success' : 'error';
        $this->session->setFlashData($t, $msg);
        if ($primaryID) {
            return $this->response->redirect(site_url(route_to('admin.academic.assessments.exams.new_exam')));
        }else {
            return $this->response->redirect(site_url(route_to('admin.academic.assessments.exams.new_exam')));
        }
    }

    public function markPublished($id)
    {
        $model = new CatExamItems();
        if($model->set('published', '1')->where('id', $id)->update()) {
            return redirect()->back()->with('success', 'Marked as published');
        }

        return redirect()->back()->with('error', 'Failed to publish');
    }

    public function markDraft($id)
    {
        $model = new CatExamItems();
        if($model->set('published', '0')->where('id', $id)->update()) {
            return redirect()->back()->with('success', 'Marked as draft');
        }

        return redirect()->back()->with('error', 'Failed to mark as draft');
    }

    public function saveEditExam($id)
    {

        $requests = $this->request->getPost();
        $questions_data = array();
        $mega = [];
        $msg = "Exam saved successfully";
        $return = true;
//
//        $msg = "Assignment saved successfully";
//        $return = true;
//        $data = $this->request->getJSON();
//        $mega = [];
//        $msg = "Some questions may not have answers or the entire set is empty";
////       $return = FALSE;
        $options = (new AnswerOption())->findAll();
        //$keys = ['A', 'B', 'C', 'D'];
        $keys = [];
        foreach ($options as $option){
            array_push($keys,$option['name']);
        }
        //print_r($data->classwork[0]);
        if($requests) {
            $out_of = $requests['out_of'];
            $cDuration = $requests['duration'];
            $cName = $requests['name'];
            $cClass = $requests['class'];
            $cSemester = $requests['semester'];
            $cSubject = $requests['subject'];
            $cGiven = $requests['given'];
            $cDeadline = $requests['deadline'];
            //$class = $data->classid;

            foreach ($requests['question'] as $k1 => $quiz){
                if (!isset($questions_data[$k1])){
                    $arr = array('question' => $quiz);
                    $questions_data[$k1] = array();
                    array_push( $questions_data[$k1],$arr);
                    if ($quiz ==''){
                        $return = false;
                        $msg = "Question cannot be empty.";
                    }
                    if (!isset($requests['correct'][$k1])){
                        $return = false;
                        $msg = "Correct Answer cannot be empty.";
                    }else{

                        $arr = array('corrects' => json_encode(array_keys(array_flatten($requests['correct'][$k1]))));
                        array_push( $questions_data[$k1],$arr);
                    }
                }
            }

            $qn = array();
            foreach ($requests['question_number'] as $k2 => $value){
                $arr = array('question_number' => $value);
                array_push($questions_data[$k2],$arr);
                if (isset($qn[$value])){
                    $return = false;
                    $msg = "Duplicates Question numbers not allowed.";
                }
                if ($value ==''){
                    $return = false;
                    $msg = "Question number cannot be empty.";
                }

            }

            foreach ($requests['instructions'] as $k3 => $n){
                $arr = array('instructions' => $n);
                array_push($questions_data[$k3],$arr);
            }

            foreach ($requests['precautions'] as $k4 => $ins){
                $arr = array('precautions' => $ins);
                array_push($questions_data[$k4],$arr);
            }

            foreach ($requests['explanation'] as $k5 => $exp){
                $arr = array('explanation' => $exp);
                array_push($questions_data[$k5],$arr);
                if ($exp ==''){
                    $return = false;
                    $msg = "Answer Explanation cannot be empty.";
                }
            }


            // $answers  =  array_splice($requests['answers'],0,count($options));
            // var_dump($requests);
            $answers = $requests['answers'];
            $chunked = array_chunk($answers,count($options));
            foreach ($chunked as $k8 => $item){
                $arr = array('answers' => json_encode($item));
                array_push($questions_data[$k8],$arr);
            }

            foreach ($questions_data as $dt){
                array_push($mega,array_flatten($dt));
            }
        } else {
            $return = FALSE;
            $msg = "Empty or invalid data";
        }
        $primaryID = '';
        if($return) {
            //Save $mega to database
            if(count($mega) > 0) {
                $to_db = [
                    'cat_exam' => $cName,
                    'name' => $cName,
                    'semester' => $cSemester,
                    'given' => $cGiven,
                    'deadline' => $cDeadline,
                    'out_of' => $out_of,
                    'duration' => $cDuration,
                    'class' => $cClass,
                    'section' => null,
                    'items' => json_encode($mega),
                    'subject' => $cSubject,
                    'class_work' => $cName,
                    'session'   => @getSession()->id,
                    'published' => (bool) $this->request->getPost('published') == 1 ? '1' : '0'
                ];
                $model = new CatExamItems();
//                if($ext = $model->where('class_work', $classwork->id)->where('class', $classwork->class->id)->where('subject', $subject)->get()->getLastRow()) {
                    $to_db['id'] = $id;
//                    if($model->save($to_db)) {
//                        $return = TRUE;
//                        $msg = "Classwork updated successfully";
//                    } else {
//                        $return = FALSE;
//                        $msg = "A database error occurred";
//                    }
//                } else {
                if ($model->save($to_db)) {
                    $primaryID = $model->getInsertID();
                    $return = TRUE;
                    $msg = "Exam saved successfully";
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


        $t = $return ? 'success' : 'error';
        $this->session->setFlashData($t, $msg);
      return $this->response->redirect(site_url(route_to('admin.academic.assessments.exams.view',$id)));

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

            return view('Academic/Assessments/Exams/view_exam', $this->data);
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

            return view('Academic/Assessments/Exams/print_exam', $this->data);
    }
}