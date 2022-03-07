<?php


namespace App\Entities;


use App\Models\ClassSubjects;
use App\Models\Sections;
use App\Models\Subjects;
use CodeIgniter\Entity;

class Note extends Entity
{
    protected $dates = ['created_at', 'updated_at'];

    public function getFile()
    {
        return base_url('uploads/notes/'.$this->attributes['file']);
    }

    public function getPath()
    {
        return FCPATH.'uploads/notes/'.$this->attributes['file'];
    }

    public function getSubject()
    {
        return (new ClassSubjects())->find($this->attributes['subject']);
    }

    public function getClass()
    {
        if($this->attributes['class']) {
            return (new \App\Models\Classes())->find($this->attributes['class']);
        }

        return FALSE;
    }
    public function getSection()
    {
        if($this->attributes['section']) {
            return (new Sections())->find($this->attributes['section']);
        }

        return FALSE;
    }
}