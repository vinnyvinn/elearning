<?php


namespace App\Entities;


use App\Models\ClassSubjects;
use App\Models\ClassWorkSubmissions;
use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Sessions;

class ClassWorkItems extends \CodeIgniter\Entity
{
    protected $dates = ['given', 'deadline', 'created_at', 'updated_at'];

    public function getItems()
    {
        return @json_decode($this->attributes['items']);
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

    public function getSubmission($student_id)
    {
        return (new ClassWorkSubmissions())->where('subject', $this->attributes['subject'])
            //->where('class_work', $this->attributes['class_work'])
            ->where('classwork_item', $this->attributes['id'])
            ->where('student_id', $student_id)
            ->get()
            ->getFirstRow('object');
    }

    public function getSubmissions()
    {
        return (new ClassWorkSubmissions())->where('subject', $this->attributes['subject'])
            //->where('class_work', $this->attributes['class_work'])
            ->where('classwork_item', $this->attributes['id'])
            ->get()
            ->getFirstRow('object');
    }

    public function getSubmissionsTotals()
    {
        return (new ClassWorkSubmissions())->where('subject', $this->attributes['subject'])
            //->where('class_work', $this->attributes['class_work'])
            ->where('classwork_item', $this->attributes['id'])
            ->selectSum('score', 'score_total')
            ->get()
            ->getResultObject()
            ->score_total;
    }
}