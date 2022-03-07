<?php


namespace App\Entities;


use App\Models\AssignmentSubmissions;
use App\Models\ClassSubjects;
use App\Models\QuizSubmissions;
use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Sessions;
use CodeIgniter\Entity;

class AssignmentItem extends Entity
{
    protected $dates = ['given', 'deadline', 'created_at', 'updated_at'];

    public function getItems()
    {
        if($items = json_decode($this->attributes['items'])) {
            return $items;
        }
        
        return null;
    }

    public function getSemester()
    {
        return (new Semesters())->find($this->attributes['semester']);
    }

    public function getSession()
    {
        return (new Sessions())->find($this->attributes['session']);
    }

    public function getClass()
    {
        return (new \App\Models\Classes())->find($this->attributes['class']);
    }

    public function getSection()
    {
        return (new Sections())->find($this->attributes['section']);
    }

    public function getSubject()
    {
        return (new ClassSubjects())->find($this->attributes['subject']);
    }
    public function isSubmitted($studentId)
    {
        $submission = (new AssignmentSubmissions())
            ->where('student_id', $studentId)
            ->where('assignment_id', $this->attributes['id'])
            ->first();
        if(!empty($submission)) {
            return $submission;
        }

        return false;
    }

    public function getSubmission($student_id)
    {
        return (new AssignmentSubmissions())->where('subject', $this->attributes['subject'])
            //->where('quiz', $this->attributes['quiz'])
            ->where('quiz_item', $this->attributes['id'])
            ->where('student_id', $student_id)
            ->get()
            ->getFirstRow('object');
    }
}