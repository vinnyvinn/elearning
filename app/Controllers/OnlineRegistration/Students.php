<?php


namespace App\Controllers\OnlineRegistration;


use App\Entities\File;
use App\Entities\Student;
use App\Libraries\IonAuth;
use App\Models\Classes;
use App\Models\Files;
use App\Models\Registrations;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Students extends \App\Controllers\AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

    }

    public function newStudents()
    {
        $model = new Registrations();
        $students = $model->where('type', 'student')->where('existing', "0")->orderBy('created_at', 'DESC')->findAll();
        $this->data['students'] = $students;

        $std = array();
        if ($this->request->getPost() && $this->request->getPost('class') !='all'){
            $this->data['class'] = $this->request->getPost('class');
            foreach ($students as $student){
                if ($student->info->class == $this->request->getPost('class')) {
                 array_push($std,$student);
                }
            }
           $this->data['students'] = $std;
           $this->data['grade'] = (new Classes())->find($this->request->getPost('class'))->name;
        }else {
          $this->data['grade'] = "ALL STUDENTS";
          $this->data['class'] = "all";
        }
        $class = $this->request->getPost('class') !='all' && $this->request->getPost('class') ? (new Classes())->find($this->request->getPost('class'))->name : "ALL STUDENTS";
        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n Online Registration - New Students \n".getSession()->name."\n".$class;
        return $this->_renderPageCustom('OnlineRegistration/newStudents', $this->data);
    }

    public function print()
    {
        $class = $_GET['class'];
        $model = new Registrations();
        $std = array();
        $model->where('type', 'student')->where('existing', "0");
        if ($class!='all'){
            foreach ($model->findAll() as $student){
                if ($student->info->class == $class) {
                    array_push($std,$student);
                }
            }
            $this->data['students'] = $std;
            $this->data['grade'] = (new Classes())->find($class)->name;
        }else {
            $this->data['grade'] = "ALL STUDENTS";
            $this->data['students'] = $model->findAll();
        }
        return view('OnlineRegistration/new/list/print', $this->data);
    }
    public function printExisting()
    {
        $class = $_GET['class'];
        $model = new Registrations();
        $std = array();
        $model->where('type', 'student')->where('existing', "1");
        if ($class!='all'){
            foreach ($model->findAll() as $student){
                if ($student->info->class == $class) {
                    array_push($std,$student);
                }
            }
            $this->data['students'] = $std;
            $this->data['grade'] = (new Classes())->find($class)->name;
        }else {
            $this->data['grade'] = "ALL STUDENTS";
            $this->data['students'] = $model->findAll();
        }
        return view('OnlineRegistration/new/list/print', $this->data);
    }
    public function pdf()
    {
        $class = $_GET['class'];
        $model = new Registrations();
        $std = array();
        $model->where('type', 'student')->where('existing', "0")->where("session",active_session());
        if ($class!='all'){
            foreach ($model->findAll() as $student){
                if ($student->info->class == $class) {
                    array_push($std,$student);
                }
            }
            $this->data['students'] = $std;
            $this->data['grade'] = (new Classes())->find($class)->name;
        }else {
            $this->data['grade'] = "ALL STUDENTS";
            $this->data['students'] = $model->findAll();
        }

       return view('OnlineRegistration/new/list/pdf',$this->data);
    }

    public function pdfExisting()
    {
        $class = $_GET['class'];
        $model = new Registrations();
        $std = array();
        $model->where('type', 'student')->where('existing', "1")->where("session",active_session());
        if ($class!='all'){
            foreach ($model->findAll() as $student){
                if ($student->info->class == $class) {
                    array_push($std,$student);
                }
            }
            $this->data['students'] = $std;
            $this->data['grade'] = (new Classes())->find($class)->name;
        }else {
            $this->data['grade'] = "ALL STUDENTS";
            $this->data['students'] = $model->findAll();
        }

        return view('OnlineRegistration/exist/list/pdf',$this->data);
    }
    function exportExcel()
    {
        $class = $_GET['class'];
        $model = new Registrations();
        $std = array();
        $model->where('type', 'student')->where('existing', "0")->where("session",active_session());
        if ($class!='all'){
            foreach ($model->findAll() as $student){
                if ($student->info->class == $class) {
                    array_push($std,$student);
                }
            }
            $students = $std;
            $grade = (new Classes())->find($class)->name;
        }else {
            $grade = "ALL STUDENTS";
            $students = $model->findAll();
        }

        $file_name = 'New Student Registration List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n New Student Registration List \n".getSession()->name."\n".$grade;
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
        $sheet->getColumnDimension("D")->setWidth(20);
        $sheet->getColumnDimension("E")->setWidth(20);
        $sheet->getColumnDimension("F")->setWidth(20);

        // column headers
        $sheet->setCellValue('A2', 'Name');
        $sheet->setCellValue('B2', 'D.O.B');
        $sheet->setCellValue('C2', 'Class');
        $sheet->setCellValue('D2', "Parent's Name");
        $sheet->setCellValue('E2', 'Application Date');
        $sheet->setCellValue('F2', 'Status');

        $count = 3;
        foreach($students as $row)
        {
            $sheet->setCellValue('A' . $count, $row->name);
            $sheet->setCellValue('B' . $count, $row->dob);
            $sheet->setCellValue('C' . $count, isset($row->name) ? $row->name : '');
            $sheet->setCellValue('D' . $count, @$row->parent->surname.' '.$row->parent->first_name.' '.$row->parent->last_name);
            $sheet->setCellValue('E' . $count, @$row->parent->mobile_number);
            $sheet->setCellValue('F' . $count, $row->status);
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

    function exportExcelExisting()
    {
        $class = $_GET['class'];
        $model = new Registrations();
        $std = array();
        $model->where('type', 'student')->where('existing', "1")->where("session",active_session());
        if ($class!='all'){
            foreach ($model->findAll() as $student){
                if ($student->info->class == $class) {
                    array_push($std,$student);
                }
            }
            $students = $std;
            $grade = (new Classes())->find($class)->name;
        }else {
            $grade = "ALL STUDENTS";
            $students = $model->findAll();
        }

        $file_name = 'Existing Student Registration List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n Existing Student Registration List \n".getSession()->name."\n".$grade;
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
        $sheet->getColumnDimension("D")->setWidth(20);
        $sheet->getColumnDimension("E")->setWidth(20);
        $sheet->getColumnDimension("F")->setWidth(20);

        // column headers
        $sheet->setCellValue('A2', 'Name');
        $sheet->setCellValue('B2', 'D.O.B');
        $sheet->setCellValue('C2', 'Class');
        $sheet->setCellValue('D2', "Parent's Name");
        $sheet->setCellValue('E2', 'Application Date');
        $sheet->setCellValue('F2', 'Status');

        $count = 3;
        foreach($students as $row)
        {
            $sheet->setCellValue('A' . $count, $row->name);
            $sheet->setCellValue('B' . $count, $row->dob);
            $sheet->setCellValue('C' . $count, isset($row->name) ? $row->name : '');
            $sheet->setCellValue('D' . $count, @$row->parent->surname.' '.$row->parent->first_name.' '.$row->parent->last_name);
            $sheet->setCellValue('E' . $count, @$row->parent->mobile_number);
            $sheet->setCellValue('F' . $count, $row->status);
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

    public function viewNewStudent($id)
    {
        $student = (new Registrations())->where('existing', "0")->find($id);
        if(!$student) {
            return redirect()->to(previous_url())->with('error', "Registration entry not found");
        }

        $this->data['site_title'] = "New Student Registration";
        $this->data['student'] = $student;

        return $this->_renderPage('OnlineRegistration/viewNewStudents', $this->data);
    }

    public function existing()
    {
        $model = new Registrations();
        $students = $model->where('type', 'student')->where('existing', "1")->orderBy('created_at', 'DESC')->where("session",active_session())->findAll();
        $this->data['students'] = $students;

        $std = array();
        if ($this->request->getPost() && $this->request->getPost('class') !='all'){
            $this->data['class'] = $this->request->getPost('class');
            foreach ($students as $student){
              if ($student->info->class == $this->request->getPost('class')) {
                  array_push($std,$student);
              }
            }
            $this->data['students'] = $std;
        }else {
            $this->data['grade'] = "ALL STUDENTS";
            $this->data['class'] = "all";
        }

        $class = $this->request->getPost('class') !='all' && $this->request->getPost('class') ? (new Classes())->find($this->request->getPost('class'))->name : "ALL STUDENTS";
        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n Online Registration - Existing Students \n".getSession()->name."\n".$class;

        return $this->_renderPageCustom('OnlineRegistration/existingStudents', $this->data);
    }

    public function viewExistingStudent($id)
    {
        $student = (new Registrations())->where('existing', "1")->find($id);

        if(!$student) {
            return redirect()->to(previous_url())->with('error', "Registration entry not found");
        }

        $this->data['site_title'] = "Existing Student Registration";
        $this->data['student'] = $student;

        return $this->_renderPage('OnlineRegistration/viewExistingStudents', $this->data);
    }

    public function downloadSlip($id)
    {
        $student = (new Registrations())->find($id);
        if(!$student) {
            return redirect()->back()->with('error', "Registration entry not found");
        }

        if(isset($student->info->deposit_slip) && $student->info->deposit_slip != '' && file_exists(FCPATH.'/uploads/deposit-slips/'.$student->info->deposit_slip)) {
            return $this->response->download('Deposit_slip_'.$student->info->deposit_slip, file_get_contents(FCPATH.'/uploads/deposit-slips/'.$student->info->deposit_slip), FALSE);
        }

        return redirect()->back()->with('error', "Deposit slip not found");
    }

    public function deleteNewStudent($id)
    {
        $student = (new Registrations())->find($id);
        if($student) {
            //Delete files first
            @unlink(FCPATH.'uploads/deposit-slips/'.$student->info->deposit_slip);
            @unlink(FCPATH.'uploads/avatars/'.$student->parent->avatar);
            @unlink(FCPATH.'uploads/avatars/'.$student->info->profile_pic);

            if((new Registrations())->delete($student->id)) {
                return redirect()->to(site_url(route_to('admin.registration.online.students.new_students')))->with('success', "Deleted successfully");
            }
        }

        return redirect()->to(site_url(route_to('admin.registration.online.students.new_students')))->with('error', "Entry not found");
    }

    public function registerStudent($id)
    {
        $model = new \App\Models\Students();

        $db = \Config\Database::connect();
        $to_db =array('status'=>'registered');
        $builder = $db->table('applications');
        $builder->where('id', $id);
        $builder->update($to_db);

        if ($model->registerStudents($id,$this->request->getPost('class'),$this->request->getPost('section'))){
            return redirect()->to(site_url(route_to('admin.registration.online.students.new_students')))->with('success', "Registered successfully");
        }

        return redirect()->to(site_url(route_to('admin.registration.online.students.new_students')))->with('error', "Fail to register student.");
    }


    public function deleteExistingStudent($id)
    {
        $student = (new Registrations())->find($id);
        if($student) {
            //Delete files first
            @unlink(FCPATH.'uploads/deposit-slips/'.$student->info->deposit_slip);
            @unlink(FCPATH.'uploads/avatars/'.$student->parent->avatar);
            @unlink(FCPATH.'uploads/avatars/'.$student->info->profile_pic);

            if((new Registrations())->delete($student->id)) {
                return redirect()->to(site_url(route_to('admin.registration.online.students.new_students')))->with('success', "Deleted successfully");
            }
        }

        return redirect()->to(site_url(route_to('admin.registration.online.students.new_students')))->with('error', "Entry not found");
    }
}