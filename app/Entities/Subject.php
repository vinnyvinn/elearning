<?php


namespace App\Entities;


use App\Models\Assignments;
use App\Models\Notes;
use App\Models\Subjectteachers;
use CodeIgniter\Entity;

class Subject extends Entity
{
    public function getNotes()
    {
        return (new Notes())->where('class', $this->attributes['class'])
            ->where('subject', $this->attributes['id'])
//            ->groupStart()
//                ->where('section', $this->class->section)
//                ->orWhere('section', '')
//            ->groupEnd()
            ->orderBy('id', 'DESC')
            //->get()->getCustomResultObject('App\Entities\Note');
            ->findAll();
    }

    public function getAssignments()
    {
        return (new Assignments())->where('class', $this->attributes['class'])
            ->where('subject', $this->attributes['id'])
//            ->groupStart()
//                ->where('section', $this->class->section)
//                ->orWhere('section', '')
//            ->groupEnd()
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    public function getTeacher($section = '')
    {
        return (new Subjectteachers())
            ->where('class_id', $this->attributes['class'])
            ->where('section_id', $section)
            ->where('subject_id', $this->attributes['id'])
            ->get()->getCustomRowObject(1, 'App\Entities\Subjectteacher');
    }
}