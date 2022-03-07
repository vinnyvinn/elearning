<?php


namespace App\Entities;


use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Sessions;

class CatExam extends \CodeIgniter\Entity
{
    protected $dates = ['starting_date', 'ending_date', 'updated_at', 'created_at'];

    public function getClass()
    {
        return (new \App\Models\Classes())->find($this->attributes['class']);
    }

    public function getSection()
    {
        return (new Sections())->find($this->attributes['section']);
    }

    public function getSession()
    {
        return (new Sessions())->find($this->getClass()->session);
    }

    public function getSemester()
    {
        if($this->attributes['semester']) {
            return (new Semesters())->find($this->attributes['semester']);
        }

        return FALSE;
    }

    public function getItems()
    {
        return (new \App\Models\CatExamItems())->where('cat_exam', $this->attributes['id'])->findAll();
    }
}