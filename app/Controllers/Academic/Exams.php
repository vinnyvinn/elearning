<?php


namespace App\Controllers\Academic;


use App\Controllers\AdminController;
use App\Entities\ExamSchedule;
use App\Models\Classes;
use App\Models\Departure;
use App\Models\ExamResults;
use App\Models\Quarters;
use App\Models\Semesters;
use App\Models\Students;
use CodeIgniter\Exceptions\PageNotFoundException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Exams extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Exam List";
        $this->data['quarters'] = (new Quarters())->where('session', active_session())->orderBy('id', 'DESC')->findAll();
        $this->data['semesters'] = (new Semesters())->where('session', active_session())->orderBy('id', 'DESC')->findAll();
        if (getSession(active_session())->session_type > 0)
        return $this->_renderPage('Academic/Exams/index_quarters', $this->data);
        return $this->_renderPage('Academic/Exams/index', $this->data);
    }

    public function schedule()
    {
       return $this->_renderPage('Academic/Exams/schedule', $this->data);
    }

    public function print()
    {
       return view('Academic/Exams/list/print', $this->data);
    }

    public function pdf()
    {
       return view('Academic/Exams/list/pdf', $this->data);
    }

    function exportExcel()
    {
        $model = new \App\Models\Exams();
        $current_exams = $model->where('session', active_session())
            ->groupStart()
            ->where('class', NULL)
            ->orWhere('class', '')
            ->groupEnd()
            ->groupStart()
            ->where('section', NULL)
            ->orWhere('section', '')
            ->groupEnd()
            ->orderBy('id', 'DESC')->findAll();

        $file_name = 'Exam List.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Exam List";
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
        $sheet->getColumnDimension("C")->setWidth(30);
        $sheet->getColumnDimension("D")->setWidth(30);

        // column headers
        $sheet->setCellValue('A2', 'Name');
        $sheet->setCellValue('B2', 'Session');
        $sheet->setCellValue('C2', 'Starting Date');
        $sheet->setCellValue('D2', 'Ending Date');

        $count = 3;
        foreach($current_exams as $row)
        {
            $sheet->setCellValue('A' . $count, $row->name);
            $sheet->setCellValue('B' . $count, $row->session ? $row->session->name : '-');
            $sheet->setCellValue('C' . $count, $row->starting_date);
            $sheet->setCellValue('D' . $count, $row->ending_date);
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

    public function getSchedule()
    {
        if($this->request->getPost('save') == 'save'){
            $class = $this->request->getPost('class');
            $exam = $this->request->getPost('exam');
            $timetable = $this->request->getPost('timetable');
            $entries = [];
            //$timetable = $timetable[];
            foreach($timetable as $day=>$tt) {
                //Check if exists, if exists,
                $time = '';
                $subject = '';
                $entity = new ExamSchedule();

                $model = new \App\Models\ExamSchedule();
                foreach ($tt['subject'] as $t=>$s) {
                    $time = $t;
                    $subject = $s;

                    if(is_array($subject)) {
                        $clean_subject = [];
                        $clean_subject = array_filter($subject);

                        $subject = json_encode($clean_subject);
                    }

                    $entry = [
                        'day'   => $tt['day'],
                        'time'  => $time,
                        'subject'   => $subject,
                        'class' => $class,
                        'exam'  => $exam
                    ];

                    //$entity->fill($entry);
                    if(isset($tt['id'][$time]) && is_numeric($tt['id'][$time])) {
                        $entry['id'] = $tt['id'][$time];
                    }

                    $model->save($entry);
                }

            }

            $return = TRUE;
            $msg = "Timetable saved successfully";

            $status = $return ? 'success' : 'error';
            if($this->request->isAJAX()) {
                $resp = [
                    'status'    => $status,
                    'message'   => $msg,
                    'notifyType'    => 'toastr',
                    'title'     => $return ? 'Success' : 'Error',
                    'callback'  => '',
                ];
                return $this->response->setContentType('application/json')->setBody(json_encode($resp));
            }

            $this->session->setFlashData($status, $msg);
            return $this->response->redirect(current_url());
        }

        $exam = $this->request->getPost('exam');
        $class = $this->request->getPost('class');

        $exam = (new \App\Models\Exams())->find($exam);
        $class = (new Classes())->find($class);
        if(!$exam || !$class) {
            throw new PageNotFoundException();
        }
        $this->data['exam'] = $exam;
        $this->data['class'] = $class;

        return view('Academic/Exams/view_schedule', $this->data);
    }

    //Results
    public function results()
    {

//        $student1 = (new Students())->find(23259);
//        $student2 = (new Students())->find(23285);
//        $model = new Departure();
//        $std_model = new Students();
//        $to_db1 = array('session'=>$student1->session->id,'type'=>'transcript','student'=>23259);
//        $to_db2 = array('session'=>$student2->session->id,'type'=>'transcript','student'=>23285);
//        $model->save($to_db1);
//        $model->save($to_db2);

//        $to_db__1 = array('id'=>23259,'active'=>0);
//        $to_db__2 = array('id'=>23285,'active'=>0);
//        $std_model->save($to_db__1);
//        $std_model->save($to_db__2);

        $this->data['site_title'] = "Exam Results";
        return $this->_renderPage('Academic/Exams/Results/index', $this->data);
    }

    public function resultsPdf($exam,$class,$section)
    {
        $this->data['exam'] = $exam;
        $this->data['class'] = $class;
        $this->data['section'] = $section;
        return view('Academic/Exams/Results/pdf', $this->data);
    }
    public function resultsPrint($exam,$class,$section)
    {
        $this->data['exam'] = $exam;
        $this->data['class'] = $class;
        $this->data['section'] = $section;
        return view('Academic/Exams/Results/print', $this->data);
    }
    public function resultsTop3()
    {

        return $this->_renderPage('Academic/Exams/Results/topthree/index', $this->data);
    }
    public function resultsTop5()
    {

        return $this->_renderPage('Academic/Exams/Results/topfive/index', $this->data);
    }
    public function resultsTop10()
    {

        return $this->_renderPage('Academic/Exams/Results/topten/index', $this->data);
    }
    public function printStudentResults($exam, $student)
    {

        $student = (new \App\Models\Students())->find($student);

        $exam = (new \App\Models\Exams())->find($exam);

        $data = [
            'exam'      => $exam,
            'student'   => $student
        ];

        return view('Academic/Exams/Results/print_student', $data);
    }

    public function show($exam_id,$class_id)
    {
    //    $result = (new ExamResults())->orderBy('created_at','DESC')->where('exam',$exam_id)->where('class',$class_id)->get()->getRow();
        $data = [
            'class' => $class_id,
            'exam'  => $exam_id,
          //  'section'   => $section
        ];
        return $this->_renderPage('Academic/Exams/Results/view/results', $data);

    }

    public function getResults()
    {
       $class = $this->request->getPost('class');
       $exam = $this->request->getPost('exam');
       $section = $this->request->getPost('section');
        $data = [
            'class' => $class,
            'exam'  => $exam,
            'section'   => $section
        ];

       return view('Academic/Exams/Results/results', $data);

        $class = (new \App\Models\Classes())->find($class);
        $exam = (new \App\Models\Exams())->find($exam);
        $section = (new \App\Models\Sections())->find($section);
        $subjects = $class->subjects();
        $students = $section->students;

        $students_arr = array();

            foreach ($students as $student) {
                foreach ($subjects as $subject) {
                    $result = (new ExamResults())->select('SUM(mark) as subtotal')->where('student', $student->id)->where('class', $class->id)
                        ->where('subject', $subject->id)->where('exam', $exam->id)->get()->getRowObject();

                   // $big = $model->select('SUM(mark) as tt')->where(['exam' => $exam->id, 'student' => $student->id, 'class' => $class->id])->get()->getLastRow();
                    if (!empty($result)) {
                        if (!isset($students_arr[$student->id])) {
                            $students_arr[$student->id] = $result->subtotal;
                        } else {
                            $students_arr[$student->id] += $result->subtotal;
                        }
                    }
                }
            }
         $data['rank_students'] = array_rank($students_arr);
        return view('Academic/Exams/Results/results', $data);
    }

    public function getResultsTop3()
    {
        $class = $this->request->getPost('class');
        $exam = $this->request->getPost('exam');
        $section = $this->request->getPost('section');
        $data = [
            'class' => $class,
            'exam'  => $exam,
            'section'   => $section
        ];

        return view('Academic/Exams/Results/topthree/results', $data);


    }
    public function getResultsTop5()
    {
        $class = $this->request->getPost('class');
        $exam = $this->request->getPost('exam');
        $section = $this->request->getPost('section');
        $data = [
            'class' => $class,
            'exam'  => $exam,
            'section'   => $section
        ];

        return view('Academic/Exams/Results/topfive/results', $data);


    }
    public function getResultsTop10()
    {
        $class = $this->request->getPost('class');
        $exam = $this->request->getPost('exam');
        $section = $this->request->getPost('section');
        $data = [
            'class' => $class,
            'exam'  => $exam,
            'section'   => $section
        ];

        return view('Academic/Exams/Results/topten/results', $data);


    }

    public function printResultsTop3($exam,$class,$section)
    {
        $data = [
            'class' => $class,
            'exam'  => $exam,
            'section'   => $section
        ];
        return view('Academic/Exams/Results/topthree/print', $data);
    }
    public function downloadResultsTop3($exam,$class,$section)
    {
        $data = [
            'class' => $class,
            'exam'  => $exam,
            'section'   => $section
        ];
        return view('Academic/Exams/Results/topthree/pdf', $data);
    }
    public function downloadResultsTop5($exam,$class,$section)
    {
        $data = [
            'class' => $class,
            'exam'  => $exam,
            'section'   => $section
        ];
        return view('Academic/Exams/Results/topfive/pdf', $data);
    }
    public function downloadResultsTop10($exam,$class,$section)
    {
        $data = [
            'class' => $class,
            'exam'  => $exam,
            'section'   => $section
        ];
        return view('Academic/Exams/Results/topten/pdf', $data);
    }
    public function printResultsTop5($exam,$class,$section)
    {
        $data = [
            'class' => $class,
            'exam'  => $exam,
            'section'   => $section
        ];
        return view('Academic/Exams/Results/topfive/print', $data);
    }
    public function printResultsTop10($exam,$class,$section)
    {
        $data = [
            'class' => $class,
            'exam'  => $exam,
            'section'   => $section
        ];
        return view('Academic/Exams/Results/topten/print', $data);
    }

    public function saveTimeSlots()
    {
        $status = 'error';
        $return = FALSE;
        $msg = "An error occured";

        if($this->request->getPost()) {
            $slots = $this->request->getPost('slot');
            $to_db = [];
            foreach ($slots['time'] as $name=>$slot) {
                $xSlot = [];
                $to_db[] = [
                    'time'  => $slot,
                    'break' => false
                ];
            }
            $classId = $this->request->getPost('class');
            $examId = $this->request->getPost('exam');
            if(update_option('custom_exam_timetable_'.$examId.'_'.$classId, json_encode($to_db))) {
                $return = TRUE;
                $msg = "Timetable timeslots updaed successfully";
            }
        }

        $status = $return ? 'success' : 'error';
        if($this->request->isAJAX()) {
            $resp = [
                'status'    => $status,
                'message'   => $msg,
                'notifyType'    => 'toastr',
                'title'     => $return ? 'Success' : 'Error',
                'callback'  => $return ? 'getExamSchedule()' : '',
            ];

            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->session->setFlashData($status, $msg);
        return $this->response->redirect(previous_url());
    }

    public function editSchedule($class, $exam)
    {
        $class = (new Classes())->find($class);
        $exam = (new \App\Models\Exams())->find($exam);
        if(!$class || !$exam) {
            return redirect()->back()->with('error', "Undefined class or exam");
        }
        $this->data['class'] = $class;
        $this->data['exam'] = $exam;
        return $this->_renderPage('Academic/Exams/edit', $this->data);
    }

    public function printSchedule($class, $exam)
    {
        $class = (new Classes())->find($class);
        $exam = (new \App\Models\Exams())->find($exam);
        if(!$class || !$exam) {
            return redirect()->back()->with('error', "Undefined class or exam");
        }
        $this->data['class'] = $class;
        $this->data['exam'] = $exam;
        return view('Academic/Exams/print', $this->data);
    }

    public function pdfSchedule($class, $exam)
    {
        $class = (new Classes())->find($class);
        $exam = (new \App\Models\Exams())->find($exam);
        if(!$class || !$exam) {
            return redirect()->back()->with('error', "Undefined class or exam");
        }
        $this->data['class'] = $class;
        $this->data['exam'] = $exam;
        return view('Academic/Exams/pdf', $this->data);
    }
}