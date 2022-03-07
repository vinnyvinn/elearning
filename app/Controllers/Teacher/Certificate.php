<?php


namespace App\Controllers\Teacher;


use App\Controllers\Home;
use App\Models\Classes;
use App\Models\DirectorSign;
use App\Models\Homeroom;
use App\Models\Students;
use App\Models\YearlyStudentEvaluation;

class Certificate extends \App\Controllers\TeacherController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Yearly Certificate";
    }

    public function index()
    {
      return $this->_renderPage('Academic/Certificate/index', $this->data);
    }

    public function getStudents()
    {
        $section = $this->request->getPost('section');

        $this->data['class'] = (new \App\Models\Sections())->find($section)->class->id;
        $this->data['section'] = $section;

        return view('Teacher/Academic/Certificate/student', $this->data);
    }

//    public function certificate($student)
//    {
//        $student = (new Students())->find($student);
//
//        $this->data['student'] = $student;
//
//        return $this->_renderPage('Academic/Certificate/certificate', $this->data);
//    }
//
//    public function print($student)
//    {
//        $student = (new Students())->find($student);
//
//        $this->data['student'] = $student;
//
//        return view('Teacher/Academic/Certificate/print', $this->data);
//    }


    public function downloadCert($student)
    {
        $student = (new Students())->find($student);

        $this->data['student'] = $student;

        $class = (new Classes())->find($student->class->id);
        if ($class->type == 'kg')
            return view('Teacher/Academic/Certificate/print-pdf-kg', $this->data);
        if (is_quarter_plus_session())
            return view('Teacher/Academic/Certificate/quarters/print-pdf-plus', $this->data);
        if (is_quarter_session())
            return view('Teacher/Academic/Certificate/quarters/print-pdf', $this->data);
        return view('Teacher/Academic/Certificate/print-pdf', $this->data);

    }


    public function certificate($student)
    {

        $student = (new Students())->find($student);

        $this->data['student'] = $student;
        $class = (new Classes())->find($student->class->id);

        if ($class->type =='kg')
            return $this->_renderPage('Academic/Certificate/certificate_kg', $this->data);
        if (is_quarter_session())
            return $this->_renderPage('Academic/Certificate/certificate_quarter', $this->data);
        return $this->_renderPage('Academic/Certificate/certificate', $this->data);
    }
    public function certificate_kg($student)
    {

        $student = (new Students())->find($student);

        $this->data['student'] = $student;

        return $this->_renderPage('Academic/Certificate/certificate', $this->data);
    }
    public function report($student)
    {

        $student = (new Students())->find($student);

        $this->data['student'] = $student;

        return $this->_renderPage('Academic/Certificate/reportform', $this->data);
    }
    public function evaluation($student)
    {

        $student = (new Students())->find($student);
        $class = (new Classes())->find($student->class->id);
        $this->data['student'] = $student;
        if ($class->type=='kg'){
            return $this->_renderPage('Academic/Certificate/evaluation_kg', $this->data);
        }
        return $this->_renderPage('Academic/Certificate/evaluation', $this->data);
    }
    public function evaluation_summary($student)
    {

        $student = (new Students())->find($student);

        $this->data['student'] = $student;

        return $this->_renderPage('Academic/Certificate/evaluation_summary', $this->data);
    }

    public function save_yearly_evaluation()
    {
        if($this->request->getPost()) {
            $to_db = [
                'student' => $this->request->getPost('id'),
                'first_sem_evaluation' => $this->request->getPost('first_sem_evaluation'),
                'second_sem_evaluation' => $this->request->getPost('second_sem_evaluation'),
                'class' => $this->request->getPost('class'),
                'section' => $this->request->getPost('section'),
                'session' => active_session(),
            ];

            $record = (new YearlyStudentEvaluation())->where('student',$this->request->getPost('id'))->where('session',active_session())->where('class',$this->request->getPost('class'))->where('section',$this->request->getPost('section'));
            if (!empty($record->get()->getLastRow())){
                try {
                    $db = \Config\Database::connect();
                    $builder = $db->table('yearly_student_evaluations');
                    $builder->where('id', $record->get()->getLastRow()->id);
                    if ($builder->update($to_db)) {
                        $return = TRUE;
                        $msg = "Conduct updated successfully";
                    } else {
                        $return = FALSE;
                        $msg = "Failed to update Conduct";
                    }
                } catch (\ReflectionException $e) {
                    $return = FALSE;
                    $msg = $e->getMessage();
                }
            }else{
                $model = new YearlyStudentEvaluation();
                try {
                    if ($model->save($to_db)) {
                        $return = TRUE;
                        $msg = "Conduct saved successfully";
                    } else {
                        $return = FALSE;
                        $msg = "Failed to save Conduct";
                    }
                } catch (\ReflectionException $e) {
                    $return = FALSE;
                    $msg = $e->getMessage();
                }
            }

            $status = $return ? 'success' : 'error';
            if($this->request->isAJAX()) {
                $resp = [
                    'status'    => $status,
                    'message'   => $msg,
                    'title'     => $return ? 'Success' : 'Error',
                    'callback'  => $return  ? 'window.location.reload()' : '',
                ];

                return $this->response->setBody(json_encode($resp))->setContentType('application/json')->setStatusCode($return ? 200 : 401);
            }

            return redirect()->to(previous_url())->with($status, $msg);
        }

        if($this->request->isAJAX()) {
            return $this->response->setBody("Invalid request")->setStatusCode(404);
        }

        return redirect()->to(previous_url())->with('error', "Invalid request");
    }
    public function save_homeroom_teacher($student)
    {
        if($this->request->getPost()) {
            $to_db = [
                'session' => active_session(),
                'class' => $this->request->getPost('class'),
                'section' => $this->request->getPost('section'),
                'first_sem_comment' => $this->request->getPost('first_sem_comment'),
                'second_sem_comment' => $this->request->getPost('second_sem_comment'),
                'student' => $this->request->getPost('id')
            ];

            $record = (new Homeroom())->find($this->request->getPost('homeroom_id'));
            if ($record)
                $to_db['id'] = $record->id;
                $model = new Homeroom();
                try {
                    if ($model->save($to_db)) {
                        $return = TRUE;
                        $msg = "Homeroom Comment & Sign  saved successfully";
                    } else {
                        $return = FALSE;
                        $msg = "Failed to save evaluation";
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
                    'title'     => $return ? 'Success' : 'Error',
                    'callback'  => $return  ? 'window.location.reload()' : '',
                ];

                return $this->response->setBody(json_encode($resp))->setContentType('application/json')->setStatusCode($return ? 200 : 401);
            }

            return redirect()->to(previous_url())->with($status, $msg);
        }

        if($this->request->isAJAX()) {
            return $this->response->setBody("Invalid request")->setStatusCode(404);
        }

        return redirect()->to(previous_url())->with('error', "Invalid request");
    }
    public function homeroom_sign()
    {
        if($this->request->getPost()) {
            if (!isset($_POST['data']['single_sign'])){
                $students = (new Students())->where('session',active_session())->where('class',$_POST['data']['class'])->where('section',$_POST['data']['section'])->where('session',active_session())->findAll();
                foreach ($students as $student) {
                    $to_db = [
                        'student' => $student->id,
                        'session' => active_session(),
                        'class' => $_POST['data']['class'],
                        'section' => $_POST['data']['section'],
                        'is_signed' => $_POST['data']['is_signed']
                    ];

                    $record = (new Homeroom())->where('session', active_session())->where('class',$_POST['data']['class'])->where('section',$_POST['data']['section'])->where('student', $student->id)->get()->getLastRow();
                    if ($record)
                        $to_db['id'] = $record->id;
                        $model = new Homeroom();
                        try {
                            if ($model->save($to_db)) {
                                $return = TRUE;
                                $msg = "Homeroom Sign  saved successfully";
                                var_dump($to_db);
                            } else {
                                $return = FALSE;
                                $msg = "Failed to save evaluation";
                            }
                        } catch (\ReflectionException $e) {
                            $return = FALSE;
                            $msg = $e->getMessage();
                        }
                      }
            }else {
                $to_db = [
                    'student' => $_POST['data']['student'],
                    'session' => active_session(),
                    'class' => $_POST['data']['class'],
                    'section' => $_POST['data']['section'],
                    'is_signed' => $_POST['data']['is_signed']
                ];

                $record2 = (new Homeroom())->where('session', active_session())->where('student',$_POST['data']['student'])->get()->getLastRow();
                if (isset($record2->id))
                    $to_db['id'] = $record2->id;
                $model2 = new Homeroom();
                try {
                    if ($model2->save($to_db)) {
                        $return = TRUE;
                        $msg = "Homeroom Sign saved successfully";
                    } else {
                        $return = FALSE;
                        $msg = "Failed to save evaluation";
                    }
                } catch (\ReflectionException $e) {
                    $return = FALSE;
                    $msg = $e->getMessage();
                }
            }

            $status = $return ? 'success' : 'error';
            if($this->request->isAJAX()) {
                $resp = [
                    'status'    => $status,
                    'message'   => $msg,
                    'title'     => $return ? 'Success' : 'Error',
                    'callback'  => $return  ? 'window.location.reload()' : '',
                ];

                return $this->response->setBody(json_encode($resp))->setContentType('application/json')->setStatusCode($return ? 200 : 401);
            }

            return redirect()->to(previous_url())->with($status, $msg);
        }

        if($this->request->isAJAX()) {
            return $this->response->setBody("Invalid request")->setStatusCode(404);
        }

        return redirect()->to(previous_url())->with('error', "Invalid request");
    }
    public function director_sign()
    {

        if($this->request->getPost()) {
            if (!isset($_POST['data']['single_sign'])){
                $students = (new Students())->where('session',active_session())->where('class',$_POST['data']['class'])->where('section',$_POST['data']['section'])->where('session',active_session())->findAll();
                foreach ($students as $student) {
                    $to_db = [
                        'student' => $student->id,
                        'session' => active_session(),
                        'class' => $_POST['data']['class'],
                        'section' => $_POST['data']['section'],
                        'is_signed' => $_POST['data']['is_signed']
                    ];

                    $record = (new DirectorSign())->where('session', active_session())->where('class',$_POST['data']['class'])->where('section',$_POST['data']['section'])->where('student', $student->id)->get()->getLastRow();
                    if (empty($record)) {
                        $model = new DirectorSign();
                        try {
                            if ($model->save($to_db)) {
                                $return = TRUE;
                                $msg = "Director Sign  saved successfully";
                            } else {
                                $return = FALSE;
                                $msg = "Failed to save evaluation";
                            }
                        } catch (\ReflectionException $e) {
                            $return = FALSE;
                            $msg = $e->getMessage();
                        }
                    }else {
                        $model = (new DirectorSign())->where('session', active_session())->where('class',$_POST['data']['class'])->where('section',$_POST['data']['section'])->where('student', $student->id);
                        try {
                            $db = \Config\Database::connect();
                            $builder = $db->table('director_sign');
                            $builder->where('id', $model->get()->getLastRow()->id);
                            if ($builder->update($to_db)) {
                                $return = TRUE;
                                $msg = "Director Sign  updated successfully";
                            } else {
                                $return = FALSE;
                                $msg = "Failed to save evaluation";
                            }
                        } catch (\ReflectionException $e) {
                            $return = FALSE;
                            $msg = $e->getMessage();
                        }

                    }


                }
            }else {
                $to_db = [
                    'student' => $_POST['data']['student'],
                    'session' => active_session(),
                    'class' => $_POST['data']['class'],
                    'section' => $_POST['data']['section'],
                    'is_signed' => $_POST['data']['is_signed']
                ];

                $record2 = (new DirectorSign())->where('session', active_session())->where('student',$_POST['data']['student'])->get()->getLastRow();
                if (isset($record2->id))
                    $to_db['id'] = $record2->id;
                    $model2 = new DirectorSign();
                    try {
                        if ($model2->save($to_db)) {
                            $return = TRUE;
                            $msg = "Director Sign  saved successfully";
                        } else {
                            $return = FALSE;
                            $msg = "Failed to save evaluation";
                        }
                    } catch (\ReflectionException $e) {
                        $return = FALSE;
                        $msg = $e->getMessage();
                    }
            }

            $status = $return ? 'success' : 'error';
            if($this->request->isAJAX()) {
                $resp = [
                    'status'    => $status,
                    'message'   => $msg,
                    'title'     => $return ? 'Success' : 'Error',
                    'callback'  => $return  ? 'window.location.reload()' : '',
                ];

                return $this->response->setBody(json_encode($resp))->setContentType('application/json')->setStatusCode($return ? 200 : 401);
            }

            return redirect()->to(previous_url())->with($status, $msg);
        }

        if($this->request->isAJAX()) {
            return $this->response->setBody("Invalid request")->setStatusCode(404);
        }

        return redirect()->to(previous_url())->with('error', "Invalid request");
    }

    public function print($student)
    {
        $student = (new Students())->find($student);

        $this->data['student'] = $student;
        $class = (new Classes())->find($student->class->id);
        if ($class->type =='kg')
            return view('Teacher/Academic/Certificate/print_kg', $this->data);
        if (is_quarter_plus_session())
            return view('Teacher/Academic/Certificate/quarters/print_plus', $this->data);

        if (is_quarter_session()){
           return view('Teacher/Academic/Certificate/quarters/print', $this->data);
        }


        return view('Teacher/Academic/Certificate/print', $this->data);
    }
}