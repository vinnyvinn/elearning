<?php


namespace App\Controllers\Parent;


use App\Controllers\ParentController;
use App\Models\Students;

class Attendance extends ParentController
{
    public function __construct()
    {
        parent::__construct();
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

        return view('Parent/Student/attendance', $data);
    }
    public function ajaxDetails()
    {

        $student = $this->request->getPost('student');
        $attendance = (new \App\Models\Attendance())->where('student',$student)->orderBy('date_created','DESC')->get()->getRow();
       if ($attendance)
        echo json_encode(['year'=>date('Y',$attendance->timestamp),'month'=>date('m',$attendance->timestamp),'student'=>$student]);
       else
        echo json_encode([]);
    }
}