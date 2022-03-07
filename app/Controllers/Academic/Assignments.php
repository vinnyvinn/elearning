<?php


namespace App\Controllers\Academic;


use App\Controllers\AdminController;
use App\Entities\Assignment;
use App\Models\AnswerOption;
use App\Models\AssignmentItems;
use App\Models\AssignmentSubmissions;
use App\Models\AssignmentSubmissionsMarked;
use App\Models\Classes;
use App\Models\ClassSubjects;
use App\Models\QuizItems;
use App\Models\Students;

class Assignments extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        return $this->_renderPage('Academic/Assignment/index', $this->data);
    }
    public function writing()
    {

        return $this->_renderPage('Academic/Assignment/wr_index', $this->data);
    }
    public function writingSubmissions($assignment,$class)
    {
       $this->data['assignment'] = $assignment;
       $this->data['class'] = (new Classes())->find($class);
       return $this->_renderPage('Academic/Assignment/wr_submission_index', $this->data);
    }
    public function createAssignment()
    {

        return $this->_renderPage('Academic/Assignment/create', $this->data);
    }

    public function getAssignments()
    {
        $class = $this->request->getPost('class');
        $semester = $this->request->getPost('semester');
        $data = [
            'class' => $class,
            'semester' => $semester
        ];

        return view('Academic/Assignment/assignments', $data);
    }
    public function getWrAssignments()
    {
        $class = $this->request->getPost('class');
        $semester = $this->request->getPost('semester');
        $data = [
            'class' => $class,
            'semester' => $semester
        ];

        return view('Academic/Assignment/wr_assignments', $data);
    }
    public function delete($id)
    {
        if((new \App\Models\AssignmentItems())->delete($id)) {
            $return = TRUE;
            $msg = "Deleted successfully";
        } else{
            $return = FALSE;
            $msg = "Failed to delete the assignment";
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Status',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'getAssignments()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);

            return $this->response->redirect(site_url(route_to('admin.academic.assignments.writing')))->withInput();
        }
    }

    public function edit($id)
    {
        $quiz = (new AssignmentItems())->find($id);
        if(!$quiz) {
            return redirect()->back()->with('error', "Assignment not found");
        }

        $this->data['site_title'] = "New Assignment";
        $this->data['quiz'] = $quiz;

        return $this->_renderPage('Academic/Assignment/edit', $this->data);
    }

    public function save()
    {
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $class = (new \App\Models\Classes())->find($class);
        //$subject = (new \App\Models\ClassSubjects())->find($subject);
        $subject = (new ClassSubjects())->where('class', $class->id)->find($subject);
        //dd($subject);
        //Upload assignment
        $data = $this->request->getPost();
            $model = new \App\Models\Assignments();
            $entity = new Assignment();
            $file = $this->request->getFile('file');

            if ($file && $file->isValid() && !$this->request->getPost('id')) {
                $entity->fill($data);
                $newName = $file->getRandomName();
                if($file->move(FCPATH . 'uploads/assignments/', $newName)) {
                    $entity->file = $newName;
                    if ($model->save($entity)) {
                        $success = TRUE;
                        $msg = "Notes uploaded successfully";
                    } else {
                        $success = FALSE;
                        $msg = implode('<br/>', $model->errors());
                    }
                }
            } elseif ($the_id = $this->request->getPost('id')) {
                if ($file && $file->isValid()) {
                    $entity->fill($data);
                    @unlink($model->find($the_id)->path);
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'uploads/assignments/', $newName);
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
                    'title' => 'Assignment Upload',
                    'message' => $msg,
                    'notifyType' => 'toastr',
                    'callback' => $success ? 'window.location.reload()' : ''
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $t = $success ? 'success' : 'error';
                $this->session->setFlashData($t, $msg);
                return $this->response->redirect(current_url())->withInput();
            }
    }
    public function saveEdit($id)
    {
        $requests = $this->request->getPost();
        $questions_data = array();
        $mega = [];
        $msg = "Assignment updated successfully";
        $return = true;

        if($requests) {
            $out_of = $requests['out_of'];
            $duration = $requests['duration'];
            $cName = $requests['name'];
            $cClass = $requests['class'];
            $cSemester = $requests['semester'];
            $cSubject = $requests['subject'];
            $cGiven = $requests['given'];
            $cDeadline = $requests['deadline'];
            //$class = $data->classid;
            $subject = $requests['subject'];
            // $questions = $requests['quiz'];
            $question_numbers = array();

            foreach ($requests['question'] as $k1 => $quiz){
                if (!isset($questions_data[$k1])){
                    $arr = array('question' => $quiz);
                    $questions_data[$k1] = array();
                    array_push( $questions_data[$k1],$arr);
                    if ($quiz ==''){
                        $return = false;
                        $msg = "Question cannot be empty.";
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
                    $msg = "Answer cannot be empty.";
                }
            }


            foreach ($questions_data as $dt){
                array_push($mega,array_flatten($dt));
            }
        } else {
            $return = FALSE;
            $msg = "Empty or invalid data";
        }

        if($return) {
            //Save $mega to database
            if(count($mega) > 0) {
                $to_db = [
                    'id' => $id,
                    'out_of'    => $out_of,
                    'duration'  => $duration,
                    'class'     => $cClass,
                    'name'      => $cName,
                    'semester'  => $cSemester,
                    'session'   => active_session(),
                    //'section'   => $section,
                    'given'     => $cGiven,
                    'deadline'  => $cDeadline,
                    'items'     => json_encode($mega),
                    'subject'   => $subject,
                    'question'      => $cName,
                    'published' => (bool) $this->request->getPost('published') == 1 ? '1' : '0'
                ];
                $model = new AssignmentItems();

                try {
                    if ($model->save($to_db)) {
                        $return = TRUE;
                        $msg = "Assignment updated successfully";
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
                $msg = "Cannot update empty data";
            }
        }


        //print_r($data);
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Status',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location = "'.site_url(route_to('admin.academic.assignments.view', $id)).'"' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(site_url(route_to('admin.academic.assignments.view',$id)));
        }

//        $data = $this->request->getJSON();
//        $mega = [];
//        $msg = "Some questions may not have answers or the entire set is empty";
//        $return = FALSE;
////        $options = (new AnswerOption())->findAll();
////        $keys = array();
////        foreach ($options as $option){
////            array_push($keys,$option['name']);
////        }
//        //$keys = ['A', 'B', 'C', 'D'];
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
//                    $correct_exists = TRUE;
//                    //$corrects = $question->correct;
//                    //$answers = $question->answers;
////                    foreach ($keys as $key) {
////                        $cstr = "correct[".$key."]";
////                        $astr = "answers[".$key."]";
////                        if((isset($question->$cstr) && $question->$cstr == true && isset($question->$astr) && $question->$astr != '')) {
////                            $correct_exists = TRUE;
////                        }
////                        $corrects[$key] = isset($question->$cstr) && $question->$cstr == true;
////                        $answers[$key] = $question->$astr;
////                    }
//                    if($question->explanation) {
//                        $data = [
//                            'question_number' => $question->question_number,
//                            'instructions' => $question->instructions,
//                            'precautions' => $question->precautions,
//                            'question' => $question->question,
//                            'explanation'   => $question->explanation,
//                        ];
//                        if(isset($question->image) && $question->image != '' && !empty($question->image)) {
//                            $file_data = $question->image;
//
//
//                            list($type, $file_data) = explode(';', $file_data);
//                            list(, $file_data)      = explode(',', $file_data);
//                            $file_data = base64_decode($file_data);
//                            $name = time()+rand(10,1000).'.png';
//
//                            file_put_contents(FCPATH.'uploads/assignments/'.$name, $file_data);
//                            $data['image'] = $name;
//                        }
//                        if($correct_exists) {
//                            array_push($mega, $data);
//                            $return = TRUE;
//                            $msg = "Assignment saved successfully";
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
//                $model = new AssignmentItems();
//
//                try {
//                    if ($model->save($to_db)) {
//                        $return = TRUE;
//                        $msg = "Assignment saved successfully";
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
//        //print_r($data);
//        if ($this->request->isAJAX()) {
//            $resp = [
//                'status' => $return ? 'success' : 'error',
//                'title' => 'Status',
//                'message' => $msg,
//                'notifyType' => 'toastr',
//                'callback' => $return ? 'window.location = "'.site_url(route_to('admin.academic.assignments.view', $id)).'"' : ''
//            ];
//            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
//        } else {
//            $t = $return ? 'success' : 'error';
//            $this->session->setFlashData($t, $msg);
//            return $this->response->redirect(site_url(route_to('admin.academic.assignments.create')))->withInput();
//        }
    }
    public function view($id)
    {
        $quiz = (new \App\Models\AssignmentItems())->find($id);
        if(!$quiz) {
            return redirect()->back()->with('error', "Assignment does not exist");
        }

        $this->data['site_title'] = $quiz->name;
        $this->data['site_description'] = "View Assignment";
        $this->data['quiz'] = $quiz;

        return $this->_renderPage('Academic/Assignment/view', $this->data);
    }
    public function viewSubmitted($student,$assignment)
    {
        $quiz = (new \App\Models\AssignmentItems())->find($assignment);
        if(!$quiz) {
            return redirect()->back()->with('error', "Assignment does not exist");
        }

        $this->data['site_title'] = $quiz->name;
        $this->data['site_description'] = "View Assignment";
        $this->data['quiz'] = $quiz;
        $this->data['student'] = (new Students())->find($student);

        return $this->_renderPage('Academic/Assignment/view_submitted', $this->data);
    }

    public function markWr()
    {
        $requests = $this->request->getPost();
        $data = array();
        $total = 0;
        foreach ($requests['answer'] as $key => $value){
         foreach ($requests['correct'] as $key2 => $value2){
             if($key == $key2){
                 array_push($data,array('question_number'=>$key,'correct'=>$value2,'question'=>$value));
                 if ($value2 ==1)
                     $total += $value2*$requests['mark_per_question'];
             }

         }
        }

        $record = (new AssignmentSubmissionsMarked())->where('submission_id',$requests['submission_id'])->where('student_id',$requests['student_id'])->where('assignment_id',$requests['assignment_id'])->first();
        if ($record)
            $requests['id'] = $record->id;
        unset($requests['correct']);
        $requests['scored'] = $total;
        $requests['answer'] = json_encode($data);
        $return = false;
        $msg = "An error occurred";
        $model = new AssignmentSubmissionsMarked();

         if ($model->save($requests)){
             $primaryID = $model->getInsertID();
             $return = true;
             $msg = "Assignment successfully saved.";
         }else {
             $msg = "Assignment could not be saved.";
         }
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Status',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location = "'.site_url(route_to('admin.academic.assignments.writing.submissions',$requests['student_id'],$requests['assignment_id'])).'"' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(site_url(route_to('admin.academic.assignments.writing.submissions',$requests['assignment_id'],$requests['class_id'])));
        }
    }
    public function saveWr()
    {
        $requests = $this->request->getPost();
        $questions_data = array();
        $mega = [];
        $msg = "Assignment saved successfully";
        $return = true;

        if($requests) {
            $out_of = $requests['out_of'];
            $duration = $requests['duration'];
            $cName = $requests['name'];
            $cClass = $requests['class'];
            $cSemester = $requests['semester'];
            $cSubject = $requests['subject'];
            $cGiven = $requests['given'];
            $cDeadline = $requests['deadline'];
            //$class = $data->classid;
            $subject = $requests['subject'];
           // $questions = $requests['quiz'];
            $question_numbers = array();

            foreach ($requests['question'] as $k1 => $quiz){
                if (!isset($questions_data[$k1])){
                    $arr = array('question' => $quiz);
                    $questions_data[$k1] = array();
                    array_push( $questions_data[$k1],$arr);
                    if ($quiz ==''){
                        $return = false;
                        $msg = "Question cannot be empty.";
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
                    $msg = "Answer cannot be empty.";
                }
            }


            foreach ($questions_data as $dt){
                array_push($mega,array_flatten($dt));
            }
        } else {
            $return = FALSE;
            $msg = "Empty or invalid data";
        }

        if($return) {
            //Save $mega to database
            if(count($mega) > 0) {
                $to_db = [
                    'out_of'    => $out_of,
                    'duration'  => $duration,
                    'class'     => $cClass,
                    'name'      => $cName,
                    'semester'  => $cSemester,
                    'session'   => active_session(),
                    //'section'   => $section,
                    'given'     => $cGiven,
                    'deadline'  => $cDeadline,
                    'items'     => json_encode($mega),
                    'subject'   => $subject,
                    'question'      => $cName,
                    'published' => (bool) $this->request->getPost('published') == 1 ? '1' : '0'
                ];
                $model = new AssignmentItems();

                try {
                    if ($model->save($to_db)) {
                        $return = TRUE;
                        $msg = "Assignment saved successfully";
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
                'callback' => $return ? 'window.location = "'.site_url(route_to('admin.academic.assignments.view', $primaryID)).'"' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(site_url(route_to('admin.academic.assignments.create')));
        }
    }


    public function viewAssignment($id)
    {
        if($post = $this->request->getPost()) {
            $class = $this->request->getPost('class');
            $subject = $this->request->getPost('subject');
            $class = (new Classes())->find($class);
            $existing = (new \App\Models\AssignmentItems())
                ->where('quiz', $id)
                ->where('class', $class->id)->where('subject', $subject)->get()->getFirstRow('\App\Entities\AssignmentItem');

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

            return view('Academic/Assignment/view_assignment', $this->data);
        } else {
            return $this->response->setStatusCode(404)->setBody("Bad request");
        }
    }
    public function printAssignment($quiz)
    {

        //if($post = $this->request->getPost()) {
        $existing = (new \App\Models\AssignmentItems())->where('id', $quiz)->get()->getFirstRow('\App\Entities\AssignmentItem');

        if(!$existing) {
            $resp = [
                'status' => 'error',
                'title' => 'Status',
                'message' => "No assignment found",
                'notifyType' => 'toastr',
            ];
            return $this->response->setStatusCode(404)->setBody("No assignment found");
        }
        $this->data['quiz'] = $existing;

        return view('Academic/Assignment/print_assignment', $this->data);
    }
    public function markPublished($id)
    {
        $model = new AssignmentItems();
        if($model->set('published', '1')->where('id', $id)->update()) {
            return redirect()->back()->with('success', 'Marked as published');
        }

        return redirect()->back()->with('error', 'Failed to publish');
    }

    public function markDraft($id)
    {
        $model = new AssignmentItems();
        if($model->set('published', '0')->where('id', $id)->update()) {
            return redirect()->back()->with('success', 'Marked as draft');
        }

        return redirect()->back()->with('error', 'Failed to mark as draft');
    }
    public function download($id)
    {
        $model = new \App\Models\Assignments();
        $ass = $model->find($id);

        if($ass && file_exists($ass->path)) {
            return $this->response->download($ass->path, null, true);
        }

        return redirect()->back()->with('error', "File no longer exists");
    }
}