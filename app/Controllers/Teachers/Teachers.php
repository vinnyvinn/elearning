<?php


namespace App\Controllers\Teachers;


use App\Controllers\AdminController;
use App\Entities\Subjectteacher;
use App\Libraries\SMS;
use App\Models\IonAuthModel;
use App\Models\Subjectteachers;
use App\Models\User;
use http\Env\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Teachers extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
//        $auth = new IonAuthModel();
//
//        $teachers = (new \App\Models\Teachers())->findAll();
//        foreach ($teachers as $teacher){
//          $user = new \App\Entities\User();
//          $password = random_string('alnum');
//          $user->id = $teacher->user_id;
//          $user->update_usermeta('password', $password);
//          $hashed = $auth->hashPassword($password);
//
//            $db = \Config\Database::connect();
//            $builder = $db->table('users');
//            $builder->where('id',$teacher->user_id);
//            $builder->update(array('password'=>$hashed));
//        }
        $this->data['site_title'] =get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Teachers List";

        return $this->_renderPageCustom('Admin/Teachers/index', $this->data);

    }

    public function pdf()
    {
        $this->data['teachers'] = (new \App\Models\Teachers())->where('session',active_session())->findAll();
        return view('Admin/Admins/list/pdf', $this->data);
    }
    public function print()
    {
      $this->data['teachers'] = (new \App\Models\Teachers())->where('session',active_session())->findAll();
      return view('Admin/Teachers/list/print', $this->data);
    }
    function exportExcel()
    {
        $teachers = (new \App\Models\Teachers())->where('session',active_session())->findAll();

        $file_name = 'Teachers List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Teachers List";
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
        $sheet->getRowDimension("1")->setRowHeight(120);
        $sheet->getColumnDimension("B")->setWidth(20);
        $sheet->getColumnDimension("C")->setWidth(20);

        // column headers
        $sheet->setCellValue('A2', 'Name');
        $sheet->setCellValue('B2', 'Teacher ID');
        $sheet->setCellValue('C2', 'Phone');

        $count = 3;
        foreach($teachers as $row)
        {
            $sheet->setCellValue('A' . $count, $row->profile->name);
            $sheet->setCellValue('B' . $count, $row->teacher_number);
            $sheet->setCellValue('C' . $count, $row->profile->phone);
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
    public function view($id)
    {
        $teacher = (new \App\Models\Teachers())->find($id);
        if(!$teacher) return redirect()->back();
        $this->data['teacher'] = $teacher;

        return $this->_renderPage('Admin/Teachers/view', $this->data);
    }

    public function unassign()
    {
        
    }
    public function create()
    {

        if($this->request->getPost()) {
            if (isset($_POST['is_director']) && $_POST['is_director'] ==1){
                if (!isset($_POST['director_classes']) || empty($_POST['director_classes'])){
                    $return = FALSE;
                    $msg = "Classes filed field is required.";

                    $resp = $this->json_response($return,$msg,'error',route_to('admin.teachers.index'));
                    return $this->response->setContentType('application/json')->setBody(json_encode($resp));
                }
            }



            $model = new \App\Models\Teachers();
            $result = $model->createTeacher();
            if($result === TRUE) {
                $return = TRUE;
                $msg = "Teachers updated successfully";
            } else {
                $return = FALSE;
                $msg = $result;
            }
            $status = $return ? 'success' : 'error';
            if($this->request->isAJAX()) {
                $resp = $this->json_response($return,$msg,$status,route_to('admin.teachers.index'));
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $this->session->setFlashData($status, $msg);
                return $this->response->redirect(site_url(route_to('admin.teachers.index')));
            }
        } else {
            return $this->_renderPage('Admin/Teachers/create', $this->data);
        }
    }

    public function json_response($return,$msg,$status,$url)
    {
        return [
            'title'     => $return ? 'Success' : 'Error',
            'message'   => $msg,
            'status'    => $status,
            'notifyType'    => 'swal',
            'callbackTime' => 'onconfirm',
            'showCancelButton' => false,
            'callback'  => $return ? 'window.location = "'.site_url($url).'"' : ''
        ];


    }
    public function edit($id)
    {
        $teacher = (new \App\Models\Teachers())->find($id);

        if(!$teacher) {
            return redirect()->back();
        }
        $this->data['teacher'] = $teacher;
        if($this->request->getPost()) {
            if (isset($_POST['is_director']) && $_POST['is_director'] ==1){
                if (!isset($_POST['director_classes']) || empty($_POST['director_classes'])){
                    $return = FALSE;
                    $msg = "Classes filed field is required.";

                    $resp = $this->json_response($return,$msg,'error',route_to('admin.teachers.index'));
                    return $this->response->setContentType('application/json')->setBody(json_encode($resp));
                }
            }
            $model = new \App\Models\Teachers();
            $result = $model->updateTeacher($id);
            if($result === TRUE) {
                $return = TRUE;
                $msg = "Teacher updated successfully";
            } else {
                $return = FALSE;
                $msg = $result;
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
                    'callback'  => $return ? 'window.location = "'.site_url(route_to('admin.teachers.view', $teacher->id)).'"' : ''
                ];

                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            } else {
                $this->session->setFlashData($status, $msg);
                return $this->response->redirect(site_url(route_to('admin.teachers.view', $teacher->id)));
            }
        } else {
            return $this->_renderPage('Admin/Teachers/edit', $this->data);
        }
    }

    public function delete($id)
    {
        $model = new \App\Models\Teachers();
        $user = new User();
        $teacher = $model->find($id);
        if(!$teacher) {
            //return redirect()->back();
            $return = FALSE;
            $msg = "Teacher not found";
        }

        if($user->delete($teacher->profile->id)) {
            $return = TRUE;
            $msg = "Teacher deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete teacher";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'status' => $status,
                'notifyType' => 'swal',
                'callbackTime' => 'onconfirm',
                'showCancelButton' => false,
                'callback' => $return ? 'window.location = "'.site_url(route_to('admin.teachers.index')).'"' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(site_url(route_to('admin.teachers.index')));

    }

    public function assignSubject($teacher)
    {
        if ($data = $this->request->getPost()) {
            $model = new Subjectteachers();
            $entity = new Subjectteacher();

            //Check if exists first
            $subject_id = $this->request->getPost('subject_id');
            $class_id = $this->request->getPost('class_id');
            $teacher_id = $this->request->getPost('teacher_id');
            $section_id = $this->request->getPost('section_id');
            $exists = $model->where('subject_id',$subject_id)->where('class_id',$class_id)->where('section_id',$section_id)->where('session',active_session())->first();
            if ($exists){
            if ((new Subjectteachers())->delete($exists->id)){
            }
           }

            try {
                if($model->where('class_id', $class_id)->where('section_id', $section_id)->where('subject_id', $subject_id)->find()){
                    $entity->fill($data);
                    if ($model->where('class_id', $class_id)->where('section_id', $section_id)->where('subject_id', $subject_id)->save($entity)) {
                        $return = true;
                        $msg = "Changes updated successfully";
                    } else {
                        $return = false;
                        $msg = "Failed to record changes";
                    }
                } else {
                    $entity->fill($data);
                    if ($model->save($entity)) {
                        $return = true;
                        $msg = "Changes updated successfully";
                    } else {
                        $return = false;
                        $msg = "Failed to record changes";
                    }
                }
            } catch (\Exception $e) {
                $return = FALSE;
                $msg = $e->getMessage();
            }

        } else {
            $return = false;
            $msg = "Invalid request";
        }
        if ($this->request->isAJAX()) {
            $resp = [
                'notifyType' => 'toastr',
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'status' => $return ? 'success' : 'error',
                'callback' => $return ? 'window.location.reload()' : false
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $status = $return ? 'success' : 'error';
        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url())->withInput();
    }

    public function sendSMS($id)
    {
        $model = new \App\Models\Teachers();
        $teacher = $model->find($id);

        $message = $this->request->getPost('sms');

        $phone = $teacher->profile->phone;
        if($phone && $phone != '') {
            $SMS = new SMS();
            if($SMS->sendSMS($message, $phone)) {
                $return = TRUE;
                $msg = "SMS sent successfully";
            } else {
                $return = FALSE;
                $msg = $SMS->error;
            }
        } else {
            $return = FALSE;
            $msg = "Invalid Mobile Phone";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'status' => $status,
                'notifyType' => 'swal',
                'callbackTime' => 'onconfirm',
                'showCancelButton' => false,
                //'callback' => $return ? 'window.location = "'.site_url(route_to('admin.teachers.view', $teacher->id)).'"' : ''
                'callback' => ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(site_url(route_to('admin.teachers.view', $teacher->id)));
    }
}
