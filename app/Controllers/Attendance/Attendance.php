<?php


namespace App\Controllers\Attendance;


use App\Controllers\AdminController;
use App\Entities\Student;
use App\Models\Classes;
use App\Models\ClassGroups;
use App\Models\Sections;
use App\Models\Students;
use App\Models\Teachers;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Attendance extends AdminController
{
    public function students()
    {
     $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Students Attendance List";
     return $this->_renderPage('Attendance/students', $this->data);
    }
    public function studentsMonthly()
    {
        $this->data['site_title'] = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Monthly Students Attendance Counter";
     return $this->_renderPageCustom('Attendance/monthly_students_attendance', $this->data);
    }

    public function recordStudents()
    {

        $this->data['attend'] = (new \App\Models\Attendance())->where('timestamp',strtotime(date('m/d/Y')))->orderBy('date_created','DESC')->where('option_type !=','normal')->where('student !=',null)->where('student !=','')->get()->getRowObject();

        return $this->_renderPage('Attendance/students_attendance', $this->data);
    }

    public function getAttendanceAjax()
    {
        $time = $this->request->getPost('date');
        $timestamp = strtotime($time);
        $attendance = (new \App\Models\Attendance())->where('timestamp',$timestamp)->orderBy('date_created','DESC')->where('option_type !=','normal')->where('student !=',null)->where('student !=','')->get()->getRowObject();
        return json_encode($attendance);
    }

    public function getTeacherAttendanceAjax()
    {
        $time = $this->request->getPost('date');
        $timestamp = strtotime($time);
        $attendance = (new \App\Models\Attendance())->where('timestamp',$timestamp)->orderBy('date_created','DESC')->where('option_type !=','normal')->where('teacher !=',null)->where('teacher !=','')->get()->getRowObject();
        return json_encode($attendance);
    }
    public function recordTeachers()
    {
        $this->data['attend'] = (new \App\Models\Attendance())->where('timestamp',strtotime(date('m/d/Y')))->orderBy('date_created','DESC')->where('option_type !=','normal')->where('teacher !=',null)->where('teacher !=','')->get()->getRowObject();
        return $this->_renderPage('Attendance/teachers_attendance', $this->data);
    }

    public function teachers()
    {
        $this->data['site_title'] = "Teachers Attendance";
        return $this->_renderPage('Attendance/teachers', $this->data);
    }
    public function teachersMonthly()
    {
        return $this->_renderPage('Attendance/teachers_monthly', $this->data);
    }

    public function show($id)
    {
       $attendance = (new \App\Models\Attendance())->find($id);

        $this->data['year'] = date('Y',$attendance['timestamp']);
        $this->data['month'] = date('m',$attendance['timestamp']);
       if ($attendance['student']){
           $this->data['section']  = (new Students())->find($attendance['student'])->section;
           return $this->_renderPage('Attendance/view/att_students', $this->data);
       }
        $db = \Config\Database::connect();
        $builder = $db->table('teachers');
        $builder->select("users.surname,users.first_name,users.last_name,teachers.teacher_number,teachers.id as trID");
        $builder->join('users','users.id = teachers.user_id');
        $builder->orderBy('users.surname, users.first_name,users.last_name');
        $builder->where('session',active_session());
        $teachers = $builder->get()->getResult();
        $this->data['teachers'] = $teachers;
        return $this->_renderPage('Attendance/view/att_teachers', $this->data);
    }
    public function getStudents($class, $section, $month, $year)
    {
        $this->data['month'] = $month;
        $this->data['year'] = $year;
        $section = (new Sections())->find($section);

        if(!$section) {
            $resp = [
                'title' => 'Error',
                'message'   => 'Selected section does not exist',
                'status'    => 'error'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->data['section'] = $section;
        //$this->data['attendance'] = (new \App\Models\Attendance())->where('class', $class)->where('section', $section);

        if($this->request->getPost('record')) {
            return view('Attendance/rec_students', $this->data);
        } else {
            return view('Attendance/att_students', $this->data);
        }
    }
    public function getStudentsMonthly($class, $section, $month, $year)
    {
        $this->data['month'] = $month;
        $this->data['year'] = $year;
        $this->data['class'] = $class;
        $section = (new Sections())->find($section);

        if(!$section) {
            $resp = [
                'title' => 'Error',
                'message'   => 'Selected section does not exist',
                'status'    => 'error'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->data['section'] = $section;
        $last_day = cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $from = strtotime($month.'/'.'01/'.$year);
        $to = strtotime($month.'/'.$last_day.'/'.$year);

        $att = (new \App\Models\Attendance())->where('session',active_session())
            ->where('timestamp >=',$from)->where('timestamp <=',$to)->where('class', $class)->where('section', $section->id)->findAll();

      $students = array();

       foreach ($att as $item){
           if (isset($students[$item['timestamp']])){
               $students[$item['timestamp']] = [
                 'absent' => $item['status'] == 0 ? $students[$item['timestamp']]['absent'] +=1 : $students[$item['timestamp']]['absent'],
                 'permission' => $item['status'] == 2 ? $students[$item['timestamp']]['permission'] +=1 : $students[$item['timestamp']]['permission'],
                 'sick' => $item['status'] == 3 ? $students[$item['timestamp']]['sick'] +=1 : $students[$item['timestamp']]['sick'],
                 'late' => $item['status'] == 4 ? $students[$item['timestamp']]['late'] +=1 : $students[$item['timestamp']]['late'],
                 'students' => $students[$item['timestamp']]['students'] +=1,
                 'date' => date('d/m/Y',$item['timestamp'])
               ];
           }else {
               $students[$item['timestamp']] = [
                   'absent' => $item['status'] == 0 ? 1 : 0,
                   'permission' => $item['status'] == 2 ? 1 : 0,
                   'sick' => $item['status'] == 3 ? 1 : 0,
                   'late' => $item['status'] == 4 ? 1 : 0,
                   'students' => 1,
                   'date' => date('d/m/Y',$item['timestamp'])
               ];
           }
       }

        $this->data['attendance'] = $students;

        if($this->request->getPost('record')) {
            return view('Attendance/rec_students', $this->data);
        } else {
            return view('Attendance/att_students_monthly', $this->data);
        }
    }

    public function studentsMonthlyPdf($class, $section, $month, $year)
    {
        $this->data['month'] = $month;
        $this->data['year'] = $year;
        $this->data['class'] = $class;
        $section = (new Sections())->find($section);

        if(!$section) {
            $resp = [
                'title' => 'Error',
                'message'   => 'Selected section does not exist',
                'status'    => 'error'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->data['section'] = $section;
        $last_day = cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $from = strtotime($month.'/'.'01/'.$year);
        $to = strtotime($month.'/'.$last_day.'/'.$year);

        $att = (new \App\Models\Attendance())->where('session',active_session())
            ->where('timestamp >=',$from)->where('timestamp <=',$to)->where('class', $class)->where('section', $section->id)->findAll();

        $students = array();

        foreach ($att as $item){
            if (isset($students[$item['timestamp']])){
                $students[$item['timestamp']] = [
                    'absent' => $item['status'] == 0 ? $students[$item['timestamp']]['absent'] +=1 : $students[$item['timestamp']]['absent'],
                    'permission' => $item['status'] == 2 ? $students[$item['timestamp']]['permission'] +=1 : $students[$item['timestamp']]['permission'],
                    'sick' => $item['status'] == 3 ? $students[$item['timestamp']]['sick'] +=1 : $students[$item['timestamp']]['sick'],
                    'late' => $item['status'] == 4 ? $students[$item['timestamp']]['late'] +=1 : $students[$item['timestamp']]['late'],
                    'students' => $students[$item['timestamp']]['students'] +=1,
                    'date' => date('d/m/Y',$item['timestamp'])
                ];
            }else {
                $students[$item['timestamp']] = [
                    'absent' => $item['status'] == 0 ? 1 : 0,
                    'permission' => $item['status'] == 2 ? 1 : 0,
                    'sick' => $item['status'] == 3 ? 1 : 0,
                    'late' => $item['status'] == 4 ? 1 : 0,
                    'students' => 1,
                    'date' => date('d/m/Y',$item['timestamp'])
                ];
            }
        }

        $this->data['attendance'] = $students;
         return view('Attendance/listStudents/monthly/pdf', $this->data);

    }
    public function studentsMonthlyPrint($class, $section, $month, $year)
    {
        $this->data['month'] = $month;
        $this->data['year'] = $year;
        $section = (new Sections())->find($section);

        if(!$section) {
            $resp = [
                'title' => 'Error',
                'message'   => 'Selected section does not exist',
                'status'    => 'error'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }

        $this->data['section'] = $section;
        $last_day = cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $from = strtotime($month.'/'.'01/'.$year);
        $to = strtotime($month.'/'.$last_day.'/'.$year);

        $att = (new \App\Models\Attendance())->where('session',active_session())
            ->where('timestamp >=',$from)->where('timestamp <=',$to)->where('class', $class)->where('section', $section->id)->findAll();

        $students = array();

        foreach ($att as $item){
            if (isset($students[$item['timestamp']])){
                $students[$item['timestamp']] = [
                    'absent' => $item['status'] == 0 ? $students[$item['timestamp']]['absent'] +=1 : $students[$item['timestamp']]['absent'],
                    'permission' => $item['status'] == 2 ? $students[$item['timestamp']]['permission'] +=1 : $students[$item['timestamp']]['permission'],
                    'sick' => $item['status'] == 3 ? $students[$item['timestamp']]['sick'] +=1 : $students[$item['timestamp']]['sick'],
                    'late' => $item['status'] == 4 ? $students[$item['timestamp']]['late'] +=1 : $students[$item['timestamp']]['late'],
                    'students' => $students[$item['timestamp']]['students'] +=1,
                    'date' => date('d/m/Y',$item['timestamp'])
                ];
            }else {
                $students[$item['timestamp']] = [
                    'absent' => $item['status'] == 0 ? 1 : 0,
                    'permission' => $item['status'] == 2 ? 1 : 0,
                    'sick' => $item['status'] == 3 ? 1 : 0,
                    'late' => $item['status'] == 4 ? 1 : 0,
                    'students' => 1,
                    'date' => date('d/m/Y',$item['timestamp'])
                ];
            }
        }

        $this->data['attendance'] = $students;
        return view('Attendance/listStudents/monthly/print', $this->data);
    }

    public function teachersMonthlyPdf($month, $year)
    {
        $this->data['month'] = $month;
        $this->data['year'] = $year;

        $last_day = cal_days_in_month(CAL_GREGORIAN, $this->data['month'],$year);
        $from = strtotime( $this->data['month'].'/'.'01/'.$year);
        $to = strtotime( $this->data['month'].'/'.$last_day.'/'.$year);

        // $where = "teacher is  NOT NULL";
        $att = (new \App\Models\Attendance())->where('session',active_session())
            ->where('timestamp >=',$from)->where('timestamp <=',$to)->where('teacher >',1)->findAll();

        $teachers = array();
        foreach ($att as $item) {
            if (isset($teachers[$item['timestamp']])) {
                $teachers[$item['timestamp']] = [
                    'absent' => $item['status'] == 0 ? $teachers[$item['timestamp']]['absent'] +=1  : $teachers[$item['timestamp']]['absent'],
                    'permission' => $item['status'] == 2 ? $teachers[$item['timestamp']]['permission'] +=1 : $teachers[$item['timestamp']]['permission'],
                    'sick' => $item['status'] == 3 ? $teachers[$item['timestamp']]['sick'] +=1 : $teachers[$item['timestamp']]['sick'],
                    'late' => $item['status'] == 4 ? $teachers[$item['timestamp']]['late'] +=1 : $teachers[$item['timestamp']]['late'],
                    'teachers' => $teachers[$item['timestamp']]['teachers'] +=1,
                    'date' => date('d/m/Y', $item['timestamp'])
                ];
            } else {
                $teachers[$item['timestamp']] = [
                    'absent' => $item['status'] == 0 ? 1 : 0,
                    'permission' => $item['status'] == 2 ? 1 : 0,
                    'sick' => $item['status'] == 3 ? 1 : 0,
                    'late' => $item['status'] == 4 ? 1 : 0,
                    'teachers' => 1,
                    'date' => date('d/m/Y', $item['timestamp'])
                ];
            }
        }
        $this->data['attendance'] = $teachers;

        return view('Attendance/listTeachers/monthly/pdf', $this->data);

    }

    public function teachersMonthlyPrint($month, $year)
    {
        $this->data['month'] = $month;
        $this->data['year'] = $year;

        $last_day = cal_days_in_month(CAL_GREGORIAN, $this->data['month'],$year);
        $from = strtotime( $this->data['month'].'/'.'01/'.$year);
        $to = strtotime( $this->data['month'].'/'.$last_day.'/'.$year);

        // $where = "teacher is  NOT NULL";
        $att = (new \App\Models\Attendance())->where('session',active_session())
            ->where('timestamp >=',$from)->where('timestamp <=',$to)->where('teacher >',1)->findAll();

        $teachers = array();
        foreach ($att as $item) {
            if (isset($teachers[$item['timestamp']])) {
                $teachers[$item['timestamp']] = [
                    'absent' => $item['status'] == 0 ? $teachers[$item['timestamp']]['absent'] +=1  : $teachers[$item['timestamp']]['absent'],
                    'permission' => $item['status'] == 2 ? $teachers[$item['timestamp']]['permission'] +=1 : $teachers[$item['timestamp']]['permission'],
                    'sick' => $item['status'] == 3 ? $teachers[$item['timestamp']]['sick'] +=1 : $teachers[$item['timestamp']]['sick'],
                    'late' => $item['status'] == 4 ? $teachers[$item['timestamp']]['late'] +=1 : $teachers[$item['timestamp']]['late'],
                    'teachers' => $teachers[$item['timestamp']]['teachers'] +=1,
                    'date' => date('d/m/Y', $item['timestamp'])
                ];
            } else {
                $teachers[$item['timestamp']] = [
                    'absent' => $item['status'] == 0 ? 1 : 0,
                    'permission' => $item['status'] == 2 ? 1 : 0,
                    'sick' => $item['status'] == 3 ? 1 : 0,
                    'late' => $item['status'] == 4 ? 1 : 0,
                    'teachers' => 1,
                    'date' => date('d/m/Y', $item['timestamp'])
                ];
            }
        }
        $this->data['attendance'] = $teachers;

        return view('Attendance/listTeachers/monthly/print', $this->data);
    }
    function exportExcelStudentsMonthly($class, $section, $month, $year)
    {
        $section = (new Sections())->find($section);
        $this->data['section'] = $section;
        $last_day = cal_days_in_month(CAL_GREGORIAN,$month,$year);
        $from = strtotime($month.'/'.'01/'.$year);
        $to = strtotime($month.'/'.$last_day.'/'.$year);

        $att = (new \App\Models\Attendance())->where('session',active_session())
            ->where('timestamp >=',$from)->where('timestamp <=',$to)->where('class', $class)->where('section', $section->id)->findAll();

        $students = array();

        foreach ($att as $item){
            if (isset($students[$item['timestamp']])){
                $students[$item['timestamp']] = [
                    'absent' => $item['status'] == 0 ? $students[$item['timestamp']]['absent'] +=1 : $students[$item['timestamp']]['absent'],
                    'permission' => $item['status'] == 2 ? $students[$item['timestamp']]['permission'] +=1 : $students[$item['timestamp']]['permission'],
                    'sick' => $item['status'] == 3 ? $students[$item['timestamp']]['sick'] +=1 : $students[$item['timestamp']]['sick'],
                    'late' => $item['status'] == 4 ? $students[$item['timestamp']]['late'] +=1 : $students[$item['timestamp']]['late'],
                    'students' => $students[$item['timestamp']]['students'] +=1,
                    'date' => date('d/m/Y',$item['timestamp'])
                ];
            }else {
                $students[$item['timestamp']] = [
                    'absent' => $item['status'] == 0 ? 1 : 0,
                    'permission' => $item['status'] == 2 ? 1 : 0,
                    'sick' => $item['status'] == 3 ? 1 : 0,
                    'late' => $item['status'] == 4 ? 1 : 0,
                    'students' => 1,
                    'date' => date('d/m/Y',$item['timestamp'])
                ];
            }
        }

        $attendance = $students;
        $file_name = 'Students Monthly Counter.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Students Monthly Counter\n".getMonthName($month);
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
        $sheet->getColumnDimension("E")->setWidth(30);
        $sheet->getColumnDimension("F")->setWidth(30);

         // column headers
        $sheet->setCellValue('A2', 'Date');
        $sheet->setCellValue('B2', 'No. of Present Students');
        $sheet->setCellValue('C2', 'No. of Absent Students');
        $sheet->setCellValue('D2', 'No. of Permission Students');
        $sheet->setCellValue('E2', 'No. of Sick Students');
        $sheet->setCellValue('F2', 'No. of Late Students');

        $count = 3;
        foreach($attendance as $row)
        {
            $sheet->setCellValue('A' . $count, $row['date']);
            $sheet->setCellValue('B' . $count,  $row['students']-$row['absent']-$row['permission']-$row['sick']-$row['late'] .'/'.$row['students']);
            $sheet->setCellValue('C' . $count, $row['absent'] .'/'.$row['students']);
            $sheet->setCellValue('D' . $count, $row['permission'] .'/'.$row['students']);
            $sheet->setCellValue('E' . $count, $row['sick'] .'/'.$row['students']);
            $sheet->setCellValue('F' . $count, $row['late'] .'/'.$row['students']);
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

    function exportExcelTeachersMonthly($month, $year)
    {
        $this->data['month'] = $month;
        $this->data['year'] = $year;

        $last_day = cal_days_in_month(CAL_GREGORIAN, $this->data['month'],$year);
        $from = strtotime( $this->data['month'].'/'.'01/'.$year);
        $to = strtotime( $this->data['month'].'/'.$last_day.'/'.$year);

        // $where = "teacher is  NOT NULL";
        $att = (new \App\Models\Attendance())->where('session',active_session())
            ->where('timestamp >=',$from)->where('timestamp <=',$to)->where('teacher >',1)->findAll();

        $teachers = array();
        foreach ($att as $item) {
            if (isset($teachers[$item['timestamp']])) {
                $teachers[$item['timestamp']] = [
                    'absent' => $item['status'] == 0 ? $teachers[$item['timestamp']]['absent'] +=1  : $teachers[$item['timestamp']]['absent'],
                    'permission' => $item['status'] == 2 ? $teachers[$item['timestamp']]['permission'] +=1 : $teachers[$item['timestamp']]['permission'],
                    'sick' => $item['status'] == 3 ? $teachers[$item['timestamp']]['sick'] +=1 : $teachers[$item['timestamp']]['sick'],
                    'late' => $item['status'] == 4 ? $teachers[$item['timestamp']]['late'] +=1 : $teachers[$item['timestamp']]['late'],
                    'teachers' => $teachers[$item['timestamp']]['teachers'] +=1,
                    'date' => date('d/m/Y', $item['timestamp'])
                ];
            } else {
                $teachers[$item['timestamp']] = [
                    'absent' => $item['status'] == 0 ? 1 : 0,
                    'permission' => $item['status'] == 2 ? 1 : 0,
                    'sick' => $item['status'] == 3 ? 1 : 0,
                    'late' => $item['status'] == 4 ? 1 : 0,
                    'teachers' => 1,
                    'date' => date('d/m/Y', $item['timestamp'])
                ];
            }
        }

        $attendance = $teachers;
        $file_name = 'Teachers Monthly Counter.xlsx';
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $title = get_option("id_school_name")."\n".get_option("website_location")."\n".getSession()->name."\n Teachers Monthly Counter\n".getMonthName($month);
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
        $sheet->getColumnDimension("E")->setWidth(30);
        $sheet->getColumnDimension("F")->setWidth(30);

        // column headers
        $sheet->setCellValue('A2', 'Date');
        $sheet->setCellValue('B2', 'No. of Present Teachers');
        $sheet->setCellValue('C2', 'No. of Absent Teachers');
        $sheet->setCellValue('D2', 'No. of Permission Teachers');
        $sheet->setCellValue('E2', 'No. of Sick Teachers');
        $sheet->setCellValue('F2', 'No. of Late Teachers');

        $count = 3;
        foreach($attendance as $row)
        {
            $sheet->setCellValue('A' . $count, $row['date']);
            $sheet->setCellValue('B' . $count,  $row['teachers']-$row['absent']-$row['permission']-$row['sick']-$row['late'] .'/'.$row['teachers']);
            $sheet->setCellValue('C' . $count, $row['absent'] .'/'.$row['teachers']);
            $sheet->setCellValue('D' . $count, $row['permission'] .'/'.$row['teachers']);
            $sheet->setCellValue('E' . $count, $row['sick'] .'/'.$row['teachers']);
            $sheet->setCellValue('F' . $count, $row['late'] .'/'.$row['teachers']);
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

    public function getTeachers()
    {
        $this->data['month'] = $this->request->getPost('month');
        $this->data['year'] = $this->request->getPost('year');

        $db = \Config\Database::connect();
        $builder = $db->table('teachers');
        $builder->select("users.surname,users.first_name,users.last_name,teachers.teacher_number,teachers.id as trID");
        $builder->join('users','users.id = teachers.user_id');
        $builder->orderBy('users.surname, users.first_name,users.last_name');
        $builder->where("teachers.session",active_session());
        $teachers = $builder->get()->getResult();
        if(!$teachers) {
            $resp = [
                'title' => 'Error',
                'message'   => 'No teachers have been registered yet',
                'status'    => 'error'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        $this->data['teachers'] = $teachers;
        //$this->data['attendance'] = (new \App\Models\Attendance())->where('class', $class)->where('section', $section);

        //return view('Attendance/rec_teachers', $this->data);
        return view('Attendance/att_teachers', $this->data);
    }

    public function getTeachersMonthly()
    {
        $this->data['month'] = $this->request->getPost('month');
        $this->data['year'] = $this->request->getPost('year');


        $last_day = cal_days_in_month(CAL_GREGORIAN, $this->data['month'],$this->request->getPost('year'));
        $from = strtotime( $this->data['month'].'/'.'01/'.$this->request->getPost('year'));
        $to = strtotime( $this->data['month'].'/'.$last_day.'/'.$this->request->getPost('year'));

       // $where = "teacher is  NOT NULL";
        $att = (new \App\Models\Attendance())->where('session',active_session())
            ->where('timestamp >=',$from)->where('timestamp <=',$to)->where('teacher >',1)->findAll();

        $teachers = array();
        foreach ($att as $item) {
                if (isset($teachers[$item['timestamp']])) {
                    $teachers[$item['timestamp']] = [
                        'absent' => $item['status'] == 0 ? $teachers[$item['timestamp']]['absent'] +=1  : $teachers[$item['timestamp']]['absent'],
                        'permission' => $item['status'] == 2 ? $teachers[$item['timestamp']]['permission'] +=1 : $teachers[$item['timestamp']]['permission'],
                        'sick' => $item['status'] == 3 ? $teachers[$item['timestamp']]['sick'] +=1 : $teachers[$item['timestamp']]['sick'],
                        'late' => $item['status'] == 4 ? $teachers[$item['timestamp']]['late'] +=1 : $teachers[$item['timestamp']]['late'],
                        'teachers' => $teachers[$item['timestamp']]['teachers'] +=1,
                        'date' => date('d/m/Y', $item['timestamp'])
                    ];
                } else {
                    $teachers[$item['timestamp']] = [
                        'absent' => $item['status'] == 0 ? 1 : 0,
                        'permission' => $item['status'] == 2 ? 1 : 0,
                        'sick' => $item['status'] == 3 ? 1 : 0,
                        'late' => $item['status'] == 4 ? 1 : 0,
                        'teachers' => 1,
                        'date' => date('d/m/Y', $item['timestamp'])
                    ];
                }
        }


         $this->data['attendance'] = $teachers;
        //$this->data['attendance'] = (new \App\Models\Attendance())->where('class', $class)->where('section', $section);

        //return view('Attendance/rec_teachers', $this->data);
        return view('Attendance/att_teachers_monthly', $this->data);
    }

    public function getStudentsAjax()
    {
        $section = $this->request->getPost('section');
        $section = (new Sections())->find($section);
        if(!$section) {
            $resp = [
                'title' => 'Error',
                'message'   => 'Selected section does not exist',
                'status'    => 'error'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        $this->data['section'] = $section;
        $this->data['date'] = $this->request->getPost('date');
        //$this->data['attendance'] = (new \App\Models\Attendance())->where('class', $class)->where('section', $section);

        return view('Attendance/rec_students', $this->data);

    }

    public function postStudentsAjax()
    {
        $option = $this->request->getPost('option');
        $time = $this->request->getPost('date');
         $students = (new Students())->where('session',active_session())->findAll();

        $timestamp = strtotime($time);
        $model = new \App\Models\Attendance();
        if (count($students) > 0) {
                foreach ($students as $student) {
                    if (isset($student->class->id) && isset($student->section->id)){
                    $entry = $model->where('student', $student->id)->where('timestamp', $timestamp)->where('session', active_session())->get()->getRowObject();

                    $to_db = [
                        'class' =>  $student->class->id,
                        'section' => $student->section->id,
                        'student' => $student->id,
                        'status' => 5,
                        'timestamp' => $timestamp,
                        'session' => active_session(),
                        'option_type' => $option
                    ];

                    if (isset($entry) && !empty($entry)) {
                        $to_db['id'] = $entry->id;
                    }

                    try {
                        if (isset($entry) && !empty($entry) && $option == 'normal') {
                            $model->delete($entry->id);
                        } elseif ($option != 'normal') {
                            $db = \Config\Database::connect();
                            $builder = $db->table('attendance');
                            if (!empty($entry)){
                                $builder->where('id',$entry->id);
                                $builder->update($to_db);
                            }else {
                             $builder->insert($to_db);
                            }

                        }
                    } catch (\ReflectionException $e) {
                        //TODO: Log this exception
                        var_dump($e->getMessage());
                    }
                }
        }

            $return = TRUE;
            $msg = "Attendance recorded successfully";
        } else {
            $return = FALSE;
            $msg = "No students found";
        }

        $status = $return ? 'success' : 'error';
        $resp = [
            'title'     => $return ? 'Success' : 'error',
            'message'   => $msg,
            'status'    => $status,
            'notifyType'    => 'swal',
            //'callbackTime' => 'onconfirm',
            'showCancelButton' => false,
            'callback'  => 'getStudents()'
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($resp));

    }
    public function postTeachersAjax()
    {
        $option = $this->request->getPost('option');
        $time = $this->request->getPost('date');
        $teachers = (new Teachers())->where('session',active_session())->findAll();

        $timestamp = strtotime($time);
        $model = new \App\Models\Attendance();
        if (count($teachers) > 0) {
            foreach ($teachers as $teacher) {
                    $entry = $model->where('teacher', $teacher->id)->where('timestamp', $timestamp)->where('session', active_session())->get()->getRowObject();

                    $to_db = [
                        'teacher' => $teacher->id,
                        'status' => 5,
                        'timestamp' => $timestamp,
                        'session' => active_session(),
                        'option_type' => $option
                    ];

                    if (isset($entry) && !empty($entry)) {
                        $to_db['id'] = $entry->id;
                    }

                    try {
                        if (isset($entry) && !empty($entry) && $option == 'normal') {
                            $model->delete($entry->id);
                        } elseif ($option != 'normal') {
                            $db = \Config\Database::connect();
                            $builder = $db->table('attendance');
                            if (!empty($entry)){
                                $builder->where('id',$entry->id);
                                $builder->update($to_db);
                            }else {
                                $builder->insert($to_db);
                            }

                        }
                    } catch (\ReflectionException $e) {
                        //TODO: Log this exception
                        var_dump($e->getMessage());
                    }
            }

            $return = TRUE;
            $msg = "Attendance recorded successfully";
        } else {
            $return = FALSE;
            $msg = "No students found";
        }

        $status = $return ? 'success' : 'error';
        $resp = [
            'title'     => $return ? 'Success' : 'error',
            'message'   => $msg,
            'status'    => $status,
            'notifyType'    => 'swal',
            //'callbackTime' => 'onconfirm',
            'showCancelButton' => false,
            'callback'  => 'getStudents()'
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($resp));

    }
    public function getTeachersAjax()
    {
        $teachers = (new Teachers())->findAll();
        if(!$teachers) {
            $resp = [
                'title' => 'Error',
                'message'   => 'No Teachers found',
                'status'    => 'error'
            ];
            return $this->response->setContentType('application/json')->setBody(json_encode($resp));
        }
        $this->data['teachers'] = $teachers;
        $this->data['date'] = $this->request->getPost('date');
        //$this->data['attendance'] = (new \App\Models\Attendance())->where('class', $class)->where('section', $section);

        return view('Attendance/rec_teachers', $this->data);

    }

    public function saveStudent()
    {
        $class = $this->request->getPost('class');
        $section = $this->request->getPost('section');
        $attendances = $this->request->getPost('attendance');
        $time = $this->request->getPost('date');
        $entryExists = $this->request->getPost('presence');
        $timestamp = strtotime($time);

        $model = new \App\Models\Attendance();
        $students_model = new Students();
        if (getDepartedIds() && count(getDepartedIds()) > 0)
           $students_model->whereNotIn("id",getDepartedIds());
        $students = $students_model->where('class', $class)->where('section', $section)->where('session', active_session())->findAll();
        if (count($students) > 0) {
            foreach ($students as $student) {
                //$student = $students_model->find($id);
                $to_db = [
                    //'class'  => $student->class->id,
                    'class'     => $class,
                    //'section'   => $student->section->id,
                    'section'   => $section,
                    'student'   => $student->id,
                    'status'    => (isset($attendances[$student->id])) ?  $attendances[$student->id] : 0,
                    'timestamp' => $timestamp,
                    'session' => active_session()
                ];

                if(isset($entryExists[$student->id]) && is_numeric($entryExists[$student->id])) {
                    $to_db['id'] = $entryExists[$student->id];
                }
                try {
                    $model->save($to_db);
                } catch (\ReflectionException $e) {
                    //TODO: Log this exception
                }
            }
            $return = TRUE;
            $msg = "Attendance recorded successfully";
        } else {
            $return = FALSE;
            $msg = "No students found";
        }

        $status = $return ? 'success' : 'error';
        $resp = [
            'title'     => $return ? 'Success' : 'error',
            'message'   => $msg,
            'status'    => $status,
            'notifyType'    => 'swal',
            //'callbackTime' => 'onconfirm',
            'showCancelButton' => false,
            'callback'  => 'getStudents()'
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($resp));
    }

    public function saveTeacher()
    {
        $attendances = $this->request->getPost('attendance');
        $time = $this->request->getPost('date');
        $entryExists = $this->request->getPost('presence');
        $timestamp = strtotime($time);
        $model = new \App\Models\Attendance();
        $teachers_model = new Teachers();
        //print_r($entryExists);
        //foreach ($attendances as $id=>$value) {
        foreach ($teachers_model->where('session',active_session())->findAll() as $teacher) {
            //$teacher = $teachers_model->find($id);
            $to_db = [
                'teacher'   => $teacher->id,
                'status'    => isset($attendances[$teacher->id]) ? $attendances[$teacher->id] : 0,
                'timestamp' => $timestamp,
                'session' => active_session()
            ];

            if(isset($entryExists[$teacher->id]) && is_numeric($entryExists[$teacher->id])) {
               $to_db['id'] = $entryExists[$teacher->id];
            }
            $model->save($to_db);
        }
        $return = TRUE;
        $msg = "Attendance recorded successfully";

        $status = $return ? 'success' : 'error';
        $resp = [
            'title'     => $return ? 'Success' : 'error',
            'message'   => $msg,
            'status'    => $status,
            'notifyType'    => 'swal',
            //'callbackTime' => 'onconfirm',
            'showCancelButton' => false,
            'callback'  => 'getTeachers()'
        ];

        return $this->response->setContentType('application/json')->setBody(json_encode($resp));
    }

    public function studentsPdf()
    {
        $this->data['section'] = (new Sections())->find($_GET['section']);
        $this->data['year'] = $_GET['year'];
        $this->data['month'] = $_GET['month'];
       return view("Attendance/listStudents/pdf",$this->data);
    }
    public function studentsPrint()
    {
        $this->data['section'] = (new Sections())->find($_GET['section']);
        $this->data['year'] = $_GET['year'];
        $this->data['month'] = $_GET['month'];
       return view("Attendance/listStudents/print",$this->data);
    }

    public function teachersPdf()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('teachers');
        $builder->select("users.surname,users.first_name,users.last_name,teachers.teacher_number,teachers.id as trID");
        $builder->join('users','users.id = teachers.user_id');
        $builder->orderBy('users.surname, users.first_name,users.last_name');
        $builder->where("teachers.session",active_session());
        $teachers = $builder->get()->getResult();

        $this->data['year'] = $_GET['year'];
        $this->data['month'] = $_GET['month'];
        $this->data['teachers'] = $teachers;
        
       return view("Attendance/listTeachers/pdf",$this->data);
    }
    public function teachersPrint()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('teachers');
        $builder->select("users.surname,users.first_name,users.last_name,teachers.teacher_number,teachers.id as trID");
        $builder->join('users','users.id = teachers.user_id');
        $builder->orderBy('users.surname, users.first_name,users.last_name');
        $builder->where("teachers.session",active_session());
        $teachers = $builder->get()->getResult();

        $this->data['year'] = $_GET['year'];
        $this->data['month'] = $_GET['month'];
        $this->data['teachers'] = $teachers;

       return view("Attendance/listTeachers/print",$this->data);
    }
}