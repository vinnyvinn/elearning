<?php


namespace App\Entities;


use App\Models\ClassSubjects;
use App\Models\Sections;
use App\Models\Subjects;
use App\Models\Submissions;
use CodeIgniter\Entity;

class Assignment extends Entity
{
    public function getFile()
    {
        return base_url('uploads/assignments/'.$this->attributes['file']);
    }

    public function getPath()
    {
        return FCPATH.'uploads/assignments/'.$this->attributes['file'];
    }

    public function getClass()
    {
        return (new \App\Models\Classes())->find($this->attributes['class']);
    }

    public function getSection()
    {
        if(!$this->attributes['section']) return false;

        return (new Sections())->find($this->attributes['section']);
    }

    public function getSubject()
    {
        return (new ClassSubjects())->find($this->attributes['subject']);
    }

    // Get submissions
    public function isSubmitted($studentId)
    {
        $submission = (new Submissions())
            ->where('student_id', $studentId)
            ->where('assignment_id', $this->attributes['id'])
            ->where('subject_id', $this->attributes['subject'])
            //->get()->getRow(0, 'App\Entities\Submission');
            ->first();
        if(!empty($submission)) {
            return $submission;
        }

        return false;
    }

    public function getSubmission($studentId)
    {
        return $this->isSubmitted($studentId);
    }
}