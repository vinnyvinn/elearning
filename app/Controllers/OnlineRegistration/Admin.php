<?php


namespace App\Controllers\OnlineRegistration;


use App\Entities\File;
use App\Entities\Teacher;
use App\Models\Files;
use App\Models\Registrations;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Admin extends \App\Controllers\AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $model = new Registrations();

        $admins = $model->where('type', 'administration')->orderBy('id', 'DESC')->where("session",active_session())->findAll();

        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n Administration Recruitment List \n".getSession()->name;;
        $this->data['admins'] = $admins;

        return $this->_renderPageCustom('OnlineRegistration/Admin/index', $this->data);
    }

    public function print()
    {
        $this->data['teachers'] = (new Registrations())->where('type', 'administration')->where("session",active_session())->findAll();
        return view('OnlineRegistration/Admin/list/print', $this->data);
    }
    function exportExcel()
    {
        $teachers = (new Registrations())->where('type', 'administration')->where("session",active_session())->findAll();
        $file_name = 'Administration Recruitment List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n Administration Recruitment List \n".getSession()->name;
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

        // column headers
        $sheet->setCellValue('A2', 'Name');
        $sheet->setCellValue('B2', 'D.O.B');
        $sheet->setCellValue('C2', 'Contact');
        $sheet->setCellValue('D2', 'Application Date');

        $count = 3;
        foreach($teachers as $row)
        {
            $sheet->setCellValue('A' . $count, $row->name);
            $sheet->setCellValue('B' . $count, $row->dob);
            $sheet->setCellValue('C' . $count, $row->info->phone_number);
            $sheet->setCellValue('D' . $count, $row->created_at->format('d/m/Y h:i A'));
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
        $model = new Registrations();

        $admin = $model->where('type', 'administration')->find($id);

        if(!$admin) {
            return redirect()->to(previous_url())->with('error', "Registration entry not found");
        }

        $this->data['site_title'] = "Admin Recruitment";
        $this->data['admin'] = $admin;

        return $this->_renderPage('OnlineRegistration/Admin/viewAdmin', $this->data);
    }

    public function deleteAdmin($id)
    {
        $student = (new Registrations())->find($id);
        if($student) {
            //Delete files first
            @unlink(FCPATH.'uploads/avatars/'.$student->info->profile_pic);

            if((new Registrations())->delete($student->id)) {
                return redirect()->to(site_url(route_to('admin.registration.online.admin')))->with('success', "Deleted successfully");
            }
        }

        return redirect()->to(site_url(route_to('admin.registration.online.admin')))->with('error', "Entry not found");
    }

    public function pdf()
    {
        $model = new Registrations();

        $teachers = $model->where('type', 'administration')->orderBy('id', 'DESC')->where("session",active_session())->findAll();
        $this->data['teachers'] = $teachers;
        return view('OnlineRegistration/Admin/list/pdf',$this->data);
    }
}