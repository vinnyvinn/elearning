<?php


namespace App\Controllers\Admin;


use App\Controllers\AdminController;
use App\Entities\TransportRoute;
use App\Models\TransportRoutes;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Transport extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Transport Routes";;
        return $this->_renderPage('Transport/index', $this->data);
    }

    public function save()
    {
        if($this->request->getPost()) {
            $entity = new TransportRoute();
            $model = new TransportRoutes();
            $entity->fill($this->request->getPost());
            if($model->save($entity)) {
                $return = TRUE;
                $msg = "Routes updated successfully";
            } else {
                $return = FALSE;
                $msg = implode(', ', $model->errors());
            }
        } else {
            $msg = "Invalid request";
            $return = FALSE;
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'window.location.reload()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }

    public function delete($id)
    {
        $model = new TransportRoutes();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Route deleted successfully";

            \Config\Database::connect()->table('usersmeta')->where('meta_key', 'transport_route')->where('meta_value', $id)->delete();
        } else {
            $return = FALSE;
            $msg = "Failed to delete route";
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => 'window.location.reload()'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        \Config\Services::session()->setFlashData($status, $msg);
        return $this->response->redirect(site_url(previous_url()));
    }

    public function view($id)
    {
        $this->data['route'] = (new TransportRoutes())->find($id);
        return $this->_renderPage('Transport/view', $this->data);
    }

    public function transportPdf()
    {
     return view("Transport/pdf",$this->data);
    }
    public function transportPrint()
    {
     return view("Transport/print",$this->data);
    }
    function exportExcel()
    {
        $routes = (new \App\Models\TransportRoutes())->orderBy('id', 'DESC')->findAll();
        $file_name = 'Transport Routes.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Transport Routes";
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
        $sheet->setCellValue('A2', 'Driver');
        $sheet->setCellValue('B2', 'Phone');
        $sheet->setCellValue('C2', 'Vehicle');
        $sheet->setCellValue('D2', 'Route');
        $sheet->setCellValue('E2', 'Price');

        $count = 3;
        foreach($routes as $row)
        {
            $sheet->setCellValue('A' . $count, $row->driver_name);
            $sheet->setCellValue('B' . $count, $row->driver_phone);
            $sheet->setCellValue('C' . $count, $row->licence_plate);
            $sheet->setCellValue('D' . $count, $row->route);
            $sheet->setCellValue('E' . $count, fee_currency($row->price));
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
    public function transportViewPdf($id)
    {
     $this->data['route'] = (new TransportRoutes())->find($id) ;
     return view("Transport/view_pdf",$this->data);
    }
}