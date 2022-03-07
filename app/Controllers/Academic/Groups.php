<?php


namespace App\Controllers\Academic;


use App\Controllers\AdminController;
use App\Models\ClassGroups;
use App\Models\Sections;
use CodeIgniter\Exceptions\PageNotFoundException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Groups extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {

        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Group List";
       return $this->_renderPageCustom('Academic/Groups/index', $this->data);
    }

    public function getGroups()
    {
        $section = $this->request->getPost('section');
        $section = (new Sections())->find($section);

        if(!$section) {
            throw new PageNotFoundException("Section not found");
        }

        $data = [
            'section'   => $section
        ];

        return view('Academic/Groups/groups', $data);
    }

    function exportExcel($section)
    {
        $groups = (new ClassGroups())->where("section",$section)->findAll();
        $section = (new Sections())->find($section);
        $file_name = 'Group List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Group List \n".$section->class->name.' '.$section->name;
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
        $sheet->setCellValue('A2', 'Group Name');

        $count = 3;
        foreach($groups as $row)
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

    public function print($section)
    {
      $this->data['groups'] = (new ClassGroups())->where("section",$section)->findAll();
      $this->data['section'] = (new Sections())->find($section);
       return view('Academic/Groups/print', $this->data);
    }

    public function pdf($section)
    {
        $this->data['groups'] = (new ClassGroups())->where("section",$section)->findAll();
        $this->data['section'] = (new Sections())->find($section);
        return view('Academic/Groups/pdf', $this->data);
    }
}