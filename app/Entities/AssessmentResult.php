<?php


namespace App\Entities;


use App\Models\ClassSubjects;
use App\Models\Students;

class AssessmentResult extends \CodeIgniter\Entity
{
    public function getStudent()
    {
        return (new Students())->find($this->attributes['student']);
    }

    public function getClass()
    {
        return (new \App\Models\Classes())->find($this->attributes['class']);
    }

    public function getSubject()
    {
        return (new ClassSubjects())->find($this->attributes['subject']);
    }

    public function getSession()
    {
        return getSession($this->attributes['session']);
    }
}