<?php


namespace App\Controllers\Academic;


use App\Models\Classes;
use App\Models\DirectorSign;
use App\Models\Homeroom;
use App\Models\Sections;
use App\Models\StudentEvaluation;
use App\Models\Students;
use App\Models\YearlyStudentEvaluation;
use CodeIgniter\Model;
use App\Libraries\Newpdf;


class Certificate extends \App\Controllers\AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->data['site_title'] = "Yearly Certificate";
//        $pdf = new DOMPDF();
//        $CI =& get_instance();
//        $CI->dompdf = $pdf;

    }

    public function index()
    {
      //return redirect(base_url("admin"));
      //  return redirect()->to(base_url("admin"));

       return $this->_renderPage('Academic/Certificate/index', $this->data);
    }

    public function getStudents()
    {
        $class = $this->request->getPost('class');
        $section = $this->request->getPost('section');

        $this->data['class'] = $class;
        $this->data['section'] = $section;

        return view('Academic/Certificate/student', $this->data);
    }

    public function downloadCert($student)
    {
       $student = (new Students())->find($student);

        $this->data['student'] = $student;

        $class = (new Classes())->find($student->class->id);
        $remove_second_semester = get_option('turn_off_semester_2');
        if ($remove_second_semester){
            if ($class->type == 'kg')
                return view('Academic/Certificate/sem1_only/print-pdf-kg', $this->data);

            if (is_quarter_plus_session())
                return view('Academic/Certificate/quarters/print-pdf-plus', $this->data);
            if (is_quarter_session())
                return view('Academic/Certificate/quarters/print-pdf', $this->data);
            return view('Academic/Certificate/sem1_only/print-pdf', $this->data);
        }else {
            if ($class->type == 'kg')
                return view('Academic/Certificate/print-pdf-kg', $this->data);

            if (is_quarter_plus_session())
                return view('Academic/Certificate/quarters/print-pdf-plus', $this->data);
            if (is_quarter_session())
                return view('Academic/Certificate/quarters/print-pdf', $this->data);
        }

         return view('Academic/Certificate/print-pdf', $this->data);

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

    public function save_evaluation()
    {
        if($this->request->getPost()) {
            $to_db = [
                'student' => $this->request->getPost('student'),
                'remark' => json_encode($this->request->getPost('remark')),
                'class' => $this->request->getPost('class'),
                'section' => $this->request->getPost('section'),
                'first_sem_tardy' => $this->request->getPost('first_sem_tardy'),
                'second_sem_tardy' => $this->request->getPost('second_sem_tardy'),
                'first_sem_absent' => $this->request->getPost('first_sem_absent'),
                'second_sem_absent' => $this->request->getPost('second_sem_absent'),
                'session' => active_session(),
            ];

            $model = new StudentEvaluation();
            $record = (new StudentEvaluation())->find($this->request->getPost('evaluation_id'));
             if ($record)
                 $to_db['id'] = $record['id'];
                try {
                    if ($model->save($to_db)) {
                        $return = TRUE;
                        $msg = "Evaluation updated successfully";
                    } else {
                        $return = FALSE;
                        $msg = "Failed to update evaluation";
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

    public function save_kg_evaluation()
    {
        if($this->request->getPost()) {

            $data = array();
            foreach ($this->request->getPost('remark') as $key => $value){
                foreach ($value as $k => $value2){
                    foreach ($value2 as $k2 => $v){
                        array_push($data,array('id'=>$key,'key'=>$k,'sem'=>$k2,'value'=>$v));
                    }

                }
            }

            $to_db = [
                'student' => $this->request->getPost('student'),
                'remark' => json_encode($data),
                'class' => $this->request->getPost('class'),
                'section' => $this->request->getPost('section'),
                'first_sem_tardy' => $this->request->getPost('first_sem_tardy'),
                'second_sem_tardy' => $this->request->getPost('second_sem_tardy'),
                'first_sem_absent' => $this->request->getPost('first_sem_absent'),
                'second_sem_absent' => $this->request->getPost('second_sem_absent'),
                'session' => active_session(),
            ];


            $model = new StudentEvaluation();

            $record = (new StudentEvaluation())->find($this->request->getPost('evaluation_id'));
            if (empty($record)) {
                try {
                    if ($model->save($to_db)) {
                        $return = TRUE;
                        $msg = "Evaluation saved successfully";
                    } else {
                        $return = FALSE;
                        $msg = "Failed to save evaluation";
                    }
                } catch (\ReflectionException $e) {
                    $return = FALSE;
                    $msg = $e->getMessage();
                }
            }
            else {
                try {
                    $db = \Config\Database::connect();
                    $builder = $db->table('student_evaluations');
                    $builder->where('id', $this->request->getPost('evaluation_id'));
                    if ($model->save($to_db)) {
                        $return = TRUE;
                        $msg = "Evaluation updated successfully";
                    } else {
                        $return = FALSE;
                        $msg = "Failed to update evaluation";
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
        $return = TRUE;
        $msg = 'success';
        $model = new Homeroom();
        if($this->request->getPost()) {
            $first_sem_comment = $this->request->getPost('first_sem_comment');
            $second_sem_comment = $this->request->getPost('second_sem_comment');
            $first_sem_sign = $this->request->getPost('first_sem_sign')?:0;
            $second_sem_sign = $this->request->getPost('second_sem_sign')?:0;

            $to_db = [
                'session' => active_session(),
                'class' => $this->request->getPost('class'),
                'section' => $this->request->getPost('section'),
                'first_sem_comment' => $first_sem_comment,
                'second_sem_comment' => $second_sem_comment,
                'first_sem_sign' => $first_sem_sign,
                'second_sem_sign' => $second_sem_sign,
                'student' => $this->request->getPost('id')
            ];

            if ($first_sem_comment !='none'){
               if ($first_sem_sign ==0){
                   $msg = "First Semester Sign is required.";
                   $return = FALSE;
               }
            }else{
             if ($first_sem_sign !=0){
                 $msg = "First Semester Comment must be selected first.";
                 $return = FALSE;
             }
            }

            if ($second_sem_comment !='none'){
                if ($second_sem_sign ==0){
                    $msg = "Second Semester Sign is required.";
                    $return = FALSE;
                }
                if ($first_sem_sign =='' || $first_sem_comment ==''){
                    $msg = "First & Second Semester Sign & Comment are required.";
                    $return = FALSE;
                }
            }else{
                if ($second_sem_sign !=0){
                    $msg = "Second Semester Comment must be selected first.";
                    $return = FALSE;
                }
            }

            $record = (new Homeroom())->find($this->request->getPost('homeroom_id'));
            if ($record)
                $to_db['id'] = $record['id'];

           $deleted = FALSE;
            if (($first_sem_comment =='none' &&  $first_sem_sign==0)){
                if ($record){
                    $model->delete($record['id']);
                    $deleted = TRUE;
                }
            }elseif ($second_sem_comment =='none' && $second_sem_sign ==0){
                if ($record){
                    $model->delete($record['id']);
                    $deleted = TRUE;
                }
            }
            if (!$deleted)
                $model->save($to_db);
            if ($return) {
                try {
                    if ($return) {
                        $return = TRUE;
                        $msg = "Homeroom Comment & Sign saved successfully";
                    } else {
                        $return = FALSE;
                        $msg = "Failed to Homeroom Comment";
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
                    'callback'  => $return ? 'window.location.reload()' : '',
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            }

            return redirect()->to(previous_url())->with($status, $msg);
        }
    }
    public function director_sign()
    {
        if($this->request->getPost()) {
            if (!isset($_POST['data']['single_sign'])){
                $section = (new Sections())->find($_POST['data']['section']);
                $students = $section->students;
                foreach ($students as $student) {
                    $to_db = [
                        'student' => $student->id,
                        'session' => active_session(),
                        'class' => $_POST['data']['class'],
                        'section' => $_POST['data']['section'],
                        'is_signed' => $_POST['data']['is_signed']
                    ];

                    $record = (new DirectorSign())->where('session', active_session())->where('student', $student->id)->first();
                    if ($record)
                        $to_db['id'] = $record['id'];
                        $model = new DirectorSign();
                        try {
                            $return = FALSE;
                            if ($_POST['data']['is_signed'] ==0){
                                if ($record){
                                  $model->delete($record['id']);
                                  $return = TRUE;
                                }
                            }else {
                                $model->save($to_db);
                                $return = TRUE;
                            }
                            if ($return) {
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

            }else {
                $to_db = [
                    'student' => $_POST['data']['student'],
                    'session' => active_session(),
                    'class' => $_POST['data']['class'],
                    'section' => $_POST['data']['section'],
                    'is_signed' => $_POST['data']['is_signed']
                ];
                $record2 = (new DirectorSign())->where('session', active_session())->where('student',$_POST['data']['student'])->first();
                if ($record2)
                    $to_db['id'] = $record2['id'];
                    $model = new DirectorSign();
                    try {
                        $return = FALSE;
                        if ($_POST['data']['is_signed'] ==0){
                            if ($record2){
                                $model->delete($record2['id']);
                                $return = TRUE;
                            }
                        }else {
                            $model->save($to_db);
                            $return = TRUE;
                        }
                        if ($return) {
                            $return = TRUE;
                            $msg = "Director Sign  saved successfully";
                        } else {
                            $return = FALSE;
                            $msg = "Failed to save Director Sign";
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
        $remove_second_semester = get_option('turn_off_semester_2');
        if (!$remove_second_semester){
            if ($class->type =='kg')
                return view('Academic/Certificate/print_kg', $this->data);
            if (is_quarter_plus_session())
                return view('Academic/Certificate/quarters/print_plus', $this->data);

            if (is_quarter_session()){
                return view('Academic/Certificate/quarters/print', $this->data);
            }
        }
        else {
            if ($class->type == 'kg')
                return view('Academic/Certificate/sem1_only/print_kg', $this->data);
            if (is_quarter_plus_session())
                return view('Academic/Certificate/quarters/print_plus', $this->data);

            if (is_quarter_session()) {
                return view('Academic/Certificate/quarters/print', $this->data);
            }
            return view('Academic/Certificate/sem1_only/print', $this->data);
        }

        return view('Academic/Certificate/print', $this->data);
    }
}