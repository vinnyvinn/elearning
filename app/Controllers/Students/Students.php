<?php


namespace App\Controllers\Students;


use App\Controllers\ProfileController;
use App\Libraries\SMS;
use App\Models\Classes;
use App\Models\Departure;
use App\Models\Exams;
use App\Models\Sections;
use CodeIgniter\Database\Config;
use CodeIgniter\Exceptions\PageNotFoundException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Students extends ProfileController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index() {
        //List of students... probably filter first. The list could be enormous

        $model = new \App\Models\Students();
        $class = 'all';
        $section = 0;
        if($cls = $this->request->getGet('class')) {
            if(is_numeric($cls)) {
                $model->where('class', $cls);

                $sctn = $this->request->getGet('section');
                if(isset($sctn) && is_numeric($sctn)) {
                    $model->where('section', $sctn);
                }
            }
        }


        if ($this->request->getPost()){

            $model2 = new \App\Models\Students();
            $model2->select("students.*,students.id as id");
            $model2->join('users','users.id=students.user_id');
            $model2->orderBy('users.surname, users.first_name,users.last_name');
            $model2->where('session', active_session());
            if (count(departedIds()) > 0)
            $model2->whereNotIn('students.id',departedIds());
           //$model2->where('students.active', 1);
            $class = $this->request->getPost('class');
            $section = $this->request->getPost('section');
            $model2->where('students.class',$class);

            if ($class !='all')
                $model2->where('students.class',$class);

            if ($section !=0)
                $model2->where('section',$section);
            $this->data['students'] = $model2->findAll();
        }else {
          //$this->data['students'] = getSession()->students;
            $model1 = new \App\Models\Students();
            if (count(departedIds()) > 0)
                $model1->whereNotIn('id',departedIds());
          $this->data['students'] = $model1->where('session',active_session())->findAll();
        }

        $this->data['class'] = $class;
        $this->data['section'] = $section;
        $phones = get_option('id_phone') ? json_decode(get_option('id_phone')) :'';
        $phones = $phones ? implode(' , ',$phones) : '';
       $this->data['site_title'] = get_option('id_school_name')."\n".' Tel: '.$phones."\n". getSession()->name."\n".($class && $section ? (new Classes())->find($class)->name.(new Sections())->find($section)->name."\n".' Students List': 'ALL STUDENT'. "\n" .'Students List');

        return $this->_renderPageCustom('Admin/Students/index', $this->data);
    }


    function export()
    {
        $class = $_GET['class'];
        $section = $_GET['section'];

        $model2 = new \App\Models\Students();
        $model2->select("students.*,students.id as id");
        $model2->join('users','users.id=students.user_id');
        $model2->orderBy('users.surname, users.first_name,users.last_name');
        $model2->where('session', active_session());
        if (count(departedIds()) > 0)
            $model2->whereNotIn('students.id',departedIds());
        //$model2->where('students.active', 1);
        if ($class !='all')
            $model2->where('students.class',$class);

        if ($section !=0)
        $model2->where('section',$section);
        $students = $model2->findAll();


        $file_name = ($class && $section) ? 'Students List '.getSession()->year.' '.(new Classes())->find($class)->name.(new Sections())->find($section)->name.'.xlsx' : ' Students List '.getSession()->year.' ALL STUDENT.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $phones = get_option('id_phone') ? json_decode(get_option('id_phone')) :'';
        $phones = $phones ? implode(' , ',$phones) : '';
        $title = get_option('id_school_name')."\n".' Tel: '.$phones."\n". getSession()->name."\n".($class && $section ? (new Classes())->find($class)->name.(new Sections())->find($section)->name."\n".' Students List': 'ALL STUDENT'. "\n" .'Students List');
        $sheet->setCellValue("A1","$title");

        //Merge cells
        $sheet->mergeCells('A1:I1');

        $sheet->getStyle("A1")->applyFromArray(
          array(
              'font'=> array('size'=>24,'bold'=>true)
          )
        );

        //Alignment
        $sheet->getStyle("A1")->getAlignment()->setHorizontal("center");

        //adjust dimensions
        $sheet->getColumnDimension("A")->setWidth(30);
        $sheet->getRowDimension("1")->setRowHeight(150);
        $sheet->getColumnDimension("B")->setWidth(20);
        $sheet->getColumnDimension("C")->setWidth(20);
        $sheet->getColumnDimension("D")->setWidth(20);
        $sheet->getColumnDimension("E")->setWidth(20);

        // column headers
        $sheet->setCellValue('A2', 'Student Name');
        $sheet->setCellValue('B2', 'Admission Number');
        $sheet->setCellValue('C2', 'Class');
        $sheet->setCellValue('D2', 'Section');
        $sheet->setCellValue('E2', 'Admission Date');

        $count = 3;
        foreach($students as $row)
        {
            $sheet->setCellValue('A' . $count, $row->profile->name);
            $sheet->setCellValue('B' . $count, $row->admission_number);
            $sheet->setCellValue('C' . $count, $row->class->name);
            $sheet->setCellValue('D' . $count, $row->section->name);
            $sheet->setCellValue('E' . $count, $row->admission_date);
            $count++;
        }

        $writer = new Xlsx($spreadsheet);

        $writer->save($file_name);

        header("Content-Type: application/vnd.ms-excel");

        header('Content-Disposition: attachment; filename="' . basename($file_name) . '"');

        header('Expires: 0');

        header('Cache-Control: must-revalidate');

        header('Pragma: public');

        header('Content-Length:' . filesize($file_name));

        flush();

        readfile($file_name);

        exit;
    }
    function printList()
    {
        $class = $_GET['class'];
        $section = $_GET['section'];

        $model2 = new \App\Models\Students();
        $model2->select("students.*,students.id as id");
        $model2->join('users','users.id=students.user_id');
        $model2->orderBy('users.surname, users.first_name,users.last_name');
        $model2->where('session', active_session());
        if (count(departedIds()) > 0)
            $model2->whereNotIn('students.id',departedIds());
        //$model2->where('students.active', 1);
        if ($class !='all')
            $model2->where('students.class',$class);

        if ($section !=0)
        $model2->where('section',$section);
        $students = $model2->findAll();

        $phones = get_option('id_phone') ? json_decode(get_option('id_phone')) :'';
        $phones = $phones ? implode(' , ',$phones) : '';

        return view("Admin/Students/list/print",['students'=>$students,'phones'=>$phones,'class'=>$class,'section'=>$section]);

    }
    public function transcript() {
        $students = (new Departure())->getAllStudents();

        $this->data['students'] = $students;
        if ($this->request->getPost()){
            if ($this->request->getPost('session') =='all'){
                $students = $students;
            }

            else{
                $depart = (new \App\Models\Departure())->where('session',$this->request->getPost('session'))->findAll();
                if ($depart){
                    $stids = array_column($depart,'student');
                    $students = (new \App\Models\Students())->whereIn('id',$stids)->findAll();
                }else{
                    $students =[];
                }
            }
            $this->data['students'] = $students;
        }

        return $this->_renderPage('Admin/Students/transcript/index', $this->data);
    }

    public function saveYears()
    {
        update_option('no_of_years', $this->request->getPost('no_of_years'));
        $return = TRUE;
        if ($this->request->isAJAX()) {
            $resp = [
                'title' => $return ? 'Success' : 'Error',
                'message' => 'Successfully updated years',
                'status' => 'success',
                'notifyType' => 'swal',
                'callbackTime' => 'onconfirm',
                'showCancelButton' => false,
                'callback' => $return ? 'window.location = "' . site_url(route_to('admin.students.transcript')) . '"' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
    }
    public function updateLetter($id)
    {
        if ($photo = $this->request->getFile('letter_photo')){
            if ($photo->isValid() && !$photo->hasMoved()) {
                $newName = $photo->getRandomName();
                if ($photo->move(FCPATH . 'uploads/files/', $newName)) {
                    update_option('letter_photo'.$id, $newName);
                }
            }
        }
        update_option('class_when_leaving'.$id,$this->request->getPost('class_when_leaving'));
        update_option('remaining_payment'.$id,$this->request->getPost('remaining_payment'));
        update_option('reason_for_leaving'.$id,$this->request->getPost('reason_for_leaving'));
        update_option('learning_program'.$id,$this->request->getPost('learning_program'));
        update_option('class_to_promote'.$id,$this->request->getPost('class_to_promote'));
        update_option('date_of_departure'.$id,$this->request->getPost('date_of_departure'));
        update_option('student_conduct'.$id,$this->request->getPost('student_conduct'));
        update_option('letter_no'.$id,$this->request->getPost('letter_no'));
        $return = TRUE;
        if ($this->request->isAJAX()) {
            $resp = [
                'title' => $return ? 'Success' : 'Error',
                'message' => 'Successfully updated letter',
                'status' => 'success',
                'notifyType' => 'swal',
                'callbackTime' => 'onconfirm',
                'showCancelButton' => false,
                'callback' => $return ? 'window.location = "' . site_url(route_to('admin.students.departure')) . '"' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

       // return redirect(site_url(route_to('admin.students.departure')));

    }

    public function updateTranscript($id)
    {
        if ($photo = $this->request->getFile('transcript_photo')){
            if ($photo->isValid() && !$photo->hasMoved()) {
                $newName = $photo->getRandomName();
                if ($photo->move(FCPATH . 'uploads/files/', $newName)) {
                    update_option('transcript_photo'.$id, $newName);
                }
            }
        }

        update_option('transcript_date_of_leaving'.$id,$this->request->getPost('transcript_date_of_leaving'));
        update_option('transcript_remarks'.$id,$this->request->getPost('transcript_remarks'));
        $return = TRUE;
        if ($this->request->isAJAX()) {
            $resp = [
                'title' => $return ? 'Success' : 'Error',
                'message' => 'Successfully updated transcript',
                'status' => 'success',
                'notifyType' => 'swal',
                'callbackTime' => 'onconfirm',
                'showCancelButton' => false,
                'callback' => $return ? 'window.location = "' . site_url(route_to('admin.students.transcript')) . '"' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        // return redirect(site_url(route_to('admin.students.departure')));

    }
    public function departure() {
        return $this->_renderPage('Admin/Students/departure/index');
    }

    public function departing() {
     return $this->_renderPage('Admin/Students/departure/departing_index');
    }

    public function departStd()
    {
        if($this->request->getPost()){
            $oldSession = $this->request->getPost('oldSession');
            $oldClass = $this->request->getPost('oldClass');
            $students = $this->request->getPost('student');

            $return = true;
            $msg = 'Success';
            if (empty($students)) {
                $return = false;
                //$msg = "Please select at least one student";
                $msg = (string) json_encode($students);
            } else {
                $departure_model = new Departure();
                $student_model = new \App\Models\Students();
                //start here @vinnyvinny
                foreach ($students as $key => $student) {
                    $to_db = array('session'=>$oldSession,'student'=>$student,'class'=>$oldClass,'type'=>'departure','count'=>$this->request->getPost('cool_count')?:0,'semester'=>$this->request->getPost('semester'));
                    try {
                        if($departure_model->save($to_db)) {
                            $insert = array('id'=> $student,'active'=>0);
                            $student_model->save($insert);
                            $return = TRUE;
                            $msg = "Selected Students departed successfully";
                        } else {
                            $return = FALSE;
                            $msg = "Some students were not departed";
                        }
                    } catch (\ReflectionException $e) {
                        $return = FALSE;
                        $msg = $e->getMessage();
                    }
                }
            }

        } else {
            $return = FALSE;
            $msg = "Invalid request";
        }

        $status = $return ? 'success' : 'error';
        $resp = [
            'title'     => $return ? 'Success' : 'error',
            'message'   => $msg,
            'status'    => $status,
            'notifyType'    => 'swal',
            'callbackTime' => 'onconfirm',
            'showCancelButton' => false,
            'callback'  => $return ? 'window.location.reload()' : ''
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($resp));
    }

    public function printTranscript($id) {
       $student = (new \App\Models\Students())->find($id);

        return view('Admin/Students/transcript/print', ['student'=>$student]);
    }
    public function printLetter($id) {
        $student = (new \App\Models\Students())->find($id);

        return view('Admin/Students/transcript/print-letter', ['student'=>$student]);
    }

    public function create()
    {
        if($this->request->getPost()) {
            $model = new \App\Models\Students();
            $result = $model->createStudent();
            if($result === TRUE) {
                $return = TRUE;

                if(isset($model->parentContact) && $model->parentContact) {
                    $sms = new SMS();

                    $sms_message = "Congratulations you have Successfully Registered to Aspire Youth Academy\n";
                    $sms_message .= "School Portal URL: ".site_url(route_to('auth.login'))."\n";
                    $sms_message .= "Student Username: {$model->studentUsername}\n";
                    $sms_message .= "Student Password: {$model->studentPassword}\n";
                    $sms_message .= "Parent Username: {$model->parentUsername}\n";
                    $sms_message .= "Parent Password: {$model->parentPassword}";

                    @$sms->sendSMS($sms_message, $model->parentContact);
                }

                $msg = "Students updated successfully";
            } else {
                $return = FALSE;
                if(is_array($result)) {
                    $msg = implode(". ", $result);
                } else {
                    $msg = "Failed to update students";
                }
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
                    'callback'  => 'window.location.reload()'
                ];

                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $msg = $return ? $msg : $result;
                $this->session->setFlashData($status, $msg);
                return redirect()->to(current_url())->withInput();
            }
        } else {
            return $this->_renderPage('Admin/Students/create', $this->data);
        }

    }

    public function view($id)
    {
        $this->data['student'] = (new \App\Models\Students())->find($id);
        $this->data['page'] = 'profile';
        return $this->_renderSection('index', $this->data);
    }

    public function exams($id)
    {
        $this->data['student'] = (new \App\Models\Students())->find($id);
        $this->data['page'] = 'exams';
        return $this->_renderSection('exams', $this->data);
    }

    public function assignments($id)
    {
        $this->data['student'] = (new \App\Models\Students())->find($id);
        $this->data['page'] = 'assignments';
        return $this->_renderSection('assignments', $this->data);
    }

    public function fees($id)
    {
        $this->data['student'] = (new \App\Models\Students())->find($id);
        $this->data['page'] = 'fees';
        return $this->_renderSection('fees', $this->data);
    }

    public function results($student, $exam)
    {
        $this->data['student'] = (new \App\Models\Students())->find($student);
        $this->data['exam'] = (new Exams())->find($exam);
        if(!$this->data['student']) throw new PageNotFoundException("Student Not Found");
        if(!$this->data['exam']) throw new PageNotFoundException("Exam Not Found");

        return $this->_renderPage('Admin/Students/exam_results', $this->data);
    }

    public function edit($id)
    {
        $model = new \App\Models\Students();
        if($this->request->getPost()) {
            $result = $model->updateStudent($id);
            if($result === TRUE) {
                $return = TRUE;
                $msg = "Student updated successfully";
            } else {
                $return = FALSE;
                $msg = implode(". ", $result);
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
                    'callback'  => 'window.location.reload()'
                ];

                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $this->session->setFlashData($status, $msg);
                return $this->response->redirect(current_url());
            }
        } else {
            $this->data['student'] = $model->find($id);
            return $this->_renderPage('Admin/Students/edit', $this->data);
        }
    }

    public function delete($id)
    {
        //Delete student
        $model = new \App\Models\Students();
        $student = $model->find($id);
        //TODO: Probably delete all the files attached to this student, his parents and contacts

        $return = FALSE;
        $msg = "Failed to delete student";
        if($model->delete($id)) {
        //if(true) {
            $return = TRUE;
            $msg = "Student deleted successfully";
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
                'callback'  => $return ? 'window.location = "'.site_url(route_to('admin.students.index')).'"' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $this->session->setFlashData($status, $msg);
            return $this->response->redirect($return ? site_url(route_to('admin.students.index')) : current_url());
        }
    }

    public function _renderSection($view, $data = [])
    {
        $this->data['html'] = view('Admin/Students/Views/'.$view, $data);

        return $this->_renderPage('Admin/Students/view', $this->data);
    }
}