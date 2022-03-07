<?php


namespace App\Entities;


use App\Models\Assignments;
use App\Models\Notes;
use App\Models\Subjects;
use App\Models\Subjectteachers;
use CodeIgniter\Entity;

class ClassSubject extends Entity
{

    public function getName()
    {
        if($info = (new Subjects())->find($this->attributes['subject'])) {
            return $info->name;
        }
        return false;
    }

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
            //->getCompiledSelect();
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