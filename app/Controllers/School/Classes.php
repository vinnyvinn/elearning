<?php


namespace App\Controllers\School;


use App\Controllers\AdminController;
use App\Models\Registrations;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Classes extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $model = new \App\Models\Classes();
        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n Online Registration - New Students \n".getSession()->name;
        $this->data['classes'] = $model->where('session', active_session())->orderBy('id', 'DESC')->findAll();
        return $this->_renderPageCustom('Admin/Classes/index', $this->data);
    }

    public function create() {
        $model = new \App\Models\Classes();
        $entity = new \App\Entities\Classes();
        $record = (new \App\Models\Classes())->where('weight',$this->request->getPost('weight'))->findAll();

        if (!empty($record)){
            $return = false;
            $msg = "Class Number must be unique";
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => $return ? 'Success' : 'Error!',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : false
            ];
          return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $requests = $this->request->getPost();
        $requests['type'] = $this->request->getPost('type') == 1 ? 'kg' : 'grade';
        if ($this->request->getPost('type') == 1){
            $grading = array();
            foreach ($_POST['grade'] as $k => $g) {
                foreach ($_POST['scale'] as $s => $sk) {
                    if ($k == $s) {
                        if ($g == '') {
                            $return = FALSE;
                            $msg = "Grade field cannot be empty";
                        }
                        $num_arr = explode("-", $sk);
                        if (count($num_arr) != 2) {
                            $return = FALSE;
                            $msg = "The first and the second values in the scale must be numeric in the format '90-100'";
                        }
                        if (!is_numeric($num_arr[0]) || !is_numeric($num_arr[1])) {
                            $return = FALSE;
                            $msg = "The first and the second values in the scale must be numeric in the format '90-100'";
                        }
                        array_push($grading, array('grade' => $g, 'scale' => $sk));
                    }
                }
                if ($return == FALSE){
                    $resp = [
                        'status' => $return ? 'success' : 'error',
                        'title' => $return ? 'Success' : 'Error!',
                        'message' => $msg,
                        'notifyType' => 'toastr',
                        'callback' => $return ? 'window.location.reload()' : false
                    ];
                    return $this->response->setContentType('application/json')->setBody(json_encode($resp));
                }
            }
            $requests['grading'] = json_encode($grading);
        }

        if($model->save($requests)) {
            $id = $model->getInsertID();
            do_action('new_class', $id);
            $return = true;
            $msg = "Class added successfully";
        } else {
            $return = false;
            $msg = implode('<br/>', $model->errors());
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => $return ? 'Success' : 'Error!',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : false
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
        }

        return $this->response->redirect(site_url(route_to('admin.school.classes.index')));
    }

    public function edit($id) {
        $model = new \App\Models\Classes();
        $entity = new \App\Entities\Classes();
        $return = TRUE;
        $requests = $this->request->getPost();
        $requests['type'] = $this->request->getPost('type') == 1 ? 'kg' : 'grade';
        if ($this->request->getPost('type') == 1){
            $grading = array();
            foreach ($_POST['grade'] as $k => $g) {
                foreach ($_POST['scale'] as $s => $sk) {
                    if ($k == $s) {
                        if ($g == '') {
                            $return = FALSE;
                            $msg = "Grade field cannot be empty";
                        }
                        $num_arr = explode("-", $sk);
                        if (count($num_arr) != 2) {
                            $return = FALSE;
                            $msg = "The first and the second values in the scale must be numeric in the format '90-100'";
                        }
                        if (!is_numeric($num_arr[0]) || !is_numeric($num_arr[1])) {
                            $return = FALSE;
                            $msg = "The first and the second values in the scale must be numeric in the format '90-100'";
                        }
                        array_push($grading, array('grade' => $g, 'scale' => $sk));
                    }
                }
                if ($return == FALSE){
                    $resp = [
                        'status' => $return ? 'success' : 'error',
                        'title' => $return ? 'Success' : 'Error!',
                        'message' => $msg,
                        'notifyType' => 'toastr',
                        'callback' => $return ? 'window.location.reload()' : false
                    ];
                    return $this->response->setContentType('application/json')->setBody(json_encode($resp));
                }
            }
            $requests['grading'] = json_encode($grading);
        }
        $requests['id'] = $id;
        if($model->save($requests)) {
            do_action('new_class', $id);
            $return = true;
            $msg = "Class updated successfully";
        } else {
            $return = false;
            $msg = implode('<br/>', $model->errors());
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => $return ? 'Success' : 'Error!',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : false
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $t = $return ? 'success' : 'error';
            $this->session->setFlashData($t, $msg);
        }

        return $this->response->redirect(site_url(route_to('admin.school.classes.index')));
    }

    public function delete($id)
    {
        $model = new \App\Models\Classes();
        if ($model->delete($id)) {
            do_action('class_deleted', $id);
            $return = true;
            $msg = "Class deleted successfully";
        } else {
            $return = false;
            $msg = "Failed to delete class";
        }

        if ($this->request->isAJAX()) {
            $resp = [
                'status' => $return ? 'success' : 'error',
                'title' => $return ? 'Success' : 'Error!',
                'message' => $msg,
                'notifyType' => 'toastr',
                'callback' => $return ? 'window.location.reload()' : false
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $t = $return ? 'success' : 'error';
        $this->session->setFlashData($t, $msg);

        return $this->response->redirect(site_url(route_to('admin.school.classes.index')))->withInput();
    }

    public function print()
    {
        $this->data['classes'] =  (new \App\Models\Classes())->where('session',active_session())->findAll();
        return view('Admin/Classes/list/print', $this->data);
    }

    public function pdf()
    {
        $this->data['classes'] =  (new \App\Models\Classes())->where('session',active_session())->findAll();
        return view('Admin/Classes/list/pdf', $this->data);
    }

    function exportExcel()
    {
        $classes = (new \App\Models\Classes())->where('session',active_session())->findAll();

        $file_name = 'Class List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Class & Section List";
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

        // column headers
        $sheet->setCellValue('A2', 'Name');

        $count = 3;
        foreach($classes as $row)
        {
            $sheet->setCellValue('A' . $count, $row->name);
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
}