<?php


namespace App\Controllers\Teacher;


use App\Controllers\AdminController;
use App\Controllers\TeacherController;
use App\Entities\Exam;
use App\Entities\ExamResult;
use App\Models\Classes;
use App\Models\ClassSubjects;
use App\Models\ExamResults;
use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Students;
use App\Models\Subjects;
use CodeIgniter\Exceptions\PageNotFoundException;

class Exams extends TeacherController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['semesters'] = (new Semesters())->where('session', active_session())->orderBy('id', 'DESC')->findAll();
        return $this->_renderPage('Exams/index', $this->data);
    }
    public function index_schedule()
    {
        $this->data['semesters'] = (new Semesters())->where('session', active_session())->orderBy('id', 'DESC')->findAll();
        return $this->_renderPage('Exams/schedule_index', $this->data);
    }

    public function examResults()
    {
        $this->data['title'] = 'Results';
        $this->data['page'] = 'results';
        return $this->_renderPage('Exams/results', $this->data);
    }
    public function create()
    {
        if(!current_user_can('create_exam')) {
            $resp = [
                'status'    => 'error',
                'message'   => "You are not allowed to perform this action",
                'notifyType'    => 'toastr',
                'title'     => 'Error',
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        $entity = new Exam();
        $entity->fill($this->request->getPost());
        $model = new \App\Models\Exams();
        try {
            if ($model->save($entity)) {
                $return = TRUE;
                $msg = "Exam recorded successfully";
            } else {
                $return = FALSE;
                $msg = "Failed to record entry";
            }
        } catch (\ReflectionException $e) {
            $return = FALSE;
            $msg = $e->getMessage();
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'window.location.reload()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }

    public function delete($id)
    {
        if(!current_user_can('delete_exam')) {
            $resp = [
                'status'    => 'error',
                'message'   => "You are not allowed to perform this action",
                'notifyType'    => 'toastr',
                'title'     => 'Error',
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        $model = new \App\Models\Exams();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Exam deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete entry";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'window.location.reload()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(route_to('admin.school.semesters.index')));
    }

    public function recordResults($exam)
    {
        $model = new \App\Models\Exams();
        $exam = $model->find($exam);
        if(!$exam) throw new PageNotFoundException("Exam not found");

        $this->data['exam'] = $exam;
        
        $this->_renderPage('Exams/View/record_results', $this->data);
    }

    public function saveResults($exam2)
    {
        if(!current_user_can('create_exam_results')) {
            $resp = [
                'status'    => 'error',
                'message'   => "You are not allowed to perform this action",
                'notifyType'    => 'toastr',
                'title'     => 'Error',
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        // echo '<pre>';
        //print_r(json_encode($_POST));
        $exam = $this->request->getPost('exam');
        if($exam == $exam2) {
            $subject = $this->request->getPost('subject');
            $marks = $this->request->getPost('mark');
            $grades = $this->request->getPost('grade');
            $remarks = $this->request->getPost('remark');
            $not_seated_for_exam = $this->request->getPost('not_seated_for_exam');

            $classes = $this->request->getPost('class');
            $entity = new ExamResult();
            $model = new ExamResults();
            $examObj = (new \App\Models\Exams())->find($exam);
            $entity->exam = $exam;
            $entity->subject = $subject;
            $data = array();

            foreach ($marks as $student=>$mark) {
                // $resObj = $examObj->resultObject->where('subject', $subject)->where('student', $student)->get()->getRowObject();
                $resObj = $model->where('subject', $subject)->where('student', $student)->where('exam',$exam)->get()->getRowObject();
                $not_seated = isset($not_seated_for_exam[$student]) ? $not_seated_for_exam[$student] : 0;
                $entity->student = $student;
                $entity->mark = $mark;
                //$entity->grade = $grades[$student];
                $entity->grade = NULL;
                $entity->remark = $remarks[$student];
                //$entity->not_seated_for_exam = isset($not_seated_for_exam[$student]) ? $not_seated_for_exam[$student] : 0;
                // $not_seated_for_exam = isset($not_seated_for_exam) ? $not_seated_for_exam[$student] : '';
                $entity->class = $classes[$student];
                $db = \Config\Database::connect();
                $builder = $db->table('exam_results');
                $data = array('exam'=>$exam,'subject'=>$subject,'not_seated_for_exam'=>$not_seated,'student'=>$student,'mark'=>$mark,'grade'=>$entity->grade,'remark'=>$entity->remark,'class'=>$entity->class->id);

                if($resObj) {
                    $builder->where('id', $resObj->id);
                    $builder->update($data);
                    $entity->id = $resObj->id;
                }else {
                    $builder->insert($data);
                }
                //  if($model->save($entity)) {
                $return = TRUE;
                $msg = "Results updated successfully";
                //   }
//                else {
//                    $return = FALSE;
//                    $msg = "Failed to update results";
//                }
            }
//
        } else {
            $return = FALSE;
            $msg = "Invalid request. Are you trying to hack?";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'title'     => $return ? 'Success' : 'Error',
                'message'   => $msg,
                'status'    => $status,
                'notifyType'    => 'swal',
                'callbackTime' => 'onconfirm',
                'showCancelButton' => false,
                'callback'  => $return ? 'getStudents()' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    public function getStudents($exam)
    {
        //d($_POST);
        //Return a HTML table
        $section = (new Sections())->find($this->request->getPost('section'));
        $this->data['class'] = $section->class;
        $this->data['section'] = $section;
        $this->data['subject'] = (new ClassSubjects())->find($this->request->getPost('subject'));
        $this->data['exam'] = (new \App\Models\Exams())->find($exam);
        return view('Teacher/Exams/View/students_subject_table', $this->data);
    }


    public function ajaxSchedule()
    {
        $student = $this->request->getPost('student');
        $exam = $this->request->getPost('exam');
        $student = (new Students())->find($student);
        $exam = (new \App\Models\Exams())->find($exam);
        if(!$student || !$exam) {
            return $this->response->setContentType('application/json')->setStatusCode(404)->setBody('Student or Exam was not found');
        }

        $data = [
            'student'   => $student,
            'class'     => $student->class,
            'exam'      => $exam
        ];
        return view('Teacher/Exams/schedule', $data);
    }
}