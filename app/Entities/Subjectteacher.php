<?php


namespace App\Entities;


use App\Models\ClassSubjects;
use App\Models\Sections;
use App\Models\Subjects;
use App\Models\Teachers;
use CodeIgniter\Entity;

class Subjectteacher extends Entity
{
    public function getProfile()
    {
        $teacher = (new Teachers())->find($this->attributes['teacher_id']);
        if($teacher) {
            return (new \App\Models\User())->find($teacher->user_id);
        }

        return false;
    }

    public function getTeacher()
    {
        return (new Teachers())->find($this->attributes['teacher_id']);
    }

    public function getClass()
    {
        return (new \App\Models\Classes())->find($this->attributes['class_id']);
    }

    public function getSection()
    {
        return (new Sections())->find($this->attributes['section_id']);
    }

    public function getSubject()
    {
        return (new ClassSubjects())->find($this->attributes['subject_id']);
    }
}