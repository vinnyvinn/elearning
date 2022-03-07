<?php


namespace App\Entities;


use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Sessions;
use App\Models\Students;
use CodeIgniter\Entity;

class Fee extends Entity
{
    public function getSession()
    {
        if($session = $this->attributes['session']) {
            return (new Sessions())->find($session);
        }

        return FALSE;
    }

    public function getSemester()
    {
        if($semester = $this->attributes['semester']) {
            return (new Semesters())->find($semester);
        }

        return FALSE;
    }

    public function getClass()
    {
        if($class = $this->attributes['class']) {
            return (new \App\Models\Classes())->find($class);
        }

        return FALSE;
    }

    public function getSection()
    {
        if($section = $this->attributes['section']) {
            return (new Sections())->find($section);
        }

        return FALSE;
    }

    public function getStudent()
    {
        if($student = $this->attributes['student']) {
            return (new Students())->find($student);
        }

        return FALSE;
    }
}