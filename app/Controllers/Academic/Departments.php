<?php


namespace App\Controllers\Academic;


use App\Controllers\AdminController;
use App\Entities\Department;
use App\Entities\SubjectDepartment;
use App\Models\SubjectDepartments;
use CodeIgniter\Exceptions\PageNotFoundException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Departments extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
     $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Department List";
    return $this->_renderPageCustom('Academic/Departments/index', $this->data);
    }

    function exportExcel()
    {
        $departments = getSession()->departments;

        $file_name = 'Department List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Department List";
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
        $sheet->getColumnDimension("B")->setWidth(30);

        // column headers
        $sheet->setCellValue('A2', 'Name');
        $sheet->setCellValue('B2', 'Head');

        $count = 3;
        foreach($departments as $row)
        {
            $sheet->setCellValue('A' . $count, $row->name);
            $sheet->setCellValue('B' . $count, $row->head->profile->name);
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
    public function getSubjects()
    {
        $dept = $this->request->getPost('department');
        $dept = (new \App\Models\Departments())->find($dept);

        if(!$dept) throw new PageNotFoundException("Department not found");

        $data = [
            'dept'  => $dept
        ];

        return view('Academic/Departments/subjects', $data);
    }


    public function manageSubjects()
    {
        $dept = $this->request->getPost('dept');
        $subjects = $this->request->getPost('subjects');

        $dept = (new \App\Models\Departments())->find($dept);
        if(!$dept) throw new PageNotFoundException("Department not found");

        $entity = new SubjectDepartment();
        $model = new SubjectDepartments();

        foreach ($subjects as $subject) {
            $data = [
                'dept_id'   => $dept->id,
                'subject'   => $subject
            ];
            $entity->fill($data);
            $model->save($entity);
        }

        $return = TRUE;
        $msg = "Department Subjects updated successfully";

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Departments',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'getSubjects('.$dept->id.')' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(current_url())->withInput();
        }
    }

    public function removeSubject($id)
    {
        $model = new SubjectDepartments();
        $es = $model->find($id);
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Department Subjects removed successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to remove subject";
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Departments',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'getSubjects('.$es->dept_id.')' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(current_url())->withInput();
        }
    }

    public function create()
    {
        $entity = new Department();
        $entity->fill($this->request->getPost());

        $model = new \App\Models\Departments();
        if($model->save($entity)) {
            $return = TRUE;
            $msg = "Departments updated successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to update departments";
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Departments',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(current_url())->withInput();
        }
    }

    public function delete($id)
    {
        $model = new \App\Models\Departments();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Department deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete the department";
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => 'Departments',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : ''
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
            return $this->response->redirect(current_url())->withInput();
        }
    }

    public function print()
    {
        return view('Academic/Departments/print', $this->data);
    }

    public function pdf()
    {
        return view('Academic/Departments/pdf', $this->data);
    }
}