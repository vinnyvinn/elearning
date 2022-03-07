<?php


namespace App\Controllers\Teacher;


use App\Controllers\AdminController;
use App\Controllers\TeacherController;
use App\Entities\ExamSchedule;
use App\Models\Classes;
use CodeIgniter\Exceptions\PageNotFoundException;

class View extends TeacherController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($id)
    {

        $this->data['exam'] = (new \App\Models\Exams())->find($id);
        $this->data['title'] = 'Time Tables';
        $this->data['page'] = 'overview';
        return $this->_renderSection('View/index', $this->data);
    }

    public function results($id)
    {

        $this->data['exam'] = (new \App\Models\Exams())->find($id);
        $this->data['title'] = 'Results';
        $this->data['page'] = 'results';
        return $this->_renderSection('View/results', $this->data);
    }

    public function classResults($examId, $classId)
    {
        $this->data['class'] = $classId;
        $this->data['exam'] = $examId;

        return view('Teacher/Exams/View/res', $this->data);
    }
    public function getResults()
    {
        $exam = $this->request->getPost('exam');
        $section = $this->request->getPost('section');
        $class = (new \App\Models\Sections())->find($this->request->getPost('section'))->class->id;
        $data = [
            'class' => $class,
            'exam' => $exam,
            'section' => $section
        ];

        return view('Teacher/Exams/View/results_index', $data);
    }

    public function timetable($examId, $classId)
    {
        //print_r($this->request->getPost());
        if($this->request->getPost('save') == 'save'){
            if(!current_user_can('create_exam_timetable')) {
                $resp = [
                    'status'    => 'error',
                    'message'   => "You are not allowed to perform this action",
                    'notifyType'    => 'toastr',
                    'title'     => 'Error',
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            }
            $class = $this->request->getPost('class');
            $exam = $this->request->getPost('exam');
            $timetable = $this->request->getPost('timetable');
            $entries = [];
            //$timetable = $timetable[];
            foreach($timetable as $day=>$tt) {
                //Check if exists, if exists,
                $time = '';
                $subject = '';
                $entity = new ExamSchedule();

                $model = new \App\Models\ExamSchedule();
                foreach ($tt['subject'] as $t=>$s) {
                    $time = $t;
                    $subject = $s;

                    $entry = [
                        'day'   => $tt['day'],
                        'time'  => $time,
                        'subject'   => $subject,
                        'class' => $class,
                        'exam'  => $exam
                    ];

                    //$entity->fill($entry);
                    if(isset($tt['id'][$time]) && is_numeric($tt['id'][$time])) {
                        $entry['id'] = $tt['id'][$time];
                    }
//                    print_r($entry);
//                    exit;

                    $model->save($entry);
                }

            }

            $return = TRUE;
            $msg = "Timetable saved successfully";

            $status = $return ? 'success' : 'error';
            if($this->request->isAJAX()) {
                $resp = [
                    'status'    => $status,
                    'message'   => $msg,
                    'notifyType'    => 'toastr',
                    'title'     => $return ? 'Success' : 'Error',
                    'callback'  => 'getTimetable()',
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            }

            $this->session->setFlashData($status, $msg);
            return $this->response->redirect(current_url());
        }
        $exam = (new \App\Models\Exams())->find($examId);
        $class = (new Classes())->find($classId);
        if(!$exam || !$class) {
            throw new PageNotFoundException();
        }
        $this->data['exam'] = $exam;
        $this->data['class'] = $class;
        //echo "We may have won for exam {$examId} in class {$classId}";

        return view('Teacher/Exams/View/timetable', $this->data);
    }

    public function _renderSection($view, $data = [])
    {
        $html = view('Teacher/Exams/'.$view, $data);
        $data['html'] = $html;
        $data = array_merge($this->data, $data);
        return $this->_renderPage('Exams/View/layout', $data);
    }
}