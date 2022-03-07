<?php


namespace App\Entities;


use App\Models\Students;
use CodeIgniter\Entity;

class Submission extends Entity
{
    public function getStudent()
    {
        return (new Students())->find($this->attributes['student_id']);
    }

    public function getFile()
    {
        return base_url('uploads/assignments/'.$this->attributes['file']);
    }

    public function getPath()
    {
        return FCPATH.'uploads/assignments/'.$this->attributes['file'];
    }
}