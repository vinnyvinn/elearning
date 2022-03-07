<?php


namespace App\Entities;


use App\Models\ClassSubjects;
use App\Models\Exams;
use App\Models\Students;
use App\Models\Subjects;
use CodeIgniter\Entity;

class ExamResult extends Entity
{
    public function getExam()
    {
        return (new Exams())->find($this->attributes['exam']);
    }

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

    public function getClassRank()
    {

    }

    public function getSectionRank($section)
    {

    }
}