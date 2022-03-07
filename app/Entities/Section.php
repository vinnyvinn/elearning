<?php


namespace App\Entities;


use App\Models\Assignments;
use App\Models\Notes;
use App\Models\Students;
use App\Models\Teachers;
use CodeIgniter\Entity;

class Section extends Entity
{
    public function getClass()
    {
        return (new \App\Models\Classes())->find($this->attributes['class']);
    }

    public function getNotes()
    {
        return (new Notes())->where('class', $this->attributes['class'])
            ->orderBy('id', 'DESC')
            //->get()->getCustomResultObject('App\Entities\Note');
            ->findAll();
    }

    public function getAssignments($subject)
    {
        return (new \App\Models\Assignments())
            ->where('class', $this->attributes['class'])
            ->where('subject', $subject)
            //->groupStart()
                //->where('section', $this->attributes['id'])
                //->orWhere('section', '')
                //->orWhere('section', NULL)
            //->groupEnd()
            ->orderBy('id', 'DESC')
            ->findAll();
    }
    public function getAss()
    {
        return (new \App\Models\Assignments())
            ->where('class', $this->attributes['class'])
            //->groupStart()
                //->where('section', $this->attributes['id'])
                //->orWhere('section', '')
                //->orWhere('section', NULL)
            //->groupEnd()
            ->orderBy('id', 'DESC')
            ->findAll();
    }

    public function getStudents()
    {
        $model = (new Students())->where('class', $this->attributes['class'])->where('section', $this->attributes['id'])
            ->select("students.*,students.id as id")
            ->join('users','users.id=students.user_id');
        if (getDepartedIds() && count(getDepartedIds()) > 0)
            $model->whereNotIn("students.id",getDepartedIds());
        $students = $model->where('class !=',null)
            ->orderBy('users.surname, users.first_name,users.last_name');
        return $students->findAll();
    }
    public function getStudentsActive()
    {
        $students = (new Students())->where('class', $this->attributes['class'])->where('section', $this->attributes['id'])
            ->select("students.*,students.id as id")
            ->join('users','users.id=students.user_id')
            ->where('class !=',null)
            ->orderBy('users.surname, users.first_name,users.last_name')
            ->findAll();
        return $students;
    }

    public function getExams()
    {

    }

    public function getAdvisor()
    {
        if($this->attributes['advisor']) {
            return (new Teachers())->find($this->attributes['advisor']);
        }

        return FALSE;
    }
    public function getAdvisorid()
    {
     return $this->attributes['advisor'];
    }
}