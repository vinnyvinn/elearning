<?php


namespace App\Controllers\Events;


use App\Controllers\AdminController;
use App\Entities\Event;
use App\Models\ClassGroups;
use App\Models\Sections;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Events extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        //Show events
        $month = $this->request->getGet('month');
        $month = isset($month) ? $month : date('m');

        $this->data['event_month'] = $month;
        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Event List";
        return $this->_renderPageCustom('Events/index', $this->data);
    }
    public function print()
    {
        $this->data['event_month'] = isset($month) ? $_GET['month'] : date('m');
        return view('Events/print', $this->data);
    }

    public function pdf()
    {
        $this->data['event_month'] = isset($month) ? $_GET['month'] : date('m');
        return view('Events/pdf', $this->data);
    }
    function exportExcel()
    {
        $event_month= isset($month) ? $_GET['month'] : date('m');
        $events = (new \App\Models\Events())
            ->like('starting_date', '-'.$event_month.'-', 'both')
            ->orLike('ending_date', '-'.$event_month.'-', 'both')
            ->orLike('starting_date', '/'.$event_month.'/', 'both')
            ->orLike('ending_date', '/'.$event_month.'/', 'both')
            ->where('session',active_session())
            ->orderBy('starting_date', 'ASC')->findAll();

        $file_name = getSession()->name.' Event List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $event_month = $event_month?getMonthName($event_month):'';
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Event List";
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
        $sheet->setCellValue('A2', 'Event');
        $sheet->setCellValue('B2', 'Start');
        $sheet->setCellValue('C2', 'End');
        $sheet->setCellValue('D2', 'Class');
        $sheet->setCellValue('E2', 'Section');

        $count = 3;
        foreach($events as $row)
        {
            $sheet->setCellValue('A' . $count, $row->name);
            $sheet->setCellValue('B' . $count, $row->starting_date->format('d/m/Y'));
            $sheet->setCellValue('C' . $count,  $row->ending_date ? $row->ending_date->format('d/m/Y') : '');
            $sheet->setCellValue('D' . $count, $row->class ? $row->class->name : '');
            $sheet->setCellValue('E' . $count, $row->section ? $row->section->name : '');
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

    public function calendar()
    {
        return $this->_renderPage('Events/calendar', $this->data);
    }

    public function create()
    {
        if($this->request->getPost()) {
            $entity = new Event();
            $model = new \App\Models\Events();
            $entity->fill($this->request->getPost());
            $entity->starting_date = date('d-m-Y', strtotime($entity->starting_date));
            $entity->ending_date = date('d-m-Y', strtotime($entity->ending_date));
            $entity->session = active_session();
            try {
                if ($model->save($entity)) {
                    $return = TRUE;
                    $msg = "Event created successully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to create event";
                }
            } catch (\ReflectionException $e) {
                $return = FALSE;
                $msg = $e->getMessage();
            }
        } else {
            $return = FALSE;
            $msg = "Invalid request";
        }

        $status = $return ? 'success' : 'error';
        $resp = [
            'title'     => $return ? 'Success' : 'Error',
            'message'   => $msg,
            'status'    => $status,
            'notifyType'    => 'swal',
            'showCancelButton' => false,
            'callback'  => 'window.location.reload()'
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($resp));
    }
    public function edit()
    {
        if($this->request->getPost()) {
            $to_db = array(
                'starting_date' => date('d-m-Y', strtotime($this->request->getPost('starting_date'))),
                'ending_date' => date('d-m-Y', strtotime($this->request->getPost('ending_date'))),
                'session' => active_session(),
                'className' => $this->request->getPost('className'),
                'name' => $this->request->getPost('name'),
                'class' => $this->request->getPost('class'),
                'section' => $this->request->getPost('section'),
            );

            try {
                $model = new \App\Models\Events();
                if ($this->request->getPost('id'))
                    $to_db['id'] = $this->request->getPost('id');
                if ($model->save($to_db)) {
                    $return = TRUE;
                    $msg = "Event updated successfully";
                } else {
                    $return = FALSE;
                    $msg = "Failed to update event";
                }
            } catch (\ReflectionException $e) {
                $return = FALSE;
                $msg = $e->getMessage();
            }
        } else {
            $return = FALSE;
            $msg = "Invalid request";
        }

        $status = $return ? 'success' : 'error';
        $resp = [
            'title'     => $return ? 'Success' : 'Error',
            'message'   => $msg,
            'status'    => $status,
            'notifyType'    => 'swal',
            'showCancelButton' => false,
            'callback'  => 'window.location.reload()'
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($resp));
    }
    public function delete($id)
    {
        $model = new \App\Models\Events();
        if($model->delete($id)) {
            $return = TRUE;
            $msg = "Event deleted successfully";
        } else {
            $return = FALSE;
            $msg = "Failed to delete event";
        }

        $status = $return ? 'success' : 'error';
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
    }
}