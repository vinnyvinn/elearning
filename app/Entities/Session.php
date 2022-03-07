<?php


namespace App\Entities;


use App\Models\ClassGroups;
use App\Models\Departments;
use App\Models\Exams;
use App\Models\Quarters;
use App\Models\SectionGroups;
use App\Models\Semesters;
use App\Models\Teachers;
use CodeIgniter\Entity;

class Session extends Entity
{
    /**
     * Get classes for this session
     *
     * @return \CodeIgniter\Database\BaseBuilder
     */
    public function classes()
    {
        return (new \App\Models\Classes())->where('session', active_session())->orderBy('id', 'DESC');
    }
    public function getClassess()
    {
      return (new \App\Models\Classes())->where('session', $this->attributes['id'])->orderBy('id', 'DESC')->findAll();
    }

    public function getClasses()
    {
     return $this->classes();
    }

    public function getSemesters()
    {
        return (new Semesters())->where('session', $this->attributes['id'])->findAll();
    }
    public function getTeachers()
    {
        return (new Teachers())->where('session', $this->attributes['id'])->findAll();
    }

    public function getGroups()
    {
        return (new ClassGroups())->where('session', $this->attributes['id'])->findAll();
    }
    public function getDepartments()
    {
        return (new Departments())->where('session', $this->attributes['id'])->findAll();
    }
    public function getQuarters()
    {
        return (new Quarters())->where('session', $this->attributes['id'])->findAll();
    }

    public function getStudents()
    {
        $model =  (new \App\Models\Students())->where('session', active_session())
             ->select("students.*,students.id as id")
            ->join('users','users.id=students.user_id');
            if (getDepartedIds() && count(getDepartedIds()) > 0)
               $model->whereNotIn("students.id",getDepartedIds());
           $model->where('class !=',null)
            ->orderBy('users.surname, users.first_name,users.last_name');
        return $model->findAll();
    }

    public function getStudentsArr()
    {
        $model =  (new \App\Models\Students())->where('session', active_session())
            ->select("students.*,students.id as id")
            ->join('users','users.id=students.user_id');
        if (getDepartedIds() && count(getDepartedIds()) > 0)
            $model->whereNotIn("students.id",getDepartedIds());
        $students = $model->where('class !=',null)
            ->orderBy('users.surname, users.first_name,users.last_name');
        return  $students->get()->getResultArray();
    }
    public function getStudents2()
    {
        (new \App\Models\User());
        return (new \App\Models\Students())->where('session', active_session())
            ->select("students.*,students.id as id")
            ->join('users','users.id=students.user_id')
            ->where('class !=',null)
            ->orderBy('users.first_name DESC, users.last_name DESC')
            ->findAll();
    }

    public function getExams()
    {
       return (new Exams())->where('session',$this->attributes['id'])->findAll();
    }
}