<?php


namespace App\Entities;


use App\Models\CatExamSubmissions;
use App\Models\ClassSubjects;
use App\Models\Sections;
use App\Models\Semesters;
use App\Models\Sessions;

class CatExamItem extends \CodeIgniter\Entity
{
    protected $dates = ['given', 'deadline', 'created_at', 'updated_at'];
//    protected $datamap = [
//        'given'     => 'starting_date',
//        'deadline'  => 'ending_date'
//    ];

    public function getItems()
    {
        return @json_decode($this->attributes['items']);
    }

    public function getStarting_date()
    {
        return $this->attributes['given'];
    }

    public function getEnding_date()
    {
        return $this->attributes['deadline'];
    }

    public function getSemester()
    {
        return (new Semesters())->find($this->attributes['semester']);
    }

    public function getSession()
    {
        return (new Sessions())->find($this->attributes['session']);
    }

    public function getItemsCount()
    {
        return is_array($this->getItems()) ? count($this->getItems()) : 0;
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
        return (new CatExamSubmissions())
            ->where('subject', $this->attributes['subject'])
            ->where('cat_exam', $this->attributes['id'])
            //->where('cat_exam_item', $this->attributes['id'])
            ->where('student_id', $student_id)
            ->get()
            ->getFirstRow('object');
    }
}