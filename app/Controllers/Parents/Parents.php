<?php


namespace App\Controllers\Parents;


use App\Controllers\AdminController;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Parents extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Parents List";
       return $this->_renderPageCustom('Admin/Parents/index', $this->data);
    }

    public function pdf()
    {
        $this->data['parents'] = (new \App\Models\Parents())->getParents_();
        return view('Admin/Parents/list/pdf', $this->data);
    }
    public function print()
    {
        $this->data['parents'] = (new \App\Models\Parents())->getParents_();
        return view('Admin/Parents/list/print', $this->data);
    }
    function exportExcel()
    {
        $parents = (new \App\Models\Parents())->getParents_();

        $file_name = 'Parents List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Parents List";
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
        $sheet->setCellValue('B2', '# of Students');
        $sheet->setCellValue('C2', 'Phone');

        $count = 3;
        foreach($parents as $row)
        {
            $pa = (new \App\Models\Parents())->find($row->id);
            $sheet->setCellValue('A' . $count, $row->name);
            $sheet->setCellValue('B' . $count, count($pa->students));
            $sheet->setCellValue('C' . $count, $row->phone);
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
        $parent = (new User())->find($id);
        if(!$parent) return redirect()->back();
        $this->data['parent'] = $parent;
        return $this->_renderPage('Admin/Parents/view', $this->data);
    }

    public function delete($id)
    {
        //Delete Parent
        $model = new \App\Models\Parents();
        $parent = $model->find($id);


        $return = FALSE;
        $msg = "Failed to delete parent";
        if (!empty($parent->students)){
            $msg = "This Parent has a student registered Can’t Delete";
        }else {
            if($model->delete($id)) {
                //if(true) {
                $return = TRUE;
                $msg = "Parent deleted successfully";
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
                'callback'  => $return ? 'window.location = "'.site_url(route_to('admin.parents.index')).'"' : ''
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        } else {
            $this->session->setFlashData($status, $msg);
            return $this->response->redirect($return ? site_url(route_to('admin.parents.index')) : current_url());
        }
    }
}