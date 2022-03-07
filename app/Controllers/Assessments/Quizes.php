<?php


namespace App\Controllers\Assessments;


use App\Controllers\AdminController;
use App\Entities\Quiz;
use App\Models\AnswerOption;
use App\Models\Classes;
use App\Models\ClassSubjects;
use App\Models\ClassWorkItems;
use App\Models\QuizItems;
use App\Models\Sections;

class Quizes extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = "Quizes";

        return $this->_renderPage('Academic/Assessments/Quizes/index', $this->data);
    }

    public function view($id)
    {
        $quiz = (new \App\Models\QuizItems())->find($id);
        if(!$quiz) {
            return redirect()->back()->with('error', "Quiz does not exist");
        }

        $this->data['site_title'] = $quiz->name;
        $this->data['site_description'] = "View Quiz";
        $this->data['quiz'] = $quiz;

        return $this->_renderPage('Academic/Assessments/Quizes/view', $this->data);
    }

    public function results($id)
    {
        $classwork = (new \App\Models\QuizItems())->find($id);
        if(!$classwork) {
            return $this->response->setStatusCode(404)->setBody("Quiz not found");
        }
        $this->data['site_title'] = $classwork->name;
        $this->data['quiz'] = $classwork;

        return $this->_renderPage('Academic/Assessments/Quizes/results', $this->data);
    }

    public function edit($id)
    {
        $quiz = (new QuizItems())->find($id);
        if(!$quiz) {
            return redirect()->back()->with('error', "Quiz not found");
        }

        $this->data['site_title'] = "New Quiz";
        $this->data['quiz'] = $quiz;

        return $this->_renderPage('Academic/Assessments/Quizes/edit', $this->data);
    }

    public function viewQuiz($id)
    {
        if($post = $this->request->getPost()) {
            $class = $this->request->getPost('class');
            $subject = $this->request->getPost('subject');
            $class = (new Classes())->find($class);
            $existing = (new \App\Models\QuizItems())
                ->where('quiz', $id)
                ->where('class', $class->id)->where('subject', $subject)->get()->getFirstRow('\App\Entities\QuizItem');

            if(!$existing) {
                $resp = [
                    'status' => 'error',
                    'title' => 'Status',
                    'message' => "No quiz found",
                    'notifyType' => 'toastr',
                ];
                return $this->response->setStatusCode(404)->setBody("No quiz found");
            }
            $this->data['quiz'] = $existing;

            return view('Academic/Assessments/Quizes/view_quiz', $this->data);
        } else {
            return $this->response->setStatusCode(404)->setBody("Bad request");
        }
    }

    public function printQuiz($quiz)
    {
        //if($post = $this->request->getPost()) {
            $existing = (new \App\Models\QuizItems())->where('id', $quiz)->get()->getFirstRow('\App\Entities\QuizItem');

            if(!$existing) {
                $resp = [
                    'status' => 'error',
                    'title' => 'Status',
                    'message' => "No quiz found",
                    'notifyType' => 'toastr',
                ];
                return $this->response->setStatusCode(404)->setBody("No quiz found");
            }
            $this->data['quiz'] = $existing;

            return view('Academic/Assessments/Quizes/print_quiz', $this->data);
    }

    public function create()
    {
        $return = FALSE;
        $msg = "Invalid request";
        if ($this->request->getPost()) {
            $entity = new Quiz();
            $model = new \App\Models\Quizes();
            $entity->fill($this->request->getPost());

            if ($model->save($entity)) {
                $return = true;
                $msg = "Classwork saved successfully";
            } else {
                $return = FALSE;
                $msg = "Failed to save classwork";
                if (is_array($model->errors())) {
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
       if ($subject =="all") {
           $quizes = (new \App\Models\QuizItems())->where('class', $class->id)
               ->where('semester', $semester)
               ->where('session', @getSession()->id)
               ->orderBy('id', 'DESC')->findAll();
       }else {
           $quizes = (new \App\Models\QuizItems())->where('class', $class->id)
               ->where('subject', $subject)
               ->where('semester', $semester)
               ->where('session', @getSession()->id)
               ->orderBy('id', 'DESC')->findAll();
       }
        $data = [
            'quizes'   => $quizes
        ];
        return view('Academic/Assessments/Quizes/get', $data);
    }

    public function delete($id)
    {
        if((new \App\Models\QuizItems())->delete($id)) {
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

            return $this->response->redirect(site_url(route_to('admin.academic.assessments.quizes.index')))->withInput();
        }
    }

    public function newQuiz()
    {

        $this->data['site_title'] = "New Quiz";
        //$this->data['quiz'] = $quiz;

        return $this->_renderPage('Academic/Assessments/Quizes/create', $this->data);
    }

    public function createNewQuiz($id)
    {
        $quiz = (new \App\Models\Quizes())->find($id);
        if(!$quiz) {
            return $this->response->setStatusCode(404)->setBody("Quiz not found");
        }
        $subject = $this->request->getPost('subject');
        $section = $this->request->getPost('section');
        $subject = (new ClassSubjects())->find($subject);
        if(!$subject) {
            return $this->response->setStatusCode(404)->setBody("Subject not found");
        }
        $section = (new Sections())->find($section);
        if(!$section) {
            return $this->response->setStatusCode(404)->setBody("Section not found");
        }
        $data = [
            'quiz'      => $quiz,
            'subject'   => $subject,
            'section'   => $section
        ];
        return view('Academic/Assessments/Quizes/new', $data);
    }

    public function saveNewQuiz()
    {
        $requests = $this->request->getPost();
        $questions_data = array();
        $mega = [];
        $msg = "Quiz saved successfully";
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
            $subject = $requests['subject'];

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
                    'quiz' => $cName,
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
                $model = new QuizItems();
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
                    $msg = "Quiz saved successfully";
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
            return $this->response->redirect(site_url(route_to('admin.academic.assessments.quizes.new_quiz')));
        }else {
            return $this->response->redirect(site_url(route_to('admin.academic.assessments.quizes.new_quiz')));
        }
    }

    public function markPublished($id)
    {
        $model = new QuizItems();
        if($model->set('published', '1')->where('id', $id)->update()) {
            return redirect()->back()->with('success', 'Marked as published');
        }

        return redirect()->back()->with('error', 'Failed to publish');
    }

    public function markDraft($id)
    {
        $model = new QuizItems();
        if($model->set('published', '0')->where('id', $id)->update()) {
            return redirect()->back()->with('success', 'Marked as draft');
        }

        return redirect()->back()->with('error', 'Failed to mark as draft');
    }

    public function saveEditQuiz($id)
    {
        $requests = $this->request->getPost();
        $questions_data = array();
        $mega = [];
        $msg = "Quiz saved successfully";
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
            $subject = $requests['subject'];

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
                    'quiz' => $cName,
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
                $model = new QuizItems();
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
                    $msg = "Quiz saved successfully";
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
            return $this->response->redirect(site_url(route_to('admin.academic.assessments.quizes.view',$id)));
        }else {
            return $this->response->redirect(site_url(route_to('admin.academic.assessments.quizes.view',$id)));
        }
//        exit();
//        $data = $this->request->getJSON();
//        $mega = [];
//        $msg = "Some questions may not have answers or the entire set is empty";
//        $return = FALSE;
//       // $keys = ['A', 'B', 'C', 'D'];
//        $keys = [];
//        $options = (new AnswerOption())->findAll();
//        foreach ($options as $option){
//            array_push($keys,$option['name']);
//        }
//        //print_r($data->quiz[0]);
//        if($data && is_object($data)) {
//            $out_of = $data->out_of;
//            $duration = $data->duration;
//            $cName = $data->name;
//            $cClass = $data->class;
//            $cSemester = $data->semester;
//            $cSubject = $data->subject;
//            $cGiven = $data->given;
//            $cDeadline = $data->deadline;
//            //$class = $data->classid;
//            $subject = $data->subject;
//            $questions = $data->quiz;
//            foreach ($questions as $question) {
//                $correct_exists = FALSE;
//                if($question->question && $question->question != '') {
//                    //$corrects = $question->correct;
//                    //$answers = $question->answers;
//                    foreach ($keys as $key) {
//                        $cstr = "correct[".$key."]";
//                        $astr = "answers[".$key."]";
//                        if((isset($question->$cstr) && $question->$cstr == true && isset($question->$astr) && $question->$astr != '')) {
//                            $correct_exists = TRUE;
//                        }
//                        $corrects[$key] = isset($question->$cstr) && $question->$cstr == true;
//                        $answers[$key] = $question->$astr;
//                    }
//                    if(isset($answers) && is_array($answers) && isset($corrects) && is_array($corrects)) {
//                        $data = [
//                            'question' => $question->question,
//                            'answers'  => $answers,
//                            'corrects' => $corrects,
//                            'explanation'   => $question->explanation,
//                        ];
//                        if(isset($question->image) && $question->image != '' && !empty($question->image)) {
//                            //TODO: Upload this image
//                            $data['image'] = $question->image;
//                        }
//                        if($correct_exists) {
//                            array_push($mega, $data);
//                            $return = TRUE;
//                            $msg = "quiz saved successfully";
//                        } else {
//                            $return = FALSE;
//                            $msg = "Some questions have no correct answer selected";
//                        }
//                    } else {
//                        $return = FALSE;
//                        $msg = "Answers not set";
//                    }
//                }
//            }
//        } else {
//            $return = FALSE;
//            $msg = "Empty or invalid data";
//        }
//
//        if($return) {
//            //Save $mega to database
//            if(count($mega) > 0) {
//                $to_db = [
//                    'id'        => $id,
//                    'out_of'    => $out_of,
//                    'duration'  => $duration,
//                    'class'     => $cClass,
//                    'name'      => $cName,
//                    'semester'  => $cSemester,
//                    'session'   => active_session(),
//                    //'section'   => $section,
//                    'given'     => $cGiven,
//                    'deadline'  => $cDeadline,
//                    'items'     => json_encode($mega),
//                    'subject'   => $subject,
//                    'quiz'      => $cName
//                ];
//                $model = new QuizItems();
//
//                try {
//                    if ($model->save($to_db)) {
//                        $return = TRUE;
//                        $msg = "quiz saved successfully";
//                        $primaryID = $model->getInsertID();
//                    } else {
//                        $return = FALSE;
//                        $msg = "A database error occurred";
//                    }
//                } catch (\ReflectionException $e) {
//                    $return = FALSE;
//                    $msg = $e->getMessage();
//                }
//            } else {
//                $return = FALSE;
//                $msg = "Cannot save empty data";
//            }
//        }
//
//
//        //print_r($data);
//        if ($this->request->isAJAX()) {
//            $resp = [
//                'status' => $return ? 'success' : 'error',
//                'title' => 'Status',
//                'message' => $msg,
//                'notifyType' => 'toastr',
//                'callback' => $return ? 'window.location = "'.site_url(route_to('admin.academic.assessments.quizes.view', $id)).'"' : ''
//            ];
//            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
//        } else {
//            $t = $return ? 'success' : 'error';
//            $this->session->setFlashData($t, $msg);
//            return $this->response->redirect(site_url(route_to('admin.academic.assessments.class_work.new_quiz_create')))->withInput();
//        }
    }
}