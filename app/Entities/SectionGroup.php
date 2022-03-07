<?php


namespace App\Entities;


use App\Models\Sections;
use App\Models\Students;
use CodeIgniter\Entity;

class SectionGroup extends Entity
{
    public function getStudent()
    {
        return (new Students())->find($this->attributes['student']);
    }

    public function getSection()
    {
        return (new Sections())->find($this->attributes['section']);
    }

    public function getClass()
    {
        return $this->getSection()->class;
    }
}