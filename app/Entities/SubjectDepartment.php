<?php


namespace App\Entities;


use App\Models\Departments;
use App\Models\Subjects;
use CodeIgniter\Entity;

class SubjectDepartment extends Entity
{
    public function getSubject()
    {
        return (new Subjects())->find($this->attributes['subject']);
    }

    public function getDepartment()
    {
        return (new Departments())->find($this->attributes['dept_id']);
    }
}