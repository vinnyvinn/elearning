<?php


namespace App\Controllers\Academic;


use App\Controllers\AdminController;
use App\Entities\Requirement;
use App\Models\Classes;
use App\Models\RequirementSubmission;
use App\Models\Students;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Requirements extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Requirements List";
        return $this->_renderPage('Academic/Requirements/index', $this->data);
    }


    public function print()
    {
      $this->data['month'] = isset($month) ? $_GET['month'] : date('m');
      $this->data['class'] = $_GET['class'];
     return view('Academic/Requirements/list/print', $this->data);
    }

    public function pdf()
    {
        $this->data['month'] = isset($month) ? $_GET['month'] : date('m');
        $this->data['class'] = $_GET['class'];
      return view('Academic/Requirements/list/pdf', $this->data);
    }
    function exportExcel()
    {
        $selected_class =  $_GET['class'];
        $selected_month =  $_GET['month'];
        $model = new \App\Models\Requirements();
        $model->where('session', active_session())->findAll();
        if($selected_class != '' && is_numeric($selected_class)) {
            $model->where('class', $selected_class);
        }
        if($selected_month != '' && is_numeric($selected_month)) {
            $selected_month = str_pad($selected_month, 2, '0', STR_PAD_LEFT);
            $model->like('deadline', $selected_month, 'left');
            //$model->where('payment_month', $selected_month);
        }
        $model->orderBy('id', 'DESC');
        $requirements = $model->findAll();

        $file_name = 'Requirements List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Requirements List";
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

        // column headers
        $sheet->setCellValue('A2', 'Class');
        $sheet->setCellValue('B2', 'Section');
        $sheet->setCellValue('C2', 'Description');
        $sheet->setCellValue('D2', 'Item');
        $sheet->setCellValue('E2', 'Deadline');

        $count = 3;
        foreach($requirements as $row)
        {
            $sheet->setCellValue('A' . $count, $row->class ? $row->class->name : 'All Classes');
            $sheet->setCellValue('B' . $count, $row->section ? $row->section->name : 'All Sections');
            $sheet->setCellValue('C' . $count, $row->description);
            $sheet->setCellValue('D' . $count,  $row->item);
            $sheet->setCellValue('E' . $count, $row->deadline);
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

    public function view($requirement, $class)
    {
        $class = (new Classes())->find($class);
        if(!$class) {
          return redirect()->to(previous_url())->with('error', "Class not found");
        }

        $requirement = (new \App\Models\Requirements())->find($requirement);
        if(!$requirement) {
            return redirect()->to(previous_url())->with('error', "Requirement entry not found");
        }

        $this->data['class'] = $class;
        $this->data['requirement'] = $requirement;
        $this->data['site_title'] = "Requirement for ".$class->name;
        return $this->_renderPage('Academic/Requirements/view', $this->data);
    }

    public function updateTeacherComment()
    {
        $student = (new Students())->find($this->request->getPost('student'));
        $model = new RequirementSubmission();
        $to_db = array(
            'teacher_comment' => $this->request->getPost('teacher_comment'),
            'teacher_check' => 1,
            'student' => $student->id,
            'class' => isset($student->class->id) ? $student->class->id : 0,
            'section' => isset($student->section->id) ? $student->section->id : 0,
            'session' => active_session(),
            'requirement' => $this->request->getPost('requirement')
        );

        $entry = (new RequirementSubmission())->where('student',$student->id)->where('session',active_session())->where('requirement',$this->request->getPost('requirement'))->get()->getRow();
        $db = \Config\Database::connect();
        $builder = $db->table('requirements_submissions');
        if (isset($entry->id)){
            $builder->where('requirement',$this->request->getPost('requirement'));
            $builder->where('student',$student->id);
            $builder->where('session',active_session());
            if ($builder->update($to_db)){
                $return = TRUE;
                $msg = "Entry updated successfully";
            }else {
                $return = FALSE;
                $msg = "An error occurred";
            }
        }else {
            if ($model->save($to_db)) {
                $return = TRUE;
                $msg = "Entry saved successfully";
            } else {
                $return = FALSE;
                $msg = "An error occurred";
            }
        }

        $s = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $s,
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());


    }
    public function save()
    {
        $data = $this->request->getPost();
        $entity = new Requirement();
        $entity->fill($data);
        $entity->session = active_session();
        $entity->class = $this->request->getPost('class') && is_numeric($this->request->getPost('class')) ? $this->request->getPost('class') : NULL;
        $entity->section = $this->request->getPost('section') && is_numeric($this->request->getPost('section')) ? $this->request->getPost('section') : NULL;
        $model = new \App\Models\Requirements();
        if ($model->save($entity)) {
            $return = TRUE;
            $msg = "Entry saved successfully";
        } else {
            $return = FALSE;
            $msg = "An error occurred";
        }

        $s = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $s,
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());
    }

    public function delete($id)
    {
        $model = new \App\Models\Requirements();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Requirement deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete payment";
        }

        $s = $return ? 'success' : 'error';
        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $s,
                'title' => $return ? 'Success' : 'Error',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        return $this->response->redirect(previous_url());
    }
}