<?php


namespace App\Controllers\Assessments;


use App\Models\Classes;
use App\Models\ClassSubjects;
use App\Models\Quarters;
use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Students;
use CodeIgniter\Model;

class ManualAssessments extends \App\Controllers\AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Assessments";
        $this->data['site_description'] = "Assessments";
    }

    public function index()
    {
       if (getSession(active_session())->session_type > 0)
        return $this->_renderPage('Assessments/Manual/index_quarters', $this->data);
        return $this->_renderPage('Assessments/Manual/index', $this->data);
    }

    public function get()
    {
        $class = $this->request->getPost('class');
        //$section = $this->request->getPost('section');
        $subject = $this->request->getPost('subject');
        $semester = $this->request->getPost('semester');

        $class = (new Classes())->find($class);
        if(!$class) {
            return $this->response->setBody("Class not found")->setStatusCode(404);
        }

//        $section = (new Sections())->find($section);
//        if(!$section) {
//            return $this->response->setBody("Section not found")->setStatusCode(404);
//        }
        $subject = (new ClassSubjects())->find($subject);
        if(!$subject) {
            return $this->response->setBody("Subject not found")->setStatusCode(404);
        }
        $semester = (new Semesters())->find($semester);
        if(!$semester) {
            return $this->response->setBody("Semester not found")->setStatusCode(404);
        }

        $this->data['class'] = $class;
        //$this->data['section'] = $section;
        $this->data['subject'] = $subject;
        $this->data['semester'] = $semester;

        return view('Assessments/Manual/get', $this->data);
    }

    public function getQ()
    {
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $quarter = $this->request->getPost('quarter');

        $class = (new Classes())->find($class);
        if(!$class) {
            return $this->response->setBody("Class not found")->setStatusCode(404);
        }

        $subject = (new ClassSubjects())->find($subject);
        if(!$subject) {
            return $this->response->setBody("Subject not found")->setStatusCode(404);
        }
        $quarter = (new Quarters())->find($quarter);
        if(!$quarter) {
            return $this->response->setBody("Quarter not found")->setStatusCode(404);
        }

        $this->data['class'] = $class;
        $this->data['subject'] = $subject;
        $this->data['quarter'] = $quarter;

        return view('Assessments/Manual/get_q', $this->data);
    }
    public function saveCA()
    {

        $return = FALSE;
        $msg = "Failed to save assessment fields";

        $session = $this->request->getPost('session');
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
      //  $section = $this->request->getPost('section');
        $items = $this->request->getPost('item');
        $semester = $this->request->getPost('semester');

        $class = (new Classes())->find($class);
        if(!$class) {
            return $this->response->setBody("Class not found")->setStatusCode(404);
        }
//        $section = (new Sections())->find($section);
//        if (!$section){
//         return $this->response->setBody("Section not found")->setStatusCode(404);
//        }
        $subject = (new ClassSubjects())->find($subject);
        if(!$subject) {
          return $this->response->setBody("Subject not found")->setStatusCode(404);
        }
        if(!is_array($items)) {
            return $this->response->setBody("Invalid request")->setStatusCode(501);
        }
        //Reorder items
//        $first = $items[0];
//        unset($items[0]);
//        $items[] = $first;
//
        $key = 'manual_cats_'.$session.'-'.$semester.'-'.$class->id.'-'.$subject->id;
        $keys = json_decode(get_option($key, json_encode([])), true);

        $items_to_db = array();
        foreach ($items as $item){
            array_push($items_to_db,$item);
        }

        //Update existing results.
        $sess = (new \App\Models\ManualAssessments())->where('session', $session)->where('semester', $semester)->where('class', $class->id)->where('subject', $subject->id)->first();
        if (isset($keys) && !empty($keys)) {
            $deleted = array();
            $new = array();

            foreach ($items as $k_ => $item) {
                if (!isset($new[$k_]))
                    $new[$k_] = true;
            }

            if (!empty($sess) && !empty($res1 = json_decode($sess->results))){
                foreach ($res1 as $k1 => $v1){
                 if (!isset($new[$k1])){
                     $deleted[$k1] = true;
                 }
                }
            }

            $manualModel = new \App\Models\ManualAssessments();
            $m_ass = (new \App\Models\ManualAssessments())->where('session', $session)->where('semester', $semester)->where('class', $class->id)->where('subject', $subject->id)->findAll();
            foreach ($m_ass as $res) {
                $marks = @json_decode($res->results, true);

                $new_marks = array();
                foreach ($marks as $k => $v) {
                    if (!isset($deleted[$k])) {
                        $new_marks[$k] = $v;
                    }
                }

                $to_db = array("results" => json_encode($new_marks), "id" => $res->id);
                $manualModel->save($to_db);
            }
        }


        if(update_option($key, json_encode($items_to_db))) {
            $return = TRUE;
            $msg = "Assessment fields saved successfully";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'getTheAssessments()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }

    public function saveCAQ()
    {
        $return = FALSE;
        $msg = "Failed to save assessment fields";

        $session = $this->request->getPost('session');
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $items = $this->request->getPost('item');
        $quarter = $this->request->getPost('quarter');

        $class = (new Classes())->find($class);
        if(!$class) {
            return $this->response->setBody("Class not found")->setStatusCode(404);
        }
        $subject = (new ClassSubjects())->find($subject);
        if(!$subject) {
            return $this->response->setBody("Subject not found")->setStatusCode(404);
        }
        if(!is_array($items)) {
            return $this->response->setBody("Invalid request")->setStatusCode(501);
        }
        //Reorder items

        $key = 'manual_cats_'.$session.'-'.$quarter.'-'.$class->id.'-'.$subject->id;

        $items_to_db = array();
        foreach ($items as $item){
            array_push($items_to_db,$item);
        }

        //Update existing results.
        $sess = (new \App\Models\ManualAssessments())->where('session', $session)->where('quarter', $quarter)->where('class', $class->id)->where('subject', $subject->id)->first();
        if (isset($keys) && !empty($keys)) {
            $deleted = array();
            $new = array();

            foreach ($items as $k_ => $item) {
                if (!isset($new[$k_]))
                    $new[$k_] = true;
            }

            if (!empty($sess) && !empty($res1 = json_decode($sess->results))){
                foreach ($res1 as $k1 => $v1){
                    if (!isset($new[$k1])){
                        $deleted[$k1] = true;
                    }
                }
            }

            $manualModel = new \App\Models\ManualAssessments();
            $m_ass = (new \App\Models\ManualAssessments())->where('session', $session)->where('quarter', $quarter)->where('class', $class->id)->where('subject', $subject->id)->findAll();
            foreach ($m_ass as $res) {
                $marks = @json_decode($res->results, true);

                $new_marks = array();
                foreach ($marks as $k => $v) {
                    if (!isset($deleted[$k])) {
                        $new_marks[$k] = $v;
                    }
                }

                $to_db = array("results" => json_encode($new_marks), "id" => $res->id);
                $manualModel->save($to_db);
            }
        }

        if(update_option($key, json_encode($items_to_db))) {
            $return = TRUE;
            $msg = "Assessment fields saved successfully";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'getTheAssessments()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }


    public function saveCATotal()
    {
        $return = FALSE;
        $msg = "invalid data submitted";
        $manualModel = new \App\Models\ManualAssessments();
        $session = $this->request->getPost('session');
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $semester = $this->request->getPost('semester');
        $given_total = $this->request->getPost('given_total');
        $desired_total = $this->request->getPost('desired_total');



         $m_ass = (new \App\Models\ManualAssessments())->where('session',$session)->where('semester',$semester)->where('class',$class)->where('subject',$subject)->findAll();

               foreach ($m_ass as $ass) {
                $converted_total = $ass->total * $desired_total/$given_total;

                    $to_db = [
                        'given_total' => $given_total,
                        'desired_total' => $desired_total,
                        'converted_total' => (float)$converted_total,

                    ];
                    
                   $db = \Config\Database::connect();
                   $builder = $db->table('manual_assessments');
                   $builder->where('session', $session)->where('class', $class)
                       ->where('student', $ass->student)
                       ->where('semester', $semester)
                       ->where('subject', $subject);
                      $builder->update($to_db);
            }

            $return = TRUE;
            $msg = "Updated successfully";

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'getTheAssessments()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }

    public function saveCATotalQ()
    {
        $return = FALSE;
        $msg = "invalid data submitted";
        $manualModel = new \App\Models\ManualAssessments();
        $session = $this->request->getPost('session');
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $quarter = $this->request->getPost('quarter');
        $given_total = $this->request->getPost('given_total');
        $desired_total = $this->request->getPost('desired_total');



        $m_ass = (new \App\Models\ManualAssessments())->where('session',$session)->where('quarter',$quarter)->where('class',$class)->where('subject',$subject)->findAll();

        foreach ($m_ass as $ass) {
            $converted_total = number_format($ass->total * $desired_total/$given_total,2,'.','');
            $to_db = [
                'given_total' => $given_total,
                'desired_total' => $desired_total,
                'converted_total' => number_format($converted_total,2,'.',''),

            ];

            $db = \Config\Database::connect();
            $builder = $db->table('manual_assessments');
            $builder->where('session', $session)->where('class', $class)
                ->where('student', $ass->student)
                ->where('quarter', $quarter)
                ->where('subject', $subject);
            $builder->update($to_db);
        }

        $return = TRUE;
        $msg = "Updated successfully";

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'getTheAssessments()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }

    public function saveResults()
    {
        $return = FALSE;
        $msg = "invalid data submitted";
        //print_r($_POST);
        $manualModel = new \App\Models\ManualAssessments();
        $session = $this->request->getPost('session');
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $section = $this->request->getPost('section');
        $students = $this->request->getPost('student');
        $semester = $this->request->getPost('semester');

        if(is_array($students)) {
            foreach ($students as $student=>$result) {
                $total = 0;
                $k = 0;
                $res = array();
                foreach ($result as $item) {
                    $k++;
                    $total += $item;
                    $res[$k] = $item;
                }
                $to_db = [
                    'session'   => $session,
                    'class'     => $class,
                 //   'section'     => $section,
                    'section' => (new Students())->find($student)->section->id,
                    'subject'   => $subject,
                    'student'   => $student,
                    'semester'  => $semester,
                    'results'   => json_encode($res),
                    'total'     => $total
                ];

                $res = (new \App\Models\ManualAssessments())->where('session', $session)->where('class', $class)
                    ->where('student', $student)
                    ->where('semester', $semester)
                    ->where('subject', $subject)->get()->getLastRow();
                if($res)
                    $to_db['id'] = $res->id;
                    try {
                        if ($manualModel->save($to_db)) {
                            $return = TRUE;
                            $msg = "Assessment updated successfully";
                        } else {
                            $return = FALSE;
                            $msg = "Failed to update Assessment";
                        }
                    } catch (\ReflectionException $e) {
                        $return = FALSE;
                        $msg = $e->getMessage();
                    }

            }

            $return = TRUE;
            $msg = "Saved successfully";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'getTheAssessments()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }

    public function saveResultsQ()
    {
        $return = FALSE;
        $msg = "invalid data submitted";

        $manualModel = new \App\Models\ManualAssessments();
        $session = $this->request->getPost('session');
        $class = $this->request->getPost('class');
        $subject = $this->request->getPost('subject');
        $section = $this->request->getPost('section');
        $students = $this->request->getPost('student');
        $quarter = $this->request->getPost('quarter');
        $semester = (new Quarters())->find($quarter);
        $semester = $semester->semester->id;

        if(is_array($students)) {
            foreach ($students as $student=>$result) {
                $total = 0;
                $k = 0;
                $res = array();
                foreach ($result as $item) {
                    $k++;
                    $total += $item;
                    $res[$k] = $item;
                }
                
                $to_db = [
                    'session'   => $session,
                    'class'     => $class,
                    'section' => (new Students())->find($student)->section->id,
                    'subject'   => $subject,
                    'student'   => $student,
                    'semester'  => $semester,
                    'quarter'  => $quarter,
                    'results'   => json_encode($result),
                    'total'     => $total
                ];

                $res = (new \App\Models\ManualAssessments())->where('session', $session)->where('class', $class)
                    ->where('student', $student)
                    ->where('quarter', $quarter)
                    ->where('subject', $subject)->get()->getLastRow();
                if($res)
                  $to_db['id'] = $res->id;
                    try {
                        if ($manualModel->save($to_db)) {
                            $return = TRUE;
                            $msg = "Assessment updated successfully";
                        } else {
                            $return = FALSE;
                            $msg = "Failed to update Assessment";
                        }
                    } catch (\ReflectionException $e) {
                        $return = FALSE;
                        $msg = $e->getMessage();
                    }
            }

            $return = TRUE;
            $msg = "Saved successfully";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'getTheAssessments()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }
}