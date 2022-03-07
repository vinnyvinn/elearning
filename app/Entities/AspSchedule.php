<?php


namespace App\Entities;


use App\Models\ClassSubjects;
use App\Models\Sections;
use CodeIgniter\Entity;

class AspSchedule extends Entity
{
    public function getSubject()
    {
        return (new ClassSubjects())->find($this->attributes['subject']);
    }

    public function getClass()
    {
        return (new \App\Models\Classes())->find($this->attributes['class']);
    }
    public function getSection()
    {
        return (new Sections())->find($this->attributes['section']);
    }
}