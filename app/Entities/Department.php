<?php


namespace App\Entities;


use App\Models\SubjectDepartments;
use App\Models\Teachers;
use CodeIgniter\Entity;

class Department extends Entity
{
    public function getHead()
    {
        $head = $this->attributes['head'];
        if($head) {
            return (new Teachers())->find($head);
        }

        return FALSE;
    }

    public function getSubjects()
    {
        return (new SubjectDepartments())->where('dept_id', $this->attributes['id'])->findAll();
    }
}