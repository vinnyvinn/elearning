<?php


namespace App\Controllers\Teacher;


use App\Controllers\AdminController;
use App\Controllers\TeacherController;
use App\Models\Classes;
use App\Models\Sections;
use App\Models\Students;
use App\Models\Teachers;

class Attendance extends TeacherController
{
    public function students()
    {
      return $this->_renderPage('Attendance/students', $this->data);
    }

    public function recordStudents()
    {
        $this->data['attend'] = (new \App\Models\Attendance())->where('timestamp',strtotime(date('m/d/Y')))->orderBy('date_created','DESC')->where('option_type !=','normal')->where('student !=',null)->where('student !=','')->get()->getRowObject();
        return $this->_renderPage('Attendance/students_attendance', $this->data);
    }

    public function recordTeachers()
    {
        return $this->_renderPage('Attendance/teachers_attendance', $this->data);
    }

    public function teachers()
    {
        return $this->_renderPage('Attendance/teachers', $this->data);
    }
    public function getAttendanceAjax()
    {
        $time = $this->request->getPost('date');
        $timestamp = strtotime($time);
        $attendance = (new \App\Models\Attendance())->where('timestamp',$timestamp)->orderBy('date_created','DESC')->where('option_type !=','normal')->where('student !=',null)->where('student !=','')->get()->getRowObject();
        return json_encode($attendance);
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
    public function getTeachers()
    {
        $this->data['month'] = $this->request->getPost('month');
        $this->data['year'] = $this->request->getPost('year');
        $teachers = (new Teachers())->findAll();
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

        return view('Teacher/Attendance/rec_students', $this->data);

    }
    public function postStudentsAjax()
    {
        $option = $this->request->getPost('option');
        $time = $this->request->getPost('date');
     //   $students = (new Students())->where('session',active_session())->where('active',1)->findAll();
        $sections = (new \App\Models\Sections())->where('advisor',$this->data['teacher']->id)->where('session',active_session())->findAll();

        $students = array();
        foreach ($sections as $section){
            foreach ($section->students as $std){
                if ($std->active==1)
                    array_push($students,$std);
            }
        }

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
        $section = $this->request->getPost('section');
        $attendances = $this->request->getPost('attendance');
        $time = $this->request->getPost('date');
        $entryExists = $this->request->getPost('presence');
        $timestamp = strtotime($time);
        $model = new \App\Models\Attendance();
        $students_model = new Students();

        $students = $students_model->where('section',$section)->where('active',1)->findAll();

        foreach ($students as $student) {
            //$student = $students_model->find($id);
            $to_db = [
                'class'  => $student->class->id,
                'section'   => $student->section->id,
                'student'   => $student->id,
                'status'    => (isset($attendances[$student->id])) ?  $attendances[$student->id] : 0,
                'timestamp' => $timestamp
            ];
            if(isset($entryExists[$student->id]) && is_numeric($entryExists[$student->id])) {
                $to_db['id'] = $entryExists[$student->id];
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
        foreach ($teachers_model->findAll() as $teacher) {
            //$teacher = $teachers_model->find($id);
            $to_db = [
                'teacher'   => $teacher->id,
                'status'    => @$attendances[$teacher->id] == 1 ? 1 : 0,
                'timestamp' => $timestamp
            ];

            if(isset($entryExists[$teacher->id]) && is_numeric($entryExists[$teacher->id])) {
                $to_db['id'] = $entryExists[$teacher->id];
            }

            //print_r($to_db);
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

    public function ajaxAttendance()
    {
        $month = $this->request->getPost('month');
        $year = $this->request->getPost('year');
        $student = $this->request->getPost('student');

        $student = (new Students())->find($student);
        if(!$month || !$year || !$student) {
            return $this->response->setStatusCode(404)->setBody("Invalid request");
        }

        $data = [
            'month'     => $month,
            'year'      => $year,
            'student'   => $student
        ];

        return view('Teacher/Student/Attendance/attendance', $data);
    }
}