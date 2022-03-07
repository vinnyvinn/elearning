<?php


namespace App\Entities;


use App\Models\Students;
use CodeIgniter\Entity;

class ClassGroup extends Entity
{

    public function getStudents()
    {
        return (new Students())->where('section',$this->attributes['section'])->findAll();
    }

}