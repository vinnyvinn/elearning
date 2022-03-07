<?php


namespace App\Entities;


use App\Models\QuizItems;
use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Sessions;

class Quiz extends \CodeIgniter\Entity
{
    public $dates = ['created_at', 'updated_at', 'given', 'deadline'];

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
        return (new QuizItems())->where('quiz', $this->attributes['id'])->findAll();
    }
}