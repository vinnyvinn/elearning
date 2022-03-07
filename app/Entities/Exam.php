<?php


namespace App\Entities;


use App\Models\ExamResults;
use App\Models\Quarters;
use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Sessions;
use CodeIgniter\Entity;

class Exam extends Entity
{
    public function getSession()
    {
        if($this->attributes['session'] != '') {
            return (new Sessions())->find($this->attributes['session']);
        }

        return FALSE;
    }

    public function getSemester()
    {
        if($this->attributes['semester'] != '') {
            return (new Semesters())->find($this->attributes['semester']);
        }

        return FALSE;
    }
    public function getQuarter()
    {
        if($this->attributes['quarter'] != '') {
            return (new Quarters())->find($this->attributes['quarter']);
        }

        return FALSE;
    }
    public function getClass()
    {
        if($cls = $this->attributes['class']) {
            return (new \App\Models\Classes())->find($cls);
        }

        return FALSE;
    }

    public function getSection()
    {
        if($sec = $this->attributes['section']) {
            return (new Sections())->find($sec);
        }

        return FALSE;
    }

    public function getResultObject()
    {
        return (new ExamResults())->where('exam', $this->attributes['id']);
    }

    public function getStudentResults($studentId)
    {
        return (new ExamResults())->where('exam', $this->attributes['id'])->where('student', $studentId)->get()->getFirstRow();
    }
}