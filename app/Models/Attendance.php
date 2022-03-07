<?php


namespace App\Models;


use CodeIgniter\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $primaryKey = 'id';

    protected $allowedFields = ['timestamp', 'student', 'teacher', 'status', 'class', 'section','session'];

   //protected $returnType = '\App\Entities\Attendance';

    public function daysStudentAttendance($date = FALSE)
    {
        $date  = $date ? $date : date('m/d/Y');
        $sql1 = $this->where('timestamp', strtotime($date))->where('student !=', '')->where('student !=', NULL)->where('status =',1)->countAllResults();
        $sql2 = $this->where('timestamp', strtotime($date))->where('student !=', '')->where('student !=', NULL)->where('status =',4)->countAllResults();
        $sql1 = $sql1?:0;
        $sql2 = $sql2?:0;
        return $sql1 + $sql2;
    }
    public function daysTeachersAttendance($date = FALSE)
    {
        $date  = $date ? $date : date('m/d/Y');
        $sql1 =  $this->where('timestamp', strtotime($date))->where('teacher !=', '')->where('teacher !=', NULL)->where('status =',1)->countAllResults();
        $sql2 =  $this->where('timestamp', strtotime($date))->where('teacher !=', '')->where('teacher !=', NULL)->where('status =',4)->countAllResults();
        $sql1 = $sql1?:0;
        $sql2 = $sql2?:0;
        return $sql1 + $sql2;
    }
}